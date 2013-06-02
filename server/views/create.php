<!--Вид создания теста-->
<? //подключение файла с функциями для выода форм
require_once('createForms.php');
?>
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
<script type="text/javascript" src="http://<? echo $_SERVER['HTTP_HOST'];?>/js/school.js"></script>
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
<? 
	//если пришла строка
	if(is_string($this->content))
		echo $this->content;
	else
	{
		//вывод формы для добавления вопросов
		if(!empty($_SESSION['test']) and $this->content==true)
		{
			echo getQuestionsForms($_SESSION['test']['test_question'], $_SESSION['test']['test_var']);
		}
		//добавление вопросов для организационного теста
		elseif(!empty($_SESSION['test']['test_questions']) and !isset($this->content))
		{
			echo getOrgQuestionsForms($_SESSION['test']['test_questions'], $_SESSION['test']['test_var']);
		}
		//добавление вопросов для университетского теста
		elseif(!empty($_SESSION['test']['univer']) and isset($_SESSION['test']['test_questions']) and isset($_SESSION['test']['test_var']))
		{
			echo getStudQuestionForms($_SESSION['test']['test_questions'], $_SESSION['test']['test_var']);	
		}
		//обратная связь
		elseif(isset($this->feedback))
		{
			echo feedBack();	
		}
		else
		{
			//вывод формы для создания тестов
			echo getForm($_SESSION['loginfo']['type_id']); 
		}
	}
	
?>
</div>
<!--Нижний колонтитул-->
<? include('blocks/footer.php'); ?>
</div></body></html>