<!--Вид для вывода информации о студентах-->
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
//вывод информации о студентах добавленых пользователем
if(isset($this->mystudents) and isset($this->cities))
{	
	require_once('editForms.php');
	echo getMyStudents($this->mystudents, $this->cities);
}
//вывод формы для добавления нового студента
elseif(isset($this->addstudent) and isset($this->cities))
{
	require_once('editForms.php');
	echo addNewStudent($this->addstudent, $this->cities);
}
//выводим все тесты для группы данного студента
elseif(isset($this->mygroup) and isset($this->grouptests))
{
	require_once('editForms.php');
	echo getStudentGroupTests($this->mygroup, $this->grouptests);
}
//выводим все тесты по предметам
elseif(isset($this->mylessons) and isset($this->lessonstests))
{
	require_once('editForms.php');
	echo getTestsByLessons($this->mylessons, $this->lessonstests);
}


?></div>
<!--Нижний колонтитул-->
<? include('blocks/footer.php'); ?>
</div></body></html>