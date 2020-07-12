<?
    $code = isset($_GET['code']) ? $_GET['code'] : '';
    $token = '';

    if ($code != '') {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://kauth.kakao.com/oauth/token?grant_type=authorization_code&client_id=b534944817b198f1e5faa8d55123893e&redirect_uri=http://qkrrbgy.dothome.co.kr/oauth.php&code=".$code,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
        ));

        $response = curl_exec($curl);
        $response = json_decode($response);

        curl_close($curl);

        $token = $response->access_token;
    }

    if ($token != '') {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://kapi.kakao.com/v2/user/me",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer ".$token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);

        include "dbconfig.php";

        $sql = "SELECT * FROM member WHERE mb_id = '".$response->id."'";
        $query = mysqli_query($conn, $sql);

        $row = mysqli_fetch_array($query);
        $cnt = @mysqli_num_rows($query);

        $id = $response->id;
        $nick = $response->kakao_account->profile->nickname;
        $email = $response->kakao_account->email;
        $pic = $response->kakao_account->profile->profile_image_url;

        if (!$cnt) {
            $sql = "INSERT INTO member (mb_id, mb_nick, mb_email, mb_pic) VALUES ('".$id."', '".$nick."', '".$email."', '".$pic."')";
            mysqli_query($conn, $sql);
        } else {
            $sql = "UPDATE member SET mb_nick = '".$nick."', mb_email = '".$email."', mb_pic = '".$pic."' WHERE mb_id = '".$id."'";
            mysqli_query($conn, $sql);
        }

        session_start();

        $_SESSION['user_id'] = $id;
        $_SESSION['user_nick'] = $nick;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_pic'] = $pic;

        header("Location: index.php");
    }
?>