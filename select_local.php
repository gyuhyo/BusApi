<!doctype HTML>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta name="theme-color" content="#1757bb">
    <link rel="stylesheet" type="text/css" href="./css/search.css?v=202006011338" />
    <script src="https://kit.fontawesome.com/24c1a5db21.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
    <script src="./js/script.js?v=2020052602"></script>
    <title>내 주변 대중교통 정보</title>
    <script type="text/javascript">
        $(function(){
            $("#search_form").submit(function() {
                $.post("./local_backend.php", {query: $("#sch_addr").val()},
                    function (data) {
                        const targetDiv = $("#content");
                        let dhtml = '<ul>';
                        $(data.documents).each(function(key, item){
                            dhtml += '<li>';
                            dhtml += '  ' + item.address_name;
                            dhtml += '  <input type="hidden" name="longitude" value="' + item.x + '">';
                            dhtml += '  <input type="hidden" name="latitude" value="' + item.y + '">';
                            dhtml += '</li>';
                        });
                        dhtml += '</ul>';

                        $(targetDiv).append(dhtml);
                    }, "json");

                return false;
            });

            $(document).on("click", "#content ul li", function(){
                var lat = $(this).find("input[name='latitude']").val();
                var lon = $(this).find("input[name='longitude']").val();

                $(opener.document).find("#pop_open").val(lat + ',' + lon);
                opener.parent.getBusStop(lon, lat);
                self.close();
            });

        });
    </script>
</head>
<body>
    <div id="header">
        <!-- 상단 로고 -->
        <h3 id="site_title">주소 검색</h3>
        <div class="cb"></div>
    </div>
    <!-- 검색바 -->
    <div id="search_bar">
        <form id="search_form" class="fl" method="post">
            <input type="text" name="sch_addr" id="sch_addr" placeholder="주변 건물 이름">
            <button id="sch_btn"><i class="fas fa-search"></i></button>
        </form>
        <div id="icon_earth" class="fr">
            <button id="earth_btn"><i class="fas fa-globe"></i></button>
        </div>
        <div class="cb"></div>
    </div>
    <div id="content">

    </div>
</body>
</html>