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

$curl = curl_init();

curl_setopt_array($curl, array(
	CURLOPT_URL => "https://api.smartable.ai/coronavirus/news/US/",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => array(
		"Subscription-Key: 3009d4ccc29e4808af1ccc25c69b4d5d"
	),
));

$response = curl_exec($curl);

curl_close($curl);

$result = json_decode($response, true);
// var_dump($result);

$number = mt_rand(1,29);

$all = $result['news'][$number];
var_dump($all);

// $title = $result['news'][0]['title'];
// var_dump($title);

// $image = $result['news'][0]["images"][0]['url'];
// var_dump($desc);

// $weburl = $result['news'][0]["webUrl"];
// var_dump($weburl);


// $app->run();
