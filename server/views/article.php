<!--Вид статьи-->
<? //подключение скрипта с формами для вывода данных 
require_once('editForms.php');?>
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
//выводим форму для создания статьи
if($this->content=='addnew')
{
	echo addArticle();
}
//проверка типа переданного параметра и формирование таблицы со статьями
elseif(is_array($this->content) and !empty($this->id))
{
printf('<h1>%s</h1><img title="%s" src="%s"><p>%s</p><span id="art_about_left">Добавил <a href="mailto:%s">%s</a> %s</span><span id="art_about_right">Просмотров: %s<br>Оценка %s голосов %s</span>',$this->content['title'],$this->content['title'],$this->content['img'],$this->content['text'],Registration::getUserEmail($this->content['user_id']),Registration::getUserLogin($this->content['user_id']),$this->content['date'],$this->content['count'],$this->content['rating'],$this->content['votes']);
echo setArticleVote($this->id);
echo showComments($this->content['id'], $this->comments, $this->cmnts);
}
else{echo $this->content; }
?>
</div>
<!--Нижний колонтитул-->
<? include('blocks/footer.php'); ?>
</div></body></html>