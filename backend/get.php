<?php
/**
 * Created by PhpStorm.
 * User: rafa
 * Date: 3/14/17
 * Time: 11:58 PM
 */

$result = array();

require __DIR__ . '/vendor/autoload.php';

$client = new Predis\Client();

$value = $client->get('peix.offers');

if ($value === null) {
    echo json_encode(array());
}
else {
    echo $value;
}