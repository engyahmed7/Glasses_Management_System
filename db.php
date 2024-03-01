<?php

require_once "vendor/autoload.php";

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

try{
$capsule->addConnection([
    'driver'    => "mysql",
    'host'      => __host__,
    'database'  => __database__,
    'username'  => __username__,
    'password'  => __password__,
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();
}catch(Exception $e){
    die("Error ". $e->getMessage());
}




