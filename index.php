<?php

if (!extension_loaded('soap')) {
    exit('SOAP extension not loaded');
}

$client = new SoapClient('radio.xml');
//to get all functions available in this wsdl file
$functions = $client->__getFunctions();

// foreach ($functions as $fun) {
//     echo "$fun <br>";
// }

$countries = $client->getCountryList();
// echo '<pre>';
// print_r($countries);
// echo '</pre>';

foreach ($countries as $country) {
    echo "$country->coid : $country->countryName : $country->countryCode <br/><br/>";
}




