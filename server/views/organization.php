<!--Представление организации-->
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
<div id="content">
<? 
	//если пришла строка
	if(is_string($this->content))
		echo $this->content;
	//если пришел массив данных
	elseif(is_array($this->content))
	{
		//подключение файлов с функциями для выода соответствующих форм
		require_once('editForms.php');
		echo getMyOrganizations($this->content, $this->cities);
	}//для отображения формы добавления новой организации
	elseif(!empty($this->user_id) and !empty($this->cities))
	{
		//подключение файлов с функциями для выода соответствующих форм
		require_once('createForms.php');
		echo createOrganization($this->user_id, $this->cities);
	}
	//вывод информации о организации сотрудника
	elseif(isset($this->worker_org))
	{
		require_once('editForms.php');
		echo getWorkerOrg($this->worker_org);
		
	}
	//вывод тестов организации
	elseif(isset($this->orgtests))
	{
		require_once('editForms.php');	
		echo getOrgTests($this->orgtests);
	}
	//вывод результатов прохождения тестов сотрудником
	elseif(isset($this->workerresults))
	{
		require_once('editForms.php');	
		echo getWorkerResults($this->workerresults);
	}
	
?>
</div>
<!--Нижний колонтитул-->
<? include('blocks/footer.php'); ?>
</div></body></html>