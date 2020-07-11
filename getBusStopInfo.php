<?php

$curl = curl_init();

$busStopName = $_POST['busStopName'];
$busStopId = $_POST['busStopId'];

$service_key = "FbugHiMN%2FxdmiaslnFlDnJLJG89alW%2FHVkK1xgbLTODsjZzHtwiwrjKq433BgxZnScJwNWmiLP%2FX6%2FHDQT6eLA%3D%3D";
$url = "http://61.43.246.153/openapi-data/service/busanBIMS2/busStop?serviceKey=".$service_key."&pageNo=1&numOfRows=10&_type=json&bstopnm=".$busStopName."&arsno=".$busStopId;

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