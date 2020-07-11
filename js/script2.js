$(function(){
   $("#sch_addr").on({
		"click": function(){
			$("#search_bar").css("background", "#fff");
			$("#search_bar button").css({"background": "#fff", "color": "#1757bb"});
	   		$("#sch_addr").css({"background": "#fff", "color": "#1757bb"});
			$("#search_form").css("border", "1px solid #1757bb");
		},
		"blur": function(e){
			$("#search_bar").css("background", "#1757bb");
			$("#search_bar button").css({"background": "#1757bb", "color": "#fff"});
	   		$("#sch_addr").css({"background": "#1757bb", "color": "#fff"});
			$("#search_form").css("border", "1px solid #fff");
		}
   });
});