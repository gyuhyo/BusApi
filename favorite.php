<?
    include "./dbconfig.php";
    session_start();

    $uid = $_SESSION["user_id"];

    if ($_POST["type"] == "add") {
        $sid = $_POST["station_id"];
        $sname = $_POST["station_name"];
        $x = $_POST["x"];
        $y = $_POST["y"];
        $sql = "INSERT INTO favorite (user_id, station_id, station_name, x, y) VALUES ('".$uid."', '".$sid."', '".$sname."', '".$x."', '".$y."')";
    } else {
        if ($_POST["type"] == "del") {
            $sid = $_POST["station_id"];
            $sql = "DELETE FROM favorite WHERE user_id = '" . $uid . "' AND station_id = '" . $sid . "'";
        } else {
            $sql = "SELECT * FROM favorite WHERE user_id = '".$uid."'";
        }
    }

    $query = mysqli_query($conn, $sql);
    $result = array();

    while($row = mysqli_fetch_array($query)) {
        array_push($result, array('station_id' =>$row['station_id']));
    }

    echo json_encode(array("result"=>$result), JSON_UNESCAPED_UNICODE);
?>