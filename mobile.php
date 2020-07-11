<!doctype HTML>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <meta name="theme-color" content="#1757bb">
        <link rel="stylesheet" type="text/css" href="./css/style.css?v=202006011338" />
        <script src="https://kit.fontawesome.com/24c1a5db21.js"></script>
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
        <script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=45812c9522edefce68361e49c09b46fa"></script>
        <script src="./js/script.js?v=2020052602"></script>
        <title>내 주변 대중교통 정보</title>
        <script type="text/javascript">
            $(function(){

            });

            function getBusStop(cX, cY){
                let itemHTML = '';
                $.post("./getBusStop.php", {x:cX, y:cY},
                    function (data) {
                    console.log(data);
                    for(i=0; i<$(data.result.lane).length; i++){
                        for(j=0; j<$(data.result.lane[i].busList).length; j++) {

                            /* 버스 정류장 정보 */
                            $.post("./getBusStopInfo.php", {busStopName: data.result.lane[i].stationName},
                            function(busStopInfoData){
                                console.log(busStopInfoData);
                            }, "xml");

                            itemHTML += '<div class="item">';
                            itemHTML += '    <p>버스 정류장: ' + data.result.lane[i].stationName + '(' + data.result.lane[i].stationID + ')</p>';
                            itemHTML += '    <p>버스 번호: ' + data.result.lane[i].busList[j].busNo + '</p>';
                            itemHTML += '    <hr>';
                            itemHTML += '    <p>도착까지 남은 시간: <b>00</b>분</p>';
                            itemHTML += '    <p>도착까지 남은 정류장: <b>0</b>정류장</p>';
                            itemHTML += '</div>';
                        }
                    }

                    $("#list_items").append(itemHTML);
                    }, "json");
            }
        </script>
    </head>
    <body>
        <div id="header">
            <h3 id="site_title">내 주변 대중교통 정보</h3>
            <h4 id="maker">박규효</h4>
            <div class="cb"></div>
        </div>
        <div id="search_bar">
            <form id="search_form">
                <input type="text" name="sch_addr" id="pop_open" placeholder="주변 건물 이름" readonly>
                <button id="sch_btn"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <div id="content">
            <ul>
                <li class="off on">버스 도착 정보</li>
                <li class="off">지하철 도착 정보</li>
            </ul>
            <div class="cb"></div>
            <div id="result_part">
                <div id="list_items">
                </div>
            </div>
        </div>
        <div id="footer">
            <div id="hidden_earth"></div>
        </div>
    </body>
</html>