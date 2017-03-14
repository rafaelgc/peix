<?php
/**
 * Created by PhpStorm.
 * User: Rafa
 * Date: 14/03/2017
 * Time: 1:05
 */
include 'external/simple_html_dom.php';
require __DIR__ . '/vendor/autoload.php';
include 'Offer.php';

Predis\Autoloader::register();

ini_set('display_errors', 1);

$client = new Predis\Client();

$value = $client->get('peix.offers');

if ($value === null) {
    $html = file_get_html('https://www.inf.upv.es/int/peix/alumnos/listado_ofertas.php');

    $offers = $html->find('table[class=tabla_base]');

    $offerList = array();

    foreach ($offers as &$offer) {
        $newOffer = new Offer();

        $rows = $offer->find('tr');

        //Una parte de la información se puede extraer de la página principal...

        $newOffer->setCode($rows[0]->find('td')[3]->plaintext);
        $newOffer->setPublicationDate(trim($rows[0]->find('td')[1]->plaintext));
        $newOffer->setCompany(trim($rows[1]->find('td')[1]->find('a')[0]->plaintext));
        $newOffer->setLocation(trim($rows[2]->find('td')[1]->plaintext));
        $newOffer->setStart(trim($rows[3]->find('td')[1]->plaintext));
        $newOffer->setHours(trim($rows[4]->find('td')[1]->plaintext));
        $newOffer->setPay(trim($rows[4]->find('td')[3]->plaintext));
        $newOffer->setTasks(trim($rows[6]->find('td')[1]->plaintext));
        $newOffer->setProfile(trim($rows[7]->find('td')[1]->plaintext));

        //Pero otra parte hay que obtenerla de la página de la oferta. Por eso, para cada oferta listada en la página
        //principal se hará una nueva petición (POST) para obtener más datos de la oferta.

        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content' => 'codigo_oferta=' . $newOffer->getCode(),
                'timeout' => 10
            )
        );

        $context  = stream_context_create($opts);
        $url = 'https://www.inf.upv.es/int/peix/alumnos/detalle_oferta.php';
        $moreOfferDetails = file_get_contents($url, false, $context, -1, 40000);

        $offerDetailsRoot = str_get_html($moreOfferDetails);

        $table = $offerDetailsRoot->find('table[class=tabla_base]');

        $detailsRows = $table[0]->find('tr');

        $newOffer->setDescription(trim($detailsRows[3]->find('td')[1]->plaintext));
        $newOffer->setDuration(trim($detailsRows[6]->find('td')[1]->plaintext));
        $newOffer->setWorkingDay(trim($detailsRows[7]->find('td')[1]->plaintext));
        $newOffer->setVacancies(trim($detailsRows[7]->find('td')[3]->plaintext));
        $newOffer->setObservations(trim($detailsRows[11]->find('td')[1]->plaintext));


        array_push($offerList, $newOffer);
    }

    $res = json_encode($offerList);
    $client->set('peix.offers', $res, "EX", 3600);

    echo $res;
}
else {
    echo $value;
}