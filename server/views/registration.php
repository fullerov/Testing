<!--Вид регистрации пользователя-->
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
<? echo $this->content; ?>
<!--Форма регистрации-->
<span id="reg_form"><p>Заполните все нижеследующие поля и нажмите на кнопку "Регистрация":</p>
<table>
<tr ><td ><form enctype="multipart/form-data" action="/registration/register" method="post" ></td></tr>
<tr><td><label for="fio">Введите Ваше Ф.И.О.:</label></td></tr>
<tr ><td ><input type="text" maxlength="255" size="50" name="fio" /></td></tr>
<tr><td ><label for="login">Введите Ваш логин:</label></td></tr>
<tr><td ><input type="text" maxlength="24" size="50" name="login" /></td></tr>
<tr><td ><label for="password">Введите Ваш пароль:</label></td></tr>
<tr><td ><input type="password" maxlength="255" size="50" name="password" /></td></tr>
<tr><td ><label for="email">Введите Ваш e-mail:</label></td></tr>
<tr><td ><input type="email" maxlength="255" size="50" name="email" /></td></tr>
<tr><td ><label for="tel">Введите Ваш телефон:</label></td></tr>
<tr><td ><input type="tel" maxlength="255" size="50" name="tel" /></td></tr>
<tr><td ><label for="address">Введите Ваш адрес:</label></td></tr>
<tr><td ><input type="text" maxlength="255" size="50" name="address" /></td></tr>
<tr><td ><label for="about">Расскажите о себе:</label></td></tr>
<tr><td ><textarea name="about" cols="39" rows="7"></textarea></td></tr>
<tr><td><label for="country">Выберите страну Вашего проживания:</label></td></tr>
<tr><td align="center"><select id="country" onchange="getCities()" name="country" ><? echo $this->countries; ?>
</select>
<br><label id="label_city" for="city">Выберите город Вашего проживания:</label><br><select id="city" name="city"></select><br>

</td></tr>
<tr><td><label for="birthdate">Введите дату Вашего рождения:</label></td></tr>
<tr><td ><center><input type="date" maxlength="255" size="50" name="birthdate" /></center></td></tr>
<tr><td ><label for="type">Вы регистрируетесь как:</label></td></tr>
<tr><td align="center"><select name="type">
<? echo $this->types;?>
</select></td></tr>
<tr><td><label for="pic">Загрузите Вашу фотографию:</label></label></td></tr>
<tr><td align="center"><input type="file" name="pic"/>
<input type="hidden" name="MAX_FILE_SIZE" value="200000" />
</td></tr>
<tr><td align="center"><label for="captcha">Введите цифры с картинки:</label><br /><img src="server/views/captcha.php" /></td></tr>
<tr><td align="center"><input type="text" maxlength="10" size="7" name="captcha" /></td></tr>
<tr><td align="center"><br /><input type="submit" name="submit" value="Регистрация"</td></tr>
</form>
</table>
</span>
</div>
<!--Нижний колонтитул-->
<? include('blocks/footer.php'); ?>
</div></body></html>