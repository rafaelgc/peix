<?php
/**
 * Created by PhpStorm.
 * User: rafa
 * Date: 3/14/17
 * Time: 11:58 PM
 */
//ini_set('display_errors', 1);
header('Content-type: application/json; charset=utf-8');

$result = array();

require __DIR__ . '/vendor/autoload.php';
require 'scraper.php';

$client = new Predis\Client();

//Se guarda en value la lista de ofertas.
$value = $client->get('peix.offers');

//Si la última actualización es muy antigua o, directamente, nunca se ejecutó
//el scraper, se ejecuta. No obstante, el usuario obtendrá el resultado viejo.

if (needsUpdate($client)) {
    //shell_exec('php run-scraper.php &');
    shell_exec('php run-scraper.php > /dev/null 2>&1 &');
}

if ($value === null) {
    //Resultado vacío.
    echo json_encode(array());
}
else {
    echo $value;
}