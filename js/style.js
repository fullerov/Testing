// JavaScript style
$(document).ready(function() {
//авторизация

	//изменение стиля кнопки "Авторизация" при нажатии и отображение формы авторизации
	$("#auth").toggle(function(){ 
    $(this).css({"color":"#E5E5E5","font-style":"normal","background-color":"#F9F9F9"});
	$("#auth_block").css({"visibility":"visible"}).animate({"height":"115px"},800);
		},function(){	
	$(this).css({"color":"#FFF","font-style":"normal","background-color":"#E2E2E2"});
	$("#auth_block").css({"visibility":"hidden"}).animate({"height":"0px"},300);
			});
			
		//изменение стиля кнопки "Профиль" при нажатии и отображении ссылок
	$("#profile").toggle(function(){ 
    $(this).css({"color":"#E5E5E5","font-style":"normal","background-color":"#F9F9F9"});
	$("#profile_block").css({"visibility":"visible"}).animate({"height":"90px"},700);
		},function(){	
	$(this).css({"color":"#FFF","font-style":"normal","background-color":"#E2E2E2"});
	$("#profile_block").css({"visibility":"hidden"}).animate({"height":"0px"},200);
			});
			

			
//конец функции ready			
});