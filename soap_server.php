<?php

require './vendor/autoload.php';

try{
    $server=new SoapServer("course_wsdl.xml");
    $server->setClass(App\Course::class);
    $server->handle();
}catch(SoapFault $exc){
    echo $exc->getTraceAsString();
}

