<!--Представление школ-->
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
<div id="content"><?
//выводим форму для добавления школы
if(isset($this->user_id) and isset($this->cities))
{
	require_once('createForms.php');
	echo addSchool($this->cities, $this->user_id);
}
//выводим данные о школах добавленных пользователем
elseif(isset($this->myschools) and isset($this->cities))
{
	require_once('editForms.php');
	echo getMySchools($this->myschools, $this->cities);
}
//выводим ошибку
elseif(is_string($this->content))
{
	echo $this->content;
}
//выводим классы школы
elseif(isset($this->classes) and isset($this->school_id))
{	
	require_once('editForms.php');
	echo getClasses($this->classes, $this->school_id);	
}
//выводим все предметы для класса
elseif(isset($this->lessons) and isset($this->class_id))
{
	require_once('editForms.php');
	echo getSchoolLessons($this->lessons, $this->class_id);
}
//выводим все тесты и информацию о школе ученика
elseif(isset($this->myschool) and isset($this->schooltests))
{
	require_once('editForms.php');
	echo getPupilSchool($this->myschool, $this->schooltests);
}

 
 ?></div>
<!--Нижний колонтитул-->
<? include('blocks/footer.php'); ?>
</div></body></html>