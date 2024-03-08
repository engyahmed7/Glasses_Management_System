<?php
require_once './vendor/autoload.php';
// require "./vendor/autoload.php";

// if (!extension_loaded('soap')) {
//     exit('SOAP extension not loaded');
// }

// $client = new SoapClient('radio.xml');
// //to get all functions available in this wsdl file
// $functions = $client->__getFunctions();

// // foreach ($functions as $fun) {
// //     echo "$fun <br>";
// // }

// $countries = $client->getCountryList();
// // echo '<pre>';
// // print_r($countries);
// // echo '</pre>';

// foreach ($countries as $country) {
//     echo "$country->coid : $country->countryName : $country->countryCode <br/><br/>";
// }


$generator = new \PHP2WSDL\PHPClass2WSDL(App\Course::class, 'http://localhost:3000/soap_service.php');

$generator->generateWSDL();
$generator->save('course_wsdl.xml');
echo "WSDL file has been generated successfully";





