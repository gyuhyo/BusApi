<!doctype HTML>
<?
include "./dbconfig.php";
session_start();
?>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <meta name="theme-color" content="#1757bb">
        <link rel="stylesheet" type="text/css" href="./css/style2.css?v=202006011338" />
        <script src="https://kit.fontawesome.com/24c1a5db21.js"></script>
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
        <script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=45812c9522edefce68361e49c09b46fa&libraries=services,clusterer,drawing"></script>
        <script type="text/javascript" src="https://openapi.map.naver.com/openapi/v3/maps.js?ncpClientId=yt79e3i4mk"></script>
        <title>내 주변 대중교통 정보</title>
		<script src="./js/script2.js?v=2020052602"></script>
        <script type="text/javascript">
            var map;

            $(function(){
				load_map();

                $("#search_form").submit(function() {
                    $.post("./local.php", {query: $("#sch_addr").val()},
                        function (data) {
                        console.log(data);
                            const targetDiv = $(".local_list");
                            $(targetDiv).empty();
                            let dhtml = '<ul>';
                            $(data.documents).each(function(key, item){
                                let loc = item.road_address_name == '' ? '주소 정보 없음.' : item.road_address_name;
                                dhtml += '<li>';
                                dhtml += '  <b class="fs-15">' + item.place_name + '</b><br/>' + loc;
                                dhtml += '  <input type="hidden" name="longitude" value="' + item.x + '">';
                                dhtml += '  <input type="hidden" name="latitude" value="' + item.y + '">';
                                dhtml += '</li>';
                            });
                            dhtml += '</ul>';

                            $(targetDiv).append(dhtml);
                        }, "json");

                    return false;
                });

                $(document).on("click", ".local_list ul li", function(){
                    let lng = $(this).find("input:hidden[name='longitude']").val();
                    let lat = $(this).find("input:hidden[name='latitude']").val();
                    load_map(lat, lng);
                    $("#loading").css("display", "block");
                    if ($(".pages").css("display") == "block") {
                        $("body").scrollTop($(document).height());
                        $("#map").css("height", "400px");
                    }
                    getBusStop(lng, lat);

                    $(".local_list ul li").removeClass("local_list_hover");
                    $(this).addClass("local_list_hover");
                });

                $(document).on("click", ".p_category", function(){
                    let lng = $(this).find("input:hidden[name='busX']").val();
                    let lat = $(this).find("input:hidden[name='busY']").val();

                    var container = document.getElementById('map'); //지도를 담을 영역의 DOM 레퍼런스
                    var options = { //지도를 생성할 때 필요한 기본 옵션
                        center: new naver.maps.LatLng(lat, lng), //지도의 중심좌표.
                        zoom: 20 //지도의 레벨(확대, 축소 정도)
                    };

                    map = new naver.maps.Map(container, options); //지도 생성 및 객체 리턴

                    var markerPosition  = new naver.maps.LatLng(lat, lng);
                    var marker = new naver.maps.Marker({
                        position: markerPosition
                    });
                    marker.setMap(map);
                    $(".busList").empty();
                    $("#loading").css("display", "block");

                    /* 버스 정류장 정보 */
                    $.post("./getBusArrive.php", {busStopId: $(this).find("input:hidden[name='busStopId']").val()}, function(data){
                        const targetElementDiv = $(".busList");
                        let html = '';
                        $(data.response.body.items.item).each(function(key, row) {
                            let min1 = row.min1 != undefined ? row.min1 + '분 (' + row.station1 + '정거장 전)' : "도착정보 없음";
                            let min2 = row.min2 != undefined ? row.min2 + '분 (' + row.station2 + '정거장 전)' : "도착정보 없음";

                            html += '<div class="busItem">';
                            html += '    <p>버스 번호: ' + row.lineNo + '</p>';
                            html += '    <hr />';
                            html += '    <p>도착 예정: ' + min1 + '</p>';
                            html += '    <p>다음 도착 예정: ' + min2 + '</p>';
                            html += '</div>';
                        });
                        console.log(data);
                        $("#loading").css("display", "none");
                        $(targetElementDiv).append(html);
                    }, "json");
                });

                $("#login_span").click(function(){
                   $(".pop_cover").show();
                });

                $(".kakao_login").click(function(){
                   location.href='./login.php';
                });

                $("header h4").click(function(){
                    if ($("div.arrow_box").css("display") == "none") {
                        $("div.arrow_box").show();
                    } else {
                        $("div.arrow_box").hide();
                    }
                });

                $(".arrow_box ul li").click(function(){
                   location.href='./' + $(this).attr("location") + '.php';
                });

                $(document).on("click", "#favorites", function(e){
                    e.stopPropagation();
                    <? if (!isset($_SESSION['user_id'])) { ?>
                    alert("로그인 후 이용 가능합니다.");
                    return;
                    <? } else { ?>
                    if ($(this).hasClass("far")) {
                        console.log($(this).next().next().val());
                        console.log($(this).next().next().next().val());
                        $(this).removeClass("far");
                        $(this).addClass("fas");
                        $(this).css("color", "yellow");
                        $.post("./favorite.php", {
                            'type': 'add',
                            'station_id': $(this).next().val(),
                            'station_name': $(this).prev().text(),
                            'x': $(this).next().next().val(),
                            'y': $(this).next().next().next().val()
                        }, function(data){
                            alert("즐겨찾기 등록 완료");
                        });
                    } else {
                        $(this).addClass("far");
                        $(this).removeClass("fas");
                        $(this).css("color", "white");
                        $.post("./favorite.php", {'type': 'del', 'station_id': $(this).next().val()}, function(data){
                            alert("즐겨찾기 제거 완료");
                        });
                    }
                    <? } ?>
                });
            });

			function load_map(lat = 35.1883881213984, lng = 129.091933372677) {
				var container = document.getElementById('map'); //지도를 담을 영역의 DOM 레퍼런스
				var options = { //지도를 생성할 때 필요한 기본 옵션
					center: new naver.maps.LatLng(lat, lng), //지도의 중심좌표.
					zoom: 17 //지도의 레벨(확대, 축소 정도)
				};

				map = new naver.maps.Map(container, options); //지도 생성 및 객체 리턴

                var markerPosition  = new naver.maps.LatLng(lat, lng);
                var marker = new naver.maps.Marker({
                    position: markerPosition
                });
                marker.setMap(map);
			}
            function getBusStop(cX, cY){
                $(".busCategory").empty();
                let busStopList = '';
                let BusStationArr = [];
                // 즐겨찾기 목록 //
                let myFavorite = new Array();
                $.post("./favorite.php", {'type': 'get'}, function(data){
                    $(data.result).each(function(key, row) {
                        myFavorite.push(row.station_id);
                    });
                }, "json");
                $.post("./getBusStop.php", {x:cX, y:cY},
                    function (data) {
                    console.log(data);
                    for(i=0; i<$(data.result.lane).length; i++) {
                        if (BusStationArr.indexOf(data.result.lane[i].stationName) == -1) {
                            BusStationArr.push(data.result.lane[i].stationName);
                        }
                    }
                    console.log(BusStationArr);
                    for(i=0; i<BusStationArr.length; i++) {
                        $.post("./getBusStopInfo.php", {busStopName: encodeURI(BusStationArr[i])},
                            function(busStopData) {
                                let favorite;
                                $(busStopData.response.body.items.item).each(function(key, row){
                                    favorite = (myFavorite.indexOf((row.bstopId).toString()) > -1) ? '<i class="fas fa-star" id="favorites" style="color: yellow;"></i>' : '<i class="far fa-star" id="favorites"></i>';
                                    busStopList += '<p class="p_category">';
                                    busStopList += '<span name="busStopName">' + row.bstopNm + '</span> ' + favorite;
                                    busStopList += '<input type="hidden" name="busStopId" value="' + row.bstopId + '"/>';
                                    busStopList += '<input type="hidden" name="busX" value="' + row.gpsX + '"/>';
                                    busStopList += '<input type="hidden" name="busY" value="' + row.gpsY + '"/>';
                                    busStopList += '</p>';
                                    $(".busCategory").append(busStopList);
                                    busStopList = '';
                                });
                                $("#loading").css("display", "none");
                            }, "json");
                    };
                    console.log(busStopList);
                    }, "json");

            }
        </script>
    </head>
    <body>
        <header>
            <h3>부산시 버스 도착정보</h3>
            <h4>
                <? if(isset($_SESSION['user_id'])) { ?>
                <img src="<?=$_SESSION['user_pic'];?>" style="width:35px; height: 35px; border-radius: 3px; vertical-align: middle;" />
                    <span><?=$_SESSION['user_nick'];?>님</span>
                    <div class="arrow_box">
                        <ul>
                            <li location="index">홈으로</li>
                            <li location="favorite_list">즐겨찾기</li>
                            <li location="logout">로그아웃</li>
                        </ul>
                    </div>
                <? } else { ?>
                    <span id="login_span">로그인</span>
                <? } ?>
            </h4>
            <div class="cb"></div>
        </header>
        <div class="pages">
			<div class="search">
				<div id="search_bar">
					<form id="search_form" autocomplete="off">
						<input type="text" name="sch_addr" id="sch_addr" placeholder="주변 건물 이름">
						<button id="sch_btn"><i class="fas fa-search"></i></button>
					</form>
				</div>
                <div class="local_list">
                </div>
			</div>
			<div id="map"></div>
			<div class="items">
                <span class="notice">오차범위 약: 20M 이내</span><br />
                <div class="busCategory">
                </div>
                <img src="./img/loading.gif" id="loading" style="width: 50px; height: 50px;" hidden/>
                <div class="busList">
                </div>
			</div>
		</div>
    <div class="pop_cover">
        <div class="login_form">
            <h3>로그인</h3>
            <hr>
            <div class="kakao_login_form">
                <img src="./img/kakao_login_btn.png" class="kakao_login"/>
            </div>
        </div>
    </div>
    </body>
</html>