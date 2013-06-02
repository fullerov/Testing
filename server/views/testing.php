<!--Вид прохождения теста-->
<? //подключение файла с функциями для выода форм
require_once('executeForms.php');
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
<script type="text/javascript" src="http://<? echo $_SERVER['HTTP_HOST'];?>/js/timer.js"></script>
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
//вывод вопросов пользовательского теста
if(is_array($this->content))
{	

//функция обрабатывает массив и выводит форму для заполнения бланка тестирования
echo '<script type="text/javascript">setInterval(getTimer,60000);</script>
<center><span id="message">На выполнение теста <b><span id="timer">'.$this->timer.'</span></b> мин.</span></center><br>';
echo beginUserTest($this->content,$this->id);

}
//вывод вопросов университетского теста
elseif(isset($this->univertest) and isset($this->univer_id) and isset($this->timer) and isset($this->id))
{
//функция обрабатывает массив и выводит форму для заполнения бланка тестирования
echo '<script type="text/javascript">setInterval(getTimer,60000);</script>
<center><span id="message">На выполнение теста <b><span id="timer">'.$this->timer.'</span></b> мин.</span></center><br>';
echo beginUniverTest($this->univer_id, $this->univertest, $this->id);
}
//вывод вопросов организационного теста
elseif(isset($this->orgtest) and isset($this->org_id) and isset($this->timer) and isset($this->id))
{
//функция обрабатывает массив и выводит форму для заполнения бланка тестирования
echo '<script type="text/javascript">setInterval(getTimer,60000);</script>
<center><span id="message">На выполнение теста <b><span id="timer">'.$this->timer.'</span></b> мин.</span></center><br>';
echo beginOrgTest($this->org_id, $this->orgtest,$this->id);
}
//вывод вопросов школьного теста
elseif(isset($this->schooltest) and isset($this->school_id) and isset($this->timer) and isset($this->id))
{
//функция обрабатывает массив и выводит форму для заполнения бланка тестирования
echo '<script type="text/javascript">setInterval(getTimer,60000);</script>
<center><span id="message">На выполнение теста <b><span id="timer">'.$this->timer.'</span></b> мин.</span></center><br>';
echo beginSchoolTest($this->schooltest, $this->school_id, $this->class_id, $this->lesson, $this->id);
}
//если тест прошел зарегистрированный сотрудник
elseif(isset($this->check) and isset($this->count) and isset($this->answers) and isset($this->result) and isset($this->org_id) and isset($this->test_id))
{
	echo orgWorkerResult($this->check, $this->count, $this->answers, $this->result, $this->org_id, $this->test_id);
}
//передача результата прохождения организационного теста 
elseif(isset($this->count) and isset($this->answers) and isset($this->result) and isset($this->org_id) and isset($this->test_id) and isset($this->time))
{
	echo orgTestResult($this->count, $this->answers, $this->result, $this->org_id, $this->test_id, $this->time);
}
//если тест прошел зарегистрированный студент
elseif(isset($this->check) and isset($this->count) and isset($this->answers) and isset($this->result) and isset($this->univer_id) and isset($this->test_id) and isset($this->time))
{
	echo studentTestResult($this->count, $this->answers, $this->result, $this->univer_id, $this->test_id, $this->time, $this->check);
}
//передача результата прохождения университетского теста 
elseif(isset($this->count) and isset($this->answers) and isset($this->result) and isset($this->univer_id) and isset($this->test_id) and isset($this->time))
{
	echo univerTestResult($this->count, $this->answers, $this->result, $this->univer_id, $this->test_id, $this->time);
}
//вывод результата прохождения пользовательского теста
elseif(isset($this->r_answers) and isset($this->test_id) and isset($this->res) and isset($this->count) and isset($this->time))
{
	echo userTestResult($this->test_id, $this->r_answers, $this->res, $this->count, $this->time);
}
//если тест прошел зарегистрированный школьник
elseif(isset($this->check) and isset($this->count) and isset($this->answers) and isset($this->result) and isset($this->class_id) and isset($this->school_id) and isset($this->test_id) and isset($this->time) and isset($this->lesson))
{
	echo pupilTestResult($this->count, $this->answers, $this->result, $this->school_id, $this->class_id, $this->lesson, $this->test_id, $this->time, $this->check);
}
//передача результата прохождения школьного теста 
elseif(isset($this->count) and isset($this->answers) and isset($this->result) and isset($this->school_id) and isset($this->test_id) and isset($this->time))
{
	echo schoolTestResult($this->count, $this->answers, $this->result, $this->school_id, $this->class_id, $this->lesson, $this->test_id, $this->time);
}




?></div>
<!--Нижний колонтитул-->
<? include('blocks/footer.php'); ?>
</div></body></html>