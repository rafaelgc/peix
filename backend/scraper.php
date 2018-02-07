<?php
/**
 * Created by PhpStorm.
 * User: Rafa
 * Date: 14/03/2017
 * Time: 1:05
 */
include_once 'external/simple_html_dom.php';
require_once __DIR__ . '/vendor/autoload.php';

define('UPDATE_MIN_TIME', 5); //Minutos.

ini_set('display_errors', 0);

function changeDateFormat($date) {
    $date = DateTime::createFromFormat('d-m-Y', $date);
    return $date->format('Y-m-d');
}

function needsUpdate($redisClient) {
    $lastUpdate = $redisClient->get('peix.lastUpdate');
    return $lastUpdate === null || (time() - intval($lastUpdate) > UPDATE_MIN_TIME * 60);
}

function runScraper($redisClient) {

    //Se obtiene la lista de ofertas que el sitema tiene actualmente en la base de datos.
    //Esto permitirá ver si, desde la última actualización, se ha publicado alguna oferta nueva y, de ser así, se
    //solicita su información.
    $offersList = $redisClient->get('peix.offers');
    if ($offersList !== null) {
        $offersList = json_decode($offersList, true);
    }
    else {
        $offersList = array();
    }

    $html = file_get_html('https://www.inf.upv.es/int/peix/alumnos/listado_ofertas.php');

    $offers = $html->find('table[class=tabla_base]');
    $newOffers = array();

    foreach ($offers as &$offer) {
        $newOffer = array();

        $rows = $offer->find('tr');

        //Una parte de la información se puede extraer de la página principal...
        $newOffer['code'] = (intval(trim($rows[0]->find('td')[3]->plaintext)));
        $newOffer['start'] = changeDateFormat(trim($rows[3]->find('td')[1]->plaintext));
        $newOffer['publish'] = changeDateFormat(trim($rows[0]->find('td')[1]->plaintext));

        //Conocido el código de la oferta, consultaremos si ya la teníamos guardada para evitar tener que hacer
        //la consulta adicional. Notar que, haciendo esto, impediríamos que las actualizaciones hechas sobre
        //las ofertas se vieran reflejadas en esta aplicación.
        //Notar también que las ofertas NO están guardadas ordenadamente en función de su identificador, por tanto,
        //habrá que comprobar entre todas las ofertas si ya está guardada la "nueva" para verificar si realmente lo es.
        $ignoreOffer = false;

        foreach ($offersList as $key => &$off) {
            //Con $newOffer['start'] === $off['start'] se puede conseguir que dos ofertas
            //con el mismo código puedan actualizarse si sus fechas de inicio son distintas.
            if ($newOffer['code'] == $off['code'] && $newOffer['start'] === $off['start'] && $newOffer['publish'] === $off['publish']) {
                $ignoreOffer = true;
                break;
            }

            //Se si se encuentra una oferta con el mismo código pero distinta fecha de publicación
            //significa que se ha actualizado. Hay que eliminar la vieja.
            if ($newOffer['code'] == $off['code'] && $newOffer['publish'] != $off['publish']) {
                unset($offersList[$key]);
                break;
            }
        }

        if ($ignoreOffer) {
            continue;
        }

        $newOffer['publicationDate'] = changeDateFormat(trim($rows[0]->find('td')[1]->plaintext));
        $newOffer['company'] = utf8_encode(trim($rows[1]->find('td')[1]->find('a')[0]->plaintext));
        $newOffer['location'] = utf8_encode(trim($rows[2]->find('td')[1]->plaintext));
        $newOffer['hours'] = (trim($rows[4]->find('td')[1]->plaintext));
        $newOffer['pay'] = floatval(explode(' ', trim($rows[4]->find('td')[3]->plaintext))[0]);
        $newOffer['tasks'] = utf8_encode(trim($rows[6]->find('td')[1]->plaintext));
        $newOffer['profile'] = utf8_encode(trim($rows[7]->find('td')[1]->plaintext));
        $newOffer['pfc'] = utf8_encode(trim($rows[5]->find('td')[1]->plaintext));

        //Otra parte de la información hay que obtenerla de la página de la oferta. Por eso, para cada oferta listada en la página
        //principal se hará una nueva petición (POST) para obtener más datos de la oferta.

        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content' => 'codigo_oferta=' . $newOffer['code'],
                'timeout' => 10
            )
        );

        $context  = stream_context_create($opts);
        $url = 'https://www.inf.upv.es/int/peix/alumnos/detalle_oferta.php';
        $moreOfferDetails = file_get_contents($url, false, $context, 0, 40000);

        $offerDetailsRoot = str_get_html($moreOfferDetails);

        $table = $offerDetailsRoot->find('table[class=tabla_base]');

        $detailsRows = $table[0]->find('tr');

        $duration = intval(explode(' ', trim($detailsRows[6]->find('td')[1]->plaintext))[0]);

        $newOffer['description'] = utf8_encode(trim($detailsRows[3]->find('td')[1]->plaintext));
        $newOffer['duration'] = ($duration);
        $newOffer['workingDay'] = utf8_encode(trim($detailsRows[7]->find('td')[1]->plaintext));
        $newOffer['vacancies'] = (intval(trim($detailsRows[7]->find('td')[3]->plaintext)));
        $newOffer['observations'] = utf8_encode(trim($detailsRows[11]->find('td')[1]->plaintext));
        $newOffer['continuity'] = utf8_encode(trim($detailsRows[9]->find('td')[1]->plaintext));

        array_push($newOffers, $newOffer);
    }

    for ($i = count($newOffers) - 1; $i >= 0; $i--) {
        array_unshift($offersList, $newOffers[$i]);
    }

    $res = json_encode($offersList);

    $redisClient->set('peix.offers', $res);
    $redisClient->set('peix.lastUpdate', time());
}

function getOffers($redisClient) {
    $offers = json_decode($redisClient->get('peix.offers'));
    for ($i = 0; $i < count($offers); $i++) {

        foreach ($offers[$i] as $key => $value) {
            $offers[$i]->$key = htmlentities(utf8_decode($value));
        }

    }
    return json_encode($offers);

}
