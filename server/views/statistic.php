<!--Представление статистики-->
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

//разбираем пришедший массив со статистическими данными
if(is_array($this->stat))
{
	$res='<h4>'.$this->content.'</h4><br><h3>C т а т и с т и к а :</h3>
	<img src="http://'.$_SERVER['HTTP_HOST'].'/images/cities.gif">
	<h5>Всего городов '.$this->stat['count'][0]['cities'].', в '.$this->stat['count'][0]['countries'].' странах</h5>';
	
	//вывод статистки 
	$res.='<center><h4><a title="Список статей..." href="/articles">Статьи:</a></h4><br><br>
	<img src="http://'.$_SERVER['HTTP_HOST'].'/images/articles.gif">
	<br><br>';
	$res.='Добавлено статей: <b>'.$this->stat['count'][0]['articles'].'</b><br>';
	$res.='Комментариев к статьям: <b>'.$this->stat['count'][0]['comments'].'</b><br>';
	$res.='Средняя оценка статей: <b>'.((int)$this->stat['results'][0]['artrating']).'</b><br><br><hr>';
	
	$res.='<h4><a title="Список пользователей..." href="/users">Пользователи:</a></h4><br><br>
	<img src="http://'.$_SERVER['HTTP_HOST'].'/images/users.gif">
	<br><br>';
	$res.='Пользователей: <b>'.$this->stat['count'][0]['users'].'</b><br>';
	$res.='Пользовательских тестов: <b>'.$this->stat['count'][0]['usertests'].'</b><br>';
	$res.='В среднем пользователи дали верный ответ на <b>'.((int)$this->stat['results'][0]['userresults']).'%</b> вопросов<br><br><br><hr>';
	
	$res.='<h4><a title="Список организаций..." href="/organizations">Организации:</a></h4><br><br>
	<img src="http://'.$_SERVER['HTTP_HOST'].'/images/orgs.gif">
	<br><br>';
	$res.='Организаций: <b>'.$this->stat['count'][0]['orgs'].'</b><br>';
	$res.='Организационных тестов: <b>'.$this->stat['count'][0]['orgtests'].'</b><br>';
	$res.='Сотрудников организаций: <b>'.$this->stat['count'][0]['employers'].'</b><br>';
	$res.='В среднем сотрудники дали верный ответ на <b>'.((int)$this->stat['results'][0]['orgresults']).'%</b> вопросов<br><br><br><hr>';
	
	$res.='<h4><a title="Список ВУЗ`ов..." href="/universities">ВУЗ`ы:</a></h4><br><br>
	<img src="http://'.$_SERVER['HTTP_HOST'].'/images/univers.gif">
	<br><br>';
	$res.='ВУЗ`ов: <b>'.$this->stat['count'][0]['univers'].'</b><br>';
	$res.='Университетских тестов: <b>'.$this->stat['count'][0]['univertests'].'</b><br>';
	$res.='Студентов: <b>'.$this->stat['count'][0]['students'].'</b><br>';
	$res.='В среднем студенты дали верный ответ на <b>'.((int)$this->stat['results'][0]['univerresults']).'%</b> вопросов<br><br><br><hr>';
	
	$res.='<h4><a title="Список школ..." href="/schools">Школы:</a></h4><br><br>
	<img src="http://'.$_SERVER['HTTP_HOST'].'/images/schools.gif">
	<br><br>';
	$res.='Школ: <b>'.$this->stat['count'][0]['schools'].'</b><br>';
	$res.='Школьных тестов: <b>'.$this->stat['count'][0]['schooltests'].'</b><br>';
	$res.='Школьников: <b>'.$this->stat['count'][0]['pupils'].'</b><br>';
	$res.='В среднем школьники дали верный ответ на <b>'.((int)$this->stat['results'][0]['schoolresults']).'%</b> вопросов</center><br><br>';
	
	echo $res;
	
}

?></div>
<!--Нижний колонтитул-->
<? include('blocks/footer.php'); ?>
</div></body></html>