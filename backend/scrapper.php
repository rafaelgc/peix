<?php
/**
 * Created by PhpStorm.
 * User: Rafa
 * Date: 14/03/2017
 * Time: 1:05
 */
include 'external/simple_html_dom.php';
include 'Offer.php';

$html = file_get_html('https://www.inf.upv.es/int/peix/alumnos/listado_ofertas.php');

$offers = $html->find('table[class=tabla_base]');

$offerList = array();

foreach ($offers as &$offer) {
    $newOffer = new Offer();

    $rows = $offer->find('tr');

    $newOffer->setPublicationDate(trim($rows[0]->find('td')[1]->plaintext));
    $newOffer->setCompany(trim($rows[1]->find('td')[1]->find('a')[0]->plaintext));
    $newOffer->setLocation(trim($rows[2]->find('td')[1]->plaintext));
    $newOffer->setStart(trim($rows[3]->find('td')[1]->plaintext));
    $newOffer->setHours(trim($rows[4]->find('td')[1]->plaintext));
    $newOffer->setPay(trim($rows[4]->find('td')[3]->plaintext));
    $newOffer->setTasks(trim($rows[6]->find('td')[1]->plaintext));
    $newOffer->setProfile(trim($rows[7]->find('td')[1]->plaintext));


    array_push($offerList, $newOffer);
}

echo json_encode($offerList);