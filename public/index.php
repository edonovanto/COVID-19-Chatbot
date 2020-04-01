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

$country = "indonesia";
$flop = "aslkaskdoks";


if('https://corona.lmao.ninja/countries/'.$country){

    $url = 'https://corona.lmao.ninja/countries/'. $country;
    $json = file_get_contents($url);
    echo $json;

    // $json = json_decode($json);

    // if ($json->message == "Country not found or doesn't have any cases"){
    //     echo 'Mohon maaf kami tidak mengerti pesan anda. Silakan kirim pesan "MULAI" untuk memulai kuis atau masukkan nama negara yang sesuai.';
    // }
    // else {
        // $result1 = $json->cases;
        // $result2 = $json->todayCases;
        // $result3 = $json->deaths;
        // $result4 = $json->recovered;
        // $result5 = $json->casesPerOneMillion;
    
        // echo $result1 ."\n". $result2 . "\n" . $result3 . "\n" . $result4 . "\n" . $result5;
    // }
}
else{
    echo 'Mohon maaf kami tidak mengerti pesan anda. Silakan kirim pesan "MULAI" untuk memulai kuis atau masukkan nama negara yang sesuai.';
}

// $app->run();
