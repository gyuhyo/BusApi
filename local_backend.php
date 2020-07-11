<?php
    $curl = curl_init();

    $query = urlencode($_POST["query"]);

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://dapi.kakao.com/v2/local/search/address.json?query=".$query,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Authorization: KakaoAK b534944817b198f1e5faa8d55123893e"
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    echo $response;
?>