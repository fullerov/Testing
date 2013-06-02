<!--Вид профиля пользователя-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--Вывод ключевых слов-->
<meta name="keywords" content="<? echo $this->keywords; ?>" />
<!--Вывод описания страницы-->
<meta name="description" content="<? echo $this->description; ?>" />
<!--Если выключен JS-->
<noscript><b style="color:red;">Включите поддержку Javascript в своем браузере!</b>
<script type="text/javascript" src="http://<? echo $_SERVER['HTTP_HOST'];?>/js/style.js"></script>
<link rel="stylesheet" href="http://<? echo $_SERVER['HTTP_HOST'];?>/css/style.css" />
</noscript>
<!--Подключение библиотеки JQuery-->
<script type="text/javascript" src="http://<? echo $_SERVER['HTTP_HOST'];?>/js/jquery.js"></script>
<!--Проверка браузера и подключение соответствующих стилей CSS и сценариев Javascript-->
<script type="text/javascript" src="http://<? echo $_SERVER['HTTP_HOST'];?>/js/checking.js"></script>
<!--Подключение клиентской логики на Javascript-->
<script type="text/javascript" src="http://<? echo $_SERVER['HTTP_HOST'];?>/js/client_actions.js"></script>
<script type="text/javascript" src="http://<? echo $_SERVER['HTTP_HOST'];?>/js/cities.js"></script>
<!--Подключение визуального редактора-->
<script type="text/javascript" src="http://<? echo $_SERVER['HTTP_HOST'];?>/js/wysiwyg.js"></script>
<!--Вывод названия страницы-->
<title><? echo $this->title; ?></title>
</head>

<body>
<div id="site">
<!--Верхний колонтитул-->
<? include('blocks/header.php'); ?>
<!--Верхнее меню-->
<? include('blocks/top_menu.php'); ?>
<!--Боковое меню-->
<? include('blocks/left_menu.php'); ?>
<!--Вывод контента-->
<div id="content">
<? //проверка типа переданного параметра вывод данных о пользователе
if(is_array($this->content) and isset($this->countries))
{
	printf('<h2 id="profile_login">%s</h2> 
	<h5>Тип регистрации %s</h5>
	<img title="%s" src="http://'.$_SERVER['HTTP_HOST'].'/%s">
	<form enctype="multipart/form-data" action="/authorization/profile" method="post"><br>

	<label for="pic">Загрузить новое изображение: </label><br>
	<input type="file" name="pic"/>
	<input type="hidden" name="MAX_FILE_SIZE" value="200000" /><br>
	<center>
	<label for="fio">Ваше Ф.И.О.:</label><br>
	<input type="text" name="fio" value="%s" size="90" maxlength="255"><br>
	
	<label for="date">Дата Вашего рождения:</label><br>
	<input type="date" name="date" value="%s" maxlength="100"><br>
	
	<label for="email">Ваш e-mail:</label><br>
	<input type="email" name="email" value="%s" size="70" maxlength="255"><br>
	
	<label for="tel">Ваш телефон:</label><br>
	<input type="tel" name="tel" value="%s" size="70" maxlength="255"><br>
	
	<label for="about">Немного о Вас:</label><br>
	<textarea cols="70" rows="15" name="about">%s</textarea><br>
	<p>Страна: <b>%s</b> город: <b>%s</b></p>
	<label for="country">Изменить страну Вашего проживания:</label><br>
	<select id="country" onchange="getCities()" name="country" >'.$this->countries.'</select>
	
	<br><label id="label_city" for="city">Город Вашего проживания:</label><br><select id="city" name="city"></select><br>
	
	<label for="address">Ваш адрес:</label><br>
	<input type="text" name="address" value="%s" size="70" maxlength="255"><br>
	
	<p>Дата регистрации: <b>%s</b></p>
	<input type="hidden" name="login" value="%s">
	<input type="submit" name="edit" value="Сохранить изменения">
	</center>
	</form><br><br>', $this->content['login'], Registration::getUserType($this->content['type_id']), $this->content['login'], $this->content['image'], $this->content['fio'], $this->content['birthdate'], $this->content['email'], $this->content['tel'], $this->content['about'], Registration::getUserCountry($this->content['country_id']), Registration::getUserCity($this->content['city_id']), $this->content['address'],$this->content['date'], $this->content['login']);
	
}
else{header('Location: ../');}
?>
</div>
<!--Нижний колонтитул-->
<? include('blocks/footer.php'); ?>
</div></body></html>