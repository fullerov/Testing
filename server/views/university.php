<!--Представление университетов-->
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
<div id="content"><?
//выводим данные о университетах добавленных пользователем
if(isset($this->myunivers) and isset($this->cities))
{
	require_once('editForms.php');
	echo getMyUniversities($this->myunivers, $this->cities);
}
//выводим форму для добавления нового университета
elseif(isset($this->user_id) and isset($this->cities))
{
	require_once('createForms.php');
	echo addNewUniver($this->user_id, $this->cities);
}
//выводим факультеты университета
elseif(isset($this->facult) and isset($this->univer) and isset($this->courses) and isset($this->specs))
{
	require_once('editForms.php');
	echo getFaculty($this->univer, $this->facult, $this->courses, $this->specs);
}
//выводим группы студентов
elseif(isset($this->groups) and isset($this->specs) and isset($this->courses) and isset($this->fac_id))
{	
	require_once('editForms.php');
	echo getGroups($this->fac_id, $this->groups, $this->specs, $this->courses);
}
//выводим предметы группы
elseif(isset($this->lessons) and isset($this->group_id))
{
	require_once('editForms.php');
	echo getLessons($this->lessons, $this->group_id);
}
//выводим ВУЗ студента
elseif(isset($this->myuniver) and isset($this->univertests))
{
	require_once('editForms.php');
	echo getStudentUniver($this->myuniver, $this->univertests);
}
 
 ?></div>
<!--Нижний колонтитул-->
<? include('blocks/footer.php'); ?>
</div></body></html>