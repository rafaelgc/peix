<?php
/**
 * Created by PhpStorm.
 * User: rafa
 * Date: 3/15/17
 * Time: 10:36 PM
 */

include_once 'scraper.php';

//Permite ejecutar manualmente el scraper siempre teniendo en cuenta
//que debe pasar un tiempo mínimo entre dos análisis.

$client = new Predis\Client();

if (needsUpdate($client)) {
    runScraper($client);
}