<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| First we need to get an application instance. This creates an instance
| of the application / container and bootstraps the application so it
| is ready to receive HTTP / Console requests from the environment.
|
*/

$app = require __DIR__.'/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$url = "https://corona.lmao.ninja/countries/indonesia";
$json = file_get_contents($url);
$json = json_decode($json);
$result = $json->cases;
$result2 = $json->todayCases;
echo "Total Kasus: \n" . $result ;
echo "Kasus hari ini: \n" . $result2;

$app->run();
