<?php


require '../vendor/autoload.php';
require '../src/Manager/DbManager.php';



// Customer Routes
require '../src/routes/routesUtente.php';
require '../src/routes/routesOfferta.php';

$app->run();