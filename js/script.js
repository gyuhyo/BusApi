$(function(){
   $("#pop_open").click(function(){
        window.open("./select_local.php", "blank", "width=400, height=700");
    });

   $("#content ul li").click(function(){
       $("#content ul li").removeClass("on");
       $(this).addClass("on");
   });

   $("#search_pop #bottom_btn input[type=button]").click(function(){
       $("#search_pop").fadeOut(300);
   });
});