<!--Представление результатов прохождения созданных тестов-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<? //подключения функций для вывода результатов
	require_once('resultForms.php');
?>
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
<? 
include('blocks/header.php'); ?>
<!--Верхнее меню-->
<? include('blocks/top_menu.php'); ?>
<!--Боковое меню-->
<? include('blocks/left_menu.php'); ?>
<!--Вывод контента-->
<div id="content"><? 

//проверка пришёл ли массив, вывод результатов организационных тестов
if(is_array($this->content) and isset($this->test_name) and isset($this->worker_fio) and isset($this->org_name))
	echo getOrgResult($this->content, $this->test_name, $this->worker_fio, $this->org_name);
	
//вывод результата университетских и школьных тестов
elseif(isset($this->students) or isset($this->pupils))
	echo allResults($this->students, $this->pupils);
	
//выводим результат тестирования студента
elseif(isset($this->myresults))
	echo getStudentResults($this->myresults);
	
//выводим результа тестирования школьника
elseif(isset($this->mytestsresults))
	echo getPupilResults($this->mytestsresults);

else echo 'Ошибка при выборке результатов теста!';

?></div>
<!--Нижний колонтитул-->
<? include('blocks/footer.php'); ?>
</div></body></html>