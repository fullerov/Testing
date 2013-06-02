<? //подключение вида для генераци форм
	require_once('editForms.php');
?>
<!--Вид редактирования тестов-->
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
<script type="text/javascript" src="http://<? echo $_SERVER['HTTP_HOST'];?>/js/univer.js"></script>
<!--Подключение визуального редактора-->
<script type="text/javascript" src="http://<? echo $_SERVER['HTTP_HOST'];?>/js/wysiwyg.js"></script>
<!--Вывод названия страницы-->
<title><? echo $this->title; ?></title>
</head>

<body>
<div id="site">
<!--Верхний колонтитул-->
<? 
include('blocks/header.php'); ?>
<!--Верхнее меню-->
<? include('blocks/top_menu.php'); ?>
<!--Боковое меню-->
<? include('blocks/left_menu.php'); ?>
<!--Вывод контента-->
<div id="content">
<? 
//редактирование статьей пользователя
if(isset($this->comments) and isset($this->num))
{
	echo editComments($this->comments, $this->num);
}
elseif(isset($this->articles))
{
	echo getArticles($this->content);
}
//редактирование вопросов теста
elseif(isset($this->type))
{
	echo questionsForm($this->content, $this->type, $this->test_id);
}
//редактирование тестов
elseif(isset($this->usertests) or isset($this->orgtests) or isset($this->schooltests) or isset($this->univertests))
{
	echo getForm($_SESSION['loginfo']['type_id'], $this->usertests, $this->orgtests, $this->schooltests, $this->univertests);
}
//редактирование стран
elseif(isset($this->countries))
{
	echo countriesEditForm($this->countries);
}
//редактирование городов
elseif(isset($this->cities) and isset($this->country_id))
{
	echo citiesEditForm($this->cities, $this->country_id);
}
//редактирование пользовательских типов
elseif(isset($this->usertypes))
{
	echo userTypesEdit($this->usertypes);	
}
//редактирование пользовательей
elseif(isset($this->users))
{
	echo usersEdit($this->users);	
}

?></div>
<!--Нижний колонтитул-->
<? include('blocks/footer.php'); ?>
</div></body></html>