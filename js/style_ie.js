// JavaScript style
$(document).ready(function() {
//авторизация

	//изменение стиля блока бокового меню при нажатии кнопки "Авторизация" и отображение формы авторизации
	$("#auth").toggle(function(){ 
    $(this).css({"color":"#E5E5E5","font-style":"normal","background-color":"#F9F9F9"});
	$("#auth_block").css("visibility","visible");
	$("#left_menu").animate({"margin-top":"80px","margin-bottom":"10px"});
		},function(){	
	$(this).css({"color":"#FFF","font-style":"normal","background-color":"#E2E2E2"});
	$("#auth_block").css("visibility","hidden");
	$("#left_menu").animate({"margin-top":"-75px","margin-bottom":"-2px"});});
	
	//изменение стиля кнопки "Профиль" при нажатии и отображении ссылок
	$("#profile").toggle(function (){
	$(this).css({"color":"#E5E5E5","font-style":"normal","background-color":"#F9F9F9"});
	$("#profile_block").css({"visibility":"visible"});
	$("#left_menu").animate({"margin-top":"-20px","margin-bottom":"10px"});
		}, function(){	
	$(this).css({"color":"#FFF","font-style":"normal","background-color":"#E2E2E2"});
	$("#profile_block").css("visibility","hidden");
	$("#left_menu").animate({"margin-top":"-75px","margin-bottom":"-2px"});});
	
	$("#profile").toggle(function(){ 
    $(this).css({"color":"#E5E5E5","font-style":"normal","background-color":"#F9F9F9"});
		},function(){	
	$(this).css({"color":"#FFF","font-style":"normal","background-color":"#E2E2E2"});});
	
	var sad=document.URL;
	var arr=sad.split('/');
	
	$("#left_menu p a").before("<img src='http://"+arr[2]+"/css/img/menu_list_arrow.png'/>");
	$("#auth_button").hover(function() {
    $(this).css({"color":"#FFF", "background-color":"#E2E2E2", "font-style":"normal"});
    }, function(){
    $(this).css({"color":"#DDD", "background-color":"transparent", "font-style":"normal"});
		});
			
//конец функции ready			
});