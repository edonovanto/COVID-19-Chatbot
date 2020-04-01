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

$country = "indonsia";
$flop = "aslkaskdoks";

    $url = 'https://corona.lmao.ninja/countries/'. $country;

    $result = "";

    
    if ($url){
        $json = file_get_contents($url);

        $json = json_decode($json);

        $result1 = $json->cases;
            $result2 = $json->todayCases;
            $result3 = $json->deaths;
            $result4 = $json->recovered;
            $result5 = $json->casesPerOneMillion;
        
            $result .= $result1 ."\n". $result2 . "\n" . $result3 . "\n" . $result4 . "\n" . $result5;
    }else {
        echo "tidak oke";
    }


            echo $result;

// $app->run();
