<?php
$x = $_POST['x'];
$y = $_POST['y'];

$curl = curl_init();
$url = "https://api.odsay.com/v1/api/pointBusStation?apiKey=6jKgSU48pGu3AYkXRTEKq1pLW99Labj2xKrZxISEK9E&x=".$x."&y=".$y."&radius=200";
curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;

?>