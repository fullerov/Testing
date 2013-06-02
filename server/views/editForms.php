<?
//функция вывода форм редактирования созданных тестов по типу пользователя
function getForm($type,$usertests, $orgtests, $schooltests, $univertests)
{
	switch($type)
	{
		case '1': return userForm($usertests); break; //пользователь 
		case '2': return userForm($usertests); break; //студент 
		case '3': return userForm($usertests); break; //школьник 
		case '4': return teacherForm($usertests, $schooltests, $univertests); break; //учитель -
		case '5': return userForm($usertests); break; //рабочий 
		case '6': return chiefForm($usertests, $orgtests); break; //начальник 
		default: return 'Некорректный тип пользователя!'; break;
	}
	
}

//функция генерирует форму с тестами созданными пользователем
function userForm($usertests)
{
	return usetTests($usertests);
}

//функция выводит вопросы теста для редактирования, удаления и добавления
function questionsForm(array $content, $type, $test_id)
{

	$result='Всего вопросов в тесте: <b>'.count($content).'</b><center>';
	$num=1;
	
	foreach($content as $question)
	{
		$result.='<form action="/authorization/editquestion" method="post">
		<label for="question">Вопрос № '.$num.':</label><br>
		<textarea name="question" cols="60" id="qstnnum'.$num.'" rows="12">'.$question['question'].'</textarea><script language="JavaScript">
  generate_wysiwyg("qstnnum'.$num.'");
</script><br>
		<label for="answer">Ответ на вопрос:</label><br>
		<input type="text" name="answer" maxlength="255" size="50" value="'.$question['answer'].'"><br>';
		
		if($question['var1']!=NULL)
		{
			$result.='<label for="var1">Варианты ответа:</label><br>';
			//вывод вариантов ответа на вопрос
			for($i=1;$i<=30;$i++)
			{
				if($question['var'.$i]!=NULL)
				{
					$answ_count=$i;
					$result.=$i.') <input type="text" name="var'.$i.'" value="'.$question['var'.$i].'"><br>';	
				}
			}
		}
		else 
			$answ_count=0;
		
		$num++;
		
		$result.='<br>
		<input type="hidden" name="test_type" value="'.$type.'">
		<input type="hidden" name="test_id" value="'.$test_id.'">
		<input type="hidden" name="answer_count" value="'.$answ_count.'">
		<input type="hidden" name="question_id" value="'.$question['id'].'">
		<input type="submit" name="question_save" value="Сохранить изменения"><br>
		<input type="submit" name="question_delete" value="Удалить вопрос"><br><br><br><hr>
		
		</form>
		';
	}
	
	return $result.'<br><form method="post" action="">
	<p><b>Добавление нового вопроса</b></p><p id="add_about"><i>Введите количество вариантов ответа:</i></p>
	<input type="text" id="var_new" name="vars" value="3" size="3" maxlength="2">
	<input type="button" id="add_new" name="add_question" value="Добавить вопрос"><br></form></center>';
	
}

//функция возвращает формы для редактирования статьей пользователя
function getArticles($articles)
{
	//проверка пришедших данных, ожидается ассоциативный массив
	if(is_array($articles))
	{
		$num=1;
		foreach($articles as $article)
		{
			$string.='<h1>Статья № '.$num.'</h1><center><form action="/articles/editarticle" method="post"><label for="title">Заголовок статьи:</label><br>
			<input type="text" size=80 maxlength="255" name="title" value="'.$article['title'].'"><br>
			<label for="description">Краткое описание статьи:</label><br>
			<textarea name="description" cols="70" rows="3">'.$article['meta_description'].'</textarea><br>
			
			<label for="text">Полный текст статьи:</label><br>
			<textarea name="text" cols="80" id="edterticle'.$num.'" rows="45">'.$article['text'].'</textarea><script language="JavaScript">
  generate_wysiwyg("edterticle'.$num.'");
</script><br>
			<label for="keywords">Ключевые слова:</label><br>
			<input type="text" size="104" maxlength="255" name="keywords" value="'.$article['meta_key'].'"><br>
			<label for="image">Путь к адресу заглавного изображения к статье:</label><br>
			<input type="text" size="104" maxlength="255" name="image" value="'.$article['img'].'"><br>
			<a href="/articles/editcomments/article/'.$article['id'].'">&nbsp;Коментарии к статье&nbsp;</a>
			<h5 id="h5_article">Дата добавления: '.$article['date'].'<br>Всего просмотров: '.$article['count'].'<br>
			Голосов: '.$article['votes'].'<br>Оценка: '.$article['rating'].'
			</h5><input type="hidden" name="article_id" value="'.$article['id'].'">
			<span id="article_buttons"><input type="submit" name="edit" value="Сохранить изменения"><br>
			<input type="submit" name="delete" value="Удалить статью"></center></form></span>
			
			<hr>
			
			';
			$num++;
		}
		
		return $string;
	}
	else
	{
		return '<p>Вы еще не создали ни одной статьи!</p><span id="add_test_error"><a href="/articles/addarticle"><< К форме создания статей</a></span>';
	}
}


//функция для вывода формы добавления статьи
function addArticle()
{
	return '<p>Заполните все нижеслудующие поля для создания новой статьи и нажмите на кнопку "Добавить":</p>
	<form action="/articles/addarticle" method="post">
	<label for="title">Заголовок статьи:</label><br>
	<input type="text" name="title" size="100" maxlength="255" value=""><br>
	<label for="description">Введите краткое описание статьи:</label><br>
	<textarea name="description" cols="76" rows="3"></textarea><br>
	<label for="keywords">Введите ключевые слова через запятую:</label><br>
	<input type="text" name="keywords" size="100" maxlength="255" value=""><br>
	<label for="text">Введите полный текст статьи:</label><br>
	<textarea id="addarticletxt" name="text" cols="77" rows="50"></textarea><script language="JavaScript">
  generate_wysiwyg("addarticletxt");
</script><br>
	<label for="image">Введите адрес к заглавной картинке:</label><br>
	<input type="text" name="image" size="100" maxlength="255" value="http://'.$_SERVER['HTTP_HOST'].'/css/img/no_img.gif"><br>
	<span id="add_button"><input type="submit" name="add" value="Добавить"></span>
	</form>';
}

//функция для вывода и добавление комментариев к статье
function showComments($article_id, $comments, $cmnts)
{
	$login='Гость';
	
	if(!empty($_SESSION['loginfo']['login']))
		$login=$_SESSION['loginfo']['login'];
	
	$addcomment='<center><form action="/articles/addcomment" method="post">
	<p>Добавить новый комментарий:</p>
	<label for="comment_txt">Введите текст комментария:</label><br>
	<textarea name="comment_txt" cols="60" rows="4"></textarea><br>
	<label for="name">Введите Ваше имя:</label><br>
	<input type="text" name="name" size="60" maxlength="55" value="'.$login.'"><br>
	<label for="captcha">Введите цифры с картинки:</label><br>
	<img src="http://'.$_SERVER['HTTP_HOST'].'/server/views/captcha.php"/><br>
	<input type="text" maxlength="50" size="30" name="captcha" value=""><br>
	<input type="hidden" name="article_id" value="'.$article_id.'">
	<input type="submit" id="addcommentbutton" name="addcomment" value="Добавить комментарий">
	</form></center>';
	
	if(is_array($cmnts))
	{
	
	if(count($cmnts)==0)
		return '<span id="comments"><h4>Комментариев ещё нет!</h4><br></span><br>'.$addcomment;
	
	$content='<span id="comments"><h4>Комментарии:</h4><br>';
	
		foreach($cmnts as $comment)
		{
			if(!empty($comment['text']))
			{
				$content.='<p><b>'.$comment['author'].'</b><i> добавил комментарий: '.$comment['date'].'</i></p>
				<p>'.$comment['text'].'</p><hr>';
			}
		
		}
	
		if(!isset($_POST['addcomment']))
	   		return  $content.$addcomment;
		else
			return  $content;
	

	}
	else return '<span id="comments"><h4>'.$comments.'</h4></span>'.$addcomment;
	
	
	if(is_string($comments) and isset($_POST['addcomment']))
	{
		return $content.'<span id="comments"><h4>'.$comments.'</h4><br></span><br><br><br><br>';
		
	}
	

}

//функция редактирования комментариев к стаьте
function editComments($comments, $num)
{
	if(count($comments)==0)
	{
		return '<h4>К статье №'.$num.' еще не добавлено комментариев!</h4><br><span id="add_test_error"><a href="/articles/myarticles"><< Вернутся назад к моим статьям</a></span>';
	}
	else
	{
		if(is_array($comments))
		{
		$content='<h4>Всего '.count($comments).' комментарий(-ев) к статье № '.$num.':</h4><br>';
		$i=1;
		foreach($comments as $comment)
		{
			if(!empty($comment['text']))
			{
				$content.='<form action="" method="post">
				<h5>Комментарий № '.$i.'</h5>
				<p><b>'.$comment['author'].'</b><i> добавил комментарий: '.$comment['date'].'</i></p>
				<textarea cols="70" rows="5" name="comment_txt">'.$comment['text'].'</textarea><br>
				<input type="hidden" name="comment_id" value="'.$comment['id'].'">
				<input type="hidden" name="article_id" value="'.$num.'">
				<input type="submit" name="delete" value="Удалить комментарий">
				<input type="submit" name="edit" value="Сохранить изменения"><hr>				
				</form>';
			}
			$i++;
		
		}
	
	   return  $content.'<br><span id="add_test_error"><a href="/articles/myarticles"><< Вернутся назад к моим статьям</a></span>';
		}
			else return $comments.'<br><span id="add_test_error"><a href="/articles/myarticles"><< Вернутся назад к моим статьям</a></span>';
	}
}

function getCities($id, array $cities)
{
	foreach($cities as $city)
	{
		if($city['id']==$id)
		 	$result.='<option selected="selected" value="'.$city['id'].'">'.$city['name'].'</option>';
		else
			$result.='<option value="'.$city['id'].'">'.$city['name'].'</option>';
	}
	
	return $result;
}

//функция выводит организации пользователя для редактирования!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
function getMyOrganizations(array $data, $cities)
{
	$content='Всего организаций добавленных Вами: <b>'.count($data).'</b><br><br>';

	if(count($data)!=0)
	{
		$num=1;

		foreach($data as $org)
		{
		$content.='<form action="/organizations/my" method="post">
		<h5>Организация № '.$num.'</h5>
		<a href="/organizations/orgworkers/id/'.$org['id'].'">>> Сотрудники организации</a><br><br>
		<label for="name">Название организации:</label><br>
		<input type="text" name="name" size="105" maxlength="255" value="'.$org['name'].'"><br>
		<label for="about">О организиции:</label><br>
		<textarea name="about" cols="80" rows="9" >'.$org['about'].'</textarea><br>
		<img src="'.$org['image'].'"><br>
		<label for="image">Адрес лейбла/герба Вашей организации:</label><br>
		<input type="text" size="80" maxlength="255" name="image" value="'.$org['image'].'"><br>
		<label for="site">Адрес сайта добавляемой организации:</label><br>
		<input type="text" name="site" size="80" maxlength="255" value="'.$org['site'].'"><br>
		<label for="email">Адрес электронной почты организации:</label><br>
		<input type="email" size="80" maxlength="255" name="email" value="'.$org['email'].'"><br>
		<label for="tel">Телефон организации:</label><br>
		<input type="tel" size="80" maxlength="255" name="tel" value="'.$org['tel'].'"><br>
		<label for="city">Город, где расположена организация:</label>
		<select name="city">'.getCities($org['city_id'], $cities).'</select><br>
		<label for="address">Адрес организации:</label><br>
		<input type="text" size="80" maxlength="255" name="address" value="'.$org['address'].'"><br>
		<input type="hidden" name="org_id" value="'.$org['id'].'"><br>
		<input type="submit" name="save" value="Сохранить изменения">
		<input type="submit" name="delete" value="Удалить организацию"><br><br><hr>
		</form>';
		$num++;
		}
		$content.='<span id="add_test_error"><a href="/organizations/add">Добавить ещё организацию >></a></span><br><br><br>';
	}
	else
		$content.='<span id="add_test_error"><a href="/organizations/add">Добавте Вашу организацию >></a></span><br><br><br>';
	
	return $content;
}

//функция возвращает строку с названиями организаций пользователя
function getUserOrganizations(array $orgs, $id)
{
	$result;
	foreach($orgs as $org)
	{
		if($id==$org['id'])
			$result.='<option selected="selected" value="'.$org['id'].'">'.$org['name'].'</option>';
		else
			$result.='<option value="'.$org['id'].'">'.$org['name'].'</option>';
	}
	return $result;
}

//функция выводит формы для редактирования сотрудников
function getWorkersList($workers, $content, array $orgs)
{
	if(is_array($workers))
	{	//если пользователь еще не добавил ни одного сотрудника
		if(count($workers)==0)
		{
			$str.='Вы еще не добавили ни одного сотрудника!<br><span id="add_test_error"><a href="/organizations/addworker">Добавить сотрудника >></a></span><br><br><br>';
		}
		
		else
		{
			//проверка пришедших даных по организациям пользователя и вызов функции для генерации строки со списком организаций
			$str.=$content.'<br>Всего <b>'.count($workers).'</b> cотрудника(-ов)';
			
			
			$num=1;
			foreach($workers as $worker)
			{
				$str.='<h5>Сотрудник № '.$num.'</h5><center>
				<form action="/organizations/workers" method="post">
				<label for="org_id">Организация сотрудника:</label><br>
				<select name="org_id">'.getUserOrganizations($orgs, $worker['org_id']).'</select><br>
				<label for="fio">Ф.И.О. сотрудника:</label><br>
				<input type="text" name="fio" size="70" maxlength="255" value="'.$worker['fio'].'"><br>
				<label for="address">Адрес сотрудника:</label><br>
				<input type="text" name="address" size="70" maxlength="255" value="'.$worker['address'].'"><br>
				<label for="tel">Телефон сотрудника:</label><br>
				<input type="tel" name="tel" size="70" maxlength="255" value="'.$worker['tel'].'"><br>
				<label for="email">Электронная почта сотрудника:</label><br>
				<input type="email" name="email" size="70" maxlength="255" value="'.$worker['email'].'"><br>
				<label for="date">Дата добавления сотрудника:</label><br>
				<input type="date" name="date" size="70" maxlength="100" value="'.$worker['date'].'"><br>
				<input type="hidden" name="worker_id" value="'.$worker['id'].'"> 
				<input type="submit" name="save" value="Сохранить изменения"><input type="submit" name="delete" value="Удалить сотрудника и его результаты"></form></center><hr>
 				';
				$num++;
			}	
			
			$str.='<br><br><span id="add_test_error"><a href="/organizations/addworker">Добавить сотрудника >></a></span><br><br><br>';
		}
		
		return $str;
	}
	else return $str;
	
}

//функция для добавления нового сотрудника
function addWorker($addworker, array $orgs)
{
	if(count($orgs)==0)
		return '<p>Вы ещё не добавили ни одной организации для сотрудников!</p><span id="add_test_error"><a href="/organizations/add">Добавить новую организацию >></a></span><br><br><br>';
	else
		return $addworker.':<br><br><center><form action="/organizations/addworker" method="post">
	<label for="fio">Введите Ф.И.О. сотрудника:</label><br>
	<input type="text" name="fio" size=60 maxlength="255"><br>
	<label for="address">Введите адрес сотрудника:</label><br>
	<input type="text" name="address" size="60" maxlength="255"><br>
	<label for="tel">Введите телефон сотрудника:</label><br>
	<input type="tel" name="tel" maxlength="255" size="60"><br>
	<label for="email">Введите адрес электронной почты сотрудника:</label><br>
	<input type="email" name="email" maxlength="255" size="60"><br>
	<label for="orgs">Выберите организацию для сотрудника:</label>
	<select name="orgs">'.getUserOrganizations($orgs, -2).'</select><br>
	<input type="submit" name="add" value="Добавить">
 	</form></center>
	';	
}

//функция генерирует форму с организационными тестами созданными пользователем !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
function chiefForm($usertests, $orgtests)
{
	return usetTests($usertests).'<hr>'.orgTests($orgtests);
}

//функция редактирования пользовательских тестов
function usetTests($content)
{

	if(count($content)!=0)
	{
	$result='Всего создано пользовательских тестов: <b>'.count($content).'</b><br><br>
	<h3>Пользовательские тесты</h3>
	Название теста Описание Время на тест в мин. Вопросы Тема Действие 
	';

	//выборка тематики пользовательских тестов
	$types=Create::getUserTestsTypes();
		
	foreach($content as $item)
	{	
	$result.='<form method="post" action="/authorization/edittest"><h4>Тест №'.$item['id'].'</h4><br><br>
	<h5>Добавлен: '.$item['date'].'</h5>
	<label for="test_name">Название теста:</label><br>
	<input type="text" name="test_name" maxlength="255" size="100" value="'.$item['name'].'"><br>
	<label for="test_description">Описание теста:</label><br>
	<textarea id="testusrdesc'.$item['id'].'" name="test_description" cols="77" rows="5">'.$item['description'].'</textarea><script language="JavaScript">
  generate_wysiwyg("testusrdesc'.$item['id'].'");
</script><br>
	<label for="test_time">Время на выполнение теста:</label>
	<input size="4" maxlength="3" type="text" value="'.$item['time_min'].'" name="test_time"> мин.<br>
	<label for="tests">Вопросы теста:</label>
	<input type="submit" name="tests" value="'.$item['quantity'].' +/-"><br>
	<label for="test_theme">Темы теста:</label>
	<select name="test_theme">';
	
	//выборка тем пользовательских тестов
	foreach($types as $k=>$v)
		{
			if($v['id']==$item['theme_id'])
				$result.='<option selected="selected" value="'.$v['id'].'">'.$v['name'].'</option>';
			else
				$result.='<option value="'.$v['id'].'">'.$v['name'].'</option>';
		}
	
	$result.='</select><br>
	<input type="hidden" name="test_id" value="'.$item['id'].'">
	<input type="hidden" name="test_type" value="user">
	<center>
	<input type="submit" name="edit" value="Сохранить"><input type="submit" name="del" value="Удалить">
	<hr>
	<br>
	</form>';
	
	
	}
	
	return $result.'<br>
	<i>Измененения вступят в силу после нажатия кнопки "Сохранить", для того чтобы удалить тест, нажмиет на кнопку "Удалить", а для редактирования вопросов теста нажмите на кнопку с количеством вопросов в столбце "Вопросы"</i><br>';
	}
	else
		return 'Вы не создали ни одного пользовательского теста!<br><span id="add_test_error"><a href="/create"><< К форме создания тестов</a></span>';
		
}

//функция редактирования организационных тестов!!!!!!!!!!!!!!!!!!!!!!!!!
function orgTests($content)
{
	if(count($content)!=0)
	{
	$result='Всего создано организационных тестов: <b>'.count($content).'</b><br><br>
	<h3>Тесты организаций</h3>
	<table border="1" cellpadding="2" cellspacing="2"><tr><th>№</th><th>Название теста</th><th>Описание</th><th>Время на тест в мин.</th><th>Вопросы</th><th>Тема</th><th>Организация</th><th>Действие</th></tr>
	';

		
	foreach($content as $item)
	{	
	$result.='<tr><form method="post" action="/authorization/editorgtest"><td>'.$item['id'].'</td><td>
	<textarea name="test_name" cols="20" rows="5">'.$item['name'].'</textarea><center><i>Добавлен: '.$item['date'].'</i></center></td><td>
	<textarea name="test_description" cols="20" rows="5">'.$item['description'].'</textarea></td>
	<td><input size="6" maxlength="3" type="text" value="'.$item['time_min'].'" name="test_time"></td>
	<td><input type="submit" name="tests" value="'.$item['quantity'].' +/-"></td>
	<td><select name="test_theme">';
	
	//выборка тематики организационных тестов
		$types=Organizations::get()->getTestsTypes();
		
	//выборка тем
	foreach($types as $k=>$v)
		{
			if($v['id']==$item['theme_id'])
				$result.='<option selected="selected" value="'.$v['id'].'">'.$v['themes'].'</option>';
			else
				$result.='<option value="'.$v['id'].'">'.$v['themes'].'</option>';
		}
	
	$result.='</select></td>
	<td><select name="test_org">';
	
	//выборка тестируемых организаций 
		$orgs=Organizations::get()->myOrganiztions($_SESSION['loginfo']['id']);
		
	//выборка организаций
	foreach($orgs as $k=>$v)
		{
			if($v['id']==$item['org_id'])
				$result.='<option selected="selected" value="'.$v['id'].'">'.$v['name'].'</option>';
			else
				$result.='<option value="'.$v['id'].'">'.$v['name'].'</option>';
		}
	
	
	$result.='</select></td>
	<td><input type="hidden" name="test_id" value="'.$item['id'].'">
	<input type="hidden" name="test_type" value="org">
	<center>
	<input type="submit" name="edit" value="Сохранить"><input type="submit" name="del" value="Удалить"></center>
	</form></td>
	</tr>';
	
	
	}
	
	return $result.'</table><br>
	<i>Измененения вступят в силу после нажатия кнопки "Сохранить", для того чтобы удалить тест, нажмиет на кнопку "Удалить" в соответствующем поле столбца "Действие", а для редактирования вопросов теста нажмите на кнопку с количеством вопросов в столбце "Вопросы"</i><br><br>';
	}
	else
		return 'Вы не создали ни одного организационного теста!<br><span id="add_test_error"><a href="/create"><< К форме создания тестов</a></span>';
		
}

//функция выводит информацию об организации пользователя если он есть в списках иначе вывод соответствующего сообщения
function getWorkerOrg($worker_org)
{
	if(is_array($worker_org))
	{
		foreach($worker_org as $org)
		{
			$myorg.='<h4>'.$org['name'].'</h4>
			<img src="'.$org['image'].'"><br>
			<h5>Информация:</h5>
			<p>'.$org['about'].'</p>
			<h5>Сайт:</h5>
			<h6><a href="http://'.$org['site'].'">'.$org['site'].'</a></h6>
			<h5>Адрес:</h5>
			<p>'.$org['address'].'</p>
			<h5>e-mail:</h5>
			<h6><a href="mailto:'.$org['email'].'">'.$org['email'].'</a></h6>
			<h5>Телефон:</h5>
			<p>'.$org['tel'].'</p>
			';	
		}
		
		return $myorg;
	}
	else return 'Вас нет в списках сотрудников организаций!';
}

//функция возвращает список тестов организации для прохождения
function getOrgTests($orgtests)
{
	if(is_array($orgtests))
	{
		$tests='Всего тестов: <b>'.count($orgtests).'</b><br>';
		foreach($orgtests as $test)
		{
			$tests.='<center><h4>'.$test['name'].'</h4></center>
			<h5>Описание теста:</h5>
			<p>'.$test['description'].'</p>
			<h5>Дата добавления: '.$test['date'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Вопросов в тесте: '.$test['quantity'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Время на выполнение: '.$test['time_min'].' мин.</h5>
			<center><h4><a href="/execute/orgtest/id/'.$test['id'].'">Пройти тест  > ></a></h4></center><br><hr>
			';
		}	
		return $tests;
	}
	else return 'Для данной организации ещё не создано тестов!';
}

//функция возвращает результат прохождения тестов пользователем
function getWorkerResults($workerresults)
{
	if(is_array($workerresults))
	{
		$results='Результаты <b>'.count($workerresults).'</b> теста(-ов) пройденых мной:';
		
		$num=1;
		foreach($workerresults as $test)
		{
			$results.='<h4><a href="/execute/orgtest/id/'.$test['test_id'].'">Тест № '.$num.'</a> пройден: '.$test['date'].' числа, результат: '.$test['result'].'% от 100%,  тест пройден за: '.$test['time_min'].' мин. </h4><br><hr>';
			$num++;
		
		}
		
		
		return $results;
	}
	else return 'Вы ещё не прошли ни одного теста!<h4><a href="/organizations/orgtests">Пройти тесты для сотрудников моей организации  > ></a></h4>';	
}

//функция возвращает форму с информацией об университетмах добавленных пользователем!!!!!!!!!!!!!!!!!!!
function getMyUniversities($myunivers, $cities)
{	
	if(is_array($myunivers))
	{
		if(count($myunivers)==0)
			return 'Вы еще не добавляли университетов!<br><span id="add_test_error"><a href="/universities/add">Добавить ВУЗ > > </a></span>';
		
		$result='Всего ВУЗ`ов добавленых Вами <b>'.count($myunivers).'</b>:<br>';
		$num=1;
		foreach($myunivers as $univer)
		{
			$result.='<h5>ВУЗ № '.$num.'</h5>
			<form action="/universities/edituniver" method="post">
			<label for="name">Название университета:</label><br>
			<input type="text" size="70" maxlength="255" name="name" value="'.$univer['name'].'"><br>
			<img src="'.$univer['image'].'"/><br>
			<label for="image">Адрес изображения:</label><br>
			<input type="text" size="70" maxlength="255" name="image" value="'.$univer['image'].'"><br>
			<label for="city">Город в котором расположен униерситет:</label>
			<select name="city">'.getCities($univer['city_id'], $cities).'</select><br>
			<label for="address">Адрес университета:</label><br>
			<input type="text" size="70" maxlength="255" name="address" value="'.$univer['address'].'"><br>
			<label for="about">О университете:</label><br>
			<textarea name="about" cols="54" rows="6">'.$univer['about'].'</textarea><br>
			<label for="site">Адрес сайта университета:</label><br>
			<input type="text" size="70" maxlength="255" name="site" value="'.$univer['site'].'"><br>
			<label for="email">Адрес электронной почты университета:</label><br>
			<input type="email" size="70" maxlength="255" name="email" value="'.$univer['email'].'"><br>
		    <label for="tel">Номер телефона:</label><br>
			<input type="tel" size="70" maxlength="255" name="tel" value="'.$univer['tel'].'"><br>
			<input type="hidden" name="univer_id" value="'.$univer['id'].'">
			<input type="submit" name="faculty" formaction="/universities/myfaculties" value="Факультеты университета">
			<input type="submit" name="save" value="Сохранить изменения"> <input type="submit" name="delete" value="Удалить ВУЗ">
			<br><br><hr></form>';
			$num++;
		}
		
		return $result.='<span id="add_test_error"><a href="/universities/add">Добавить ВУЗ > > </a></span><br><br><br>';
	}	
	else return 'Ошибка при выборке университетов пользователя!';
}

//функция выводит форму с данными о студентах дли их удаления или обновления!!!!!!!!!!!!
function getMyStudents($mystudents,$cities)
{
	if(is_array($mystudents))
	{
		$cts=getCities('0', $cities);
		
		$result='Всего Вами добавлено студентов: <b>'.count($mystudents).'<b><br><br>';
		
		foreach($mystudents as $student)
		{
			$result.='<form method="post" action="/universities/mystudents">
			<label for="fio">Ф.И.О. студента:</label><br>
			<input type="text" maxlength="255" size="65" name="fio" value="'.$student['fio'].'"><br>
			<label for="email">Адрес электронной почты студента:</label><br>
			<input type="email" maxlength="255" size="65" name="email" value="'.$student['email'].'"><br>
			<label for="tel">Номер телефона студента:</label><br>
			<input type="tel" maxlength="255" size="65" name="tel" value="'.$student['tel'].'"><br>
			<label for="address">Адрес студента:</label><br>
			<input type="text" maxlength="255" size="65" name="address" value="'.$student['address'].'"><br>
			<label for="date">Дата зачисления студента в ВУЗ:</label><br>
			<input type="date" maxlength="255" size="65" name="date" value="'.$student['date'].'"><br>
			<p id="pspoiler">Дополнительные параметры +</p>
			<div id="spoiler">
			<label for="city">Город в котором расположен ВУЗ:</label>
	<select id="ucity" onchange="getUniver()" name="city"><option value="0">Выберите город \/</option>
	'.$cts.'</select><br>
	
	<label id="label_univer" for="univer">ВУЗ в котором учится студент:</label>
	<select id="univer" onchange="getFaculty()" name="univer"></select><br>
	
	<label id="label_faculty" for="faculty">Факультет:</label>
	<select id="faculty" onchange="getCourse()" name="faculty"></select><br>
	
	<label id="label_course" for="course">Курс на котором обучается студент:</label>
	<span id="course"><select name="course"></select><br></span>
	
	<label id="label_group" for="group">Группу студента:</label>
	<select id="group" name="group"></select><br>
	
			</div>
			<input type="hidden" name="student_id" value="'.$student['id'].'">
			<input type="submit" name="delete" value="Удалить студента и его результаты"><input type="submit" name="save" value="Сохранить изменения">
			</form>
			';
		}
		
		return $result.'<br><span id="add_test_error"><a href="/universities/addstudent">Добавить студента > > </a></span><br><br><br><br>';
	}
	else return 'Вы еще не добавляли студнетов в базу!<br><span id="add_test_error"><a href="/universities/addstudent">Добавить студента > > </a></span><br><br>';
}

//функция выводит форму для добавления нового студента!!!!!!!!!!!!
function addNewStudent($addstudent, $cities)
{
	$cts=getCities('0', $cities);
	return $addstudent.'<br><br>
	<form action="/universities/addstudent" method="post">
	<label for="fio">Введите Ф.И.О. студента:</label><br>
	<input type="text" size="65" maxlength="255" name="fio"><br>
	<label for="email">Введите адрес электронной почты студента:</label><br>
	<input type="email" size="65" maxlength="255" name="email"><br>
	<label for="tel">Введите телефон студента:</label><br>
	<input type="tel" size="65" maxlength="255" name="tel"><br>
	<label for="address">Введите адрес студента:</label><br>
	<input type="tel" size="65" maxlength="255" name="address"><br>
	<label for="date">Введите дату зачисления студента в ВУЗ:</label><br>
	<input type="date" size="65" maxlength="255" name="date"><br>
	<label for="city">Город в котором расположен ВУЗ студента:</label>
	<select id="ucity" onchange="getUniver()" name="city"><option value="0">Выберите город \/</option>
	'.$cts.'</select><br>
	
	<label id="label_univer" for="univer">Выберите ВУЗ в котором учится студент:</label>
	<select id="univer" onchange="getFaculty()" name="univer"></select><br>
	
	<label id="label_faculty" for="faculty">Выберите факультет:</label>
	<select id="faculty" onchange="getCourse()" name="faculty"></select><br>
	
	<label id="label_course" for="course">Выберите курс на котором обучается студент:</label>
	<span id="course"><select name="course"></select><br></span>
	
	<label id="label_group" for="group">Выберите группу студента:</label>
	<select id="group" name="group"></select><br>
	
	<input type="submit" name="add" value="Добавить студента">
	</form>
	';
}


//функция возвращает максимальное значение курса передаваемого в многомерном массиве
function getCountCourse(array $courses, $fac_id)
{
		foreach($courses as $k=>$v)
		{
			if($v['faculty_id']==$fac_id)
				$res[]=$v['course'];
		}
		
		return count($res);
}

//функция возвращает количество специальностей на факультете
function getCountSpec(array $spec, $fac_id)
{
	foreach($spec as $k=>$v)
		{
			if($v['faculty_id']==$fac_id)
				$res[]=$v['id'];
		}
		
	return count($res);
}

//функция выводит строку со специальностями факультета
function getFacSpec(array $spec, $fac_id)
{
		foreach($spec as $k=>$v)
		{
			if($v['faculty_id']==$fac_id)
				$res[]=$v['name'];
		}
		
	return @implode(',',$res);
}

//функция выводит форму со списком факультетов университета для редактирования!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
function getFaculty($univer, $faculties, $courses, $specs)
{
	if(is_array($faculties))
	{
		$result='Всего факультетов в ВУЗ`е: <b>'.count($faculties).'</b><br>';
		
		$num=1;
		foreach($faculties as $fac)
		{
			$result.='<form action="/universities/myfaculties" method="post">
			<h5>Факультет № '.$num.'</h5>
			<label for="name">Название факультета:</label><br>
			<input type="text" maxlength="255" size="100" name="name" value="'.$fac['name'].'"<br>
			<input type="hidden" id="fac_id" name="fac_id" value="'.$fac['id'].'"><br>
			<label for="courses">Курсов на факультете:</label>
			<input type="number" name="courses" max="8" min="0" value="'.getCountCourse($courses, $fac['id']).'"><br>
			<label for="specs">Специальности факультета (добавлять через запятую, удалять слева на право) всего: <b>'.getCountSpec($specs, $fac['id']).'</b></label><br>		<input type="text" name="specs" size="100" maxlength="255" value="'.getFacSpec($specs, $fac['id']).'"><br>
			<input type="submit" name="groups" title="Редактирование и добавление групп" value="Студенческие группы факультета" formaction="/universities/mygroups">
			<input type="submit" title="Будут удалены все студенты и результаты прохождения тестов!" name="delete" value="Удалить факультет">
			<input type="submit" name="edit" value="Сохранить изменения"><hr>
			</form>';
			$num++;
		}
		
		return $result.'<p id="info">Для того чтобы добавить новый факультет, нажмите на ссылку:</p>
		<form action="/universities/addfaculty" method="post">
		<input type="hidden" name="univer_id" value="'.$univer.'">
		<h5 id="add_faculty">Добавить факультет > > </h5>
		</form>';
	}
	else
	{
		return '<p id="info">Вы ещё не добавляли факультеты к данному ВУЗ`у!</p>
		<form action="/universities/addfaculty" method="post">
		<input type="hidden" name="univer_id" value="'.$univer.'">
		<h5 id="add_faculty">Добавить факультет > > </h5>
		</form>
		';	
		
	}	
	
}

//функция возвращает строку c отмеченным параметром специальности
function selectedSpec(array $specs,$specialty_id)
{
	foreach($specs as $sp)
	{
		if($sp['id']==$specialty_id)
			$res.='<option selected="selected" value="'.$sp['id'].'">'.$sp['name'].'</option>';	
		else 
			$res.='<option value="'.$sp['id'].'">'.$sp['name'].'</option>';	
	}
	return $res;
}

//функция возвращает строку c отмеченным параметром курса
function selectedCourses(array $courses,$course_id)
{
	foreach($courses as $crss)
	{
		if($crss['id']==$course_id)
			$res.='<option selected="selected" value="'.$crss['id'].'">'.$crss['course'].'</option>';	
		else 
			$res.='<option value="'.$crss['id'].'">'.$crss['course'].'</option>';	
	}
	return $res;
}

//функция выводит список студенческих групп
function getGroups($fac_id, $groups, array $specs, array $courses)
{
	if(is_array($groups))	
	{
		
		
		if(count($groups)==0)	
			return '<b>Вы еще не добавляли студенческие группы к данному факультету!</b>
			<center><form action="/universities/mygroups" method="post">
			<input type="hidden" name="fac_id" value="'.$fac_id.'">
			<h5 id="add_group" onclick="getSpecCour()">Добавить студенческую группу > ></h5>
			</form></center>
			';
		
		$num=1;
		foreach($groups as $group)
		{
			
			$grps.='<form action="/universities/editgroups" method="post">
			<input type="hidden" name="group_id" value="'.$group['id'].'">
			<h5>Группа № '.$num.'</h5>
			<label for="name">Название группы:</label><br>
			<input type="text" name="name" maxlength="255" size="70" value="'.$group['name'].'"><br>
			<label for="year">Год набора группы:</label>
			<input type="number" max="3000" min="1950" name="year" value="'.$group['year'].'">
			<label for"course">Курсы:</label>
			<select name="course">'.selectedCourses($courses,$group['course_id']).'</select><br>
			<label for="spec">Специальности:</label><br>
			<select name="spec">'.selectedSpec($specs,$group['specialty_id']).'</select><br><br>
			<input type="submit" formaction="/universities/lessons" name="lessons" value="Предметы группы">
			<input type="submit" name="save" value="Сохранить изменения">
			<input type="submit" title="...и всех студентов данной группы с результатами прохождения тестов!" name="delete" value="Удалить группу">
			<hr></form>
			';
			$num++;
		}	
		
		return $grps.'<center><form action="/universities/mygroups" method="post">
			<input type="hidden" name="fac_id" value="'.$fac_id.'">
			<h5 id="add_group" onclick="getSpecCour()">Добавить студенческую группу > ></h5>
			</form></center>
			';;
	}
	else return 'Ошибка при выборке групп!';
}

//функция возвращает форму для редактирования предметов группы!!!!!!!!!!!!!!!!!!!!!!!!!
function getLessons($lessons, $group)
{
	$cnt=count($lessons);
	if($cnt!=0)
	{
		$res='<p>Всего предметов в данной группе: <b>'.$cnt.'</b></p>';
			
			$num=1;
			foreach($lessons as $lesson)
			{
				$res.='<form action="" method="post">
				<h5>Предмет № '.$num.'</h5>
				<label for="name">Название:</label><br>
				<input type="text" name="name" maxlength="255" size="60" value="'.$lesson['name'].'"><br>
				<input type="hidden" name="id" value="'.$lesson['id'].'">
				<input type="submit" name="save" value="Сохранить изменения">
				<input type="submit" name="delete" title="Также будут удалены тесты созданые для этого предмета и результаты их прохождения!" value="Удалить предмет">		<hr>
				</form>
				';
				$num++;
			}
			
		return $res.'<form action="/universities/lessons" method="post">
	<input type="hidden" name="group_id" value="'.$group.'">
	<h5 id="addlesson">Добавить предмет > ></h5>
	</form>';
	}
	else return '<b>Вы еще не добавляли предметов для данной группы студентов!</b>
	<form action="/universities/lessons" method="post">
	<input type="hidden" name="group_id" value="'.$group.'">
	<h5 id="addlesson">Добавить предмет > ></h5>
	</form>
	';
}

//функция выводит тесты учителя
function teacherForm($usertests, $schooltests, $univertests)
{
	return univerTests($univertests).'<hr>'.schoolTests($schooltests).'<hr>'.usetTests($usertests).'<br><br>';
}

//функция выводит университетские тесты для редактирования!!!!!!!!!!!!!!!!!!!!!!!!!!!!
function univerTests($content)
{
	if(count($content)!=0)
	{
	$result='Всего создано университетских тестов: <b>'.count($content).'</b><br>
	<h3>Университетские тесты</h3>';

	//выборка университетских тестов созданных пользователем
	$univers=Universities::get()->getUniverIdName($_SESSION['loginfo']['id']);
		
	foreach($content as $item)
	{	
	$result.='<form method="post" action="/authorization/editunivertest"><h4>Тест № '.$item['id'].'</h4><h5>Добавлен: '.$item['date'].'</h5>
	<label for="test_name">Название теста:</label><br>
	<input type="text" name="test_name" maxlength="255" size="100" value="'.$item['name'].'"><br>
	<label for="test_description">Описание теста:</label><br>
	<textarea id="testdesc'.$item['id'].'" name="test_description" cols="77" rows="5">'.$item['description'].'</textarea><script language="JavaScript">
  generate_wysiwyg("testdesc'.$item['id'].'");
</script><br>
	<label for="test_time">Время на выполнение теста:</label>
	<input size="4" maxlength="3" type="text" value="'.$item['time_min'].'" name="test_time"> мин.<br>
	<label for="tests">Вопросы теста:</label>
	<input type="submit" name="tests" value="'.$item['quantity'].' +/-"><br>
	<p id="pspoiler2">Дополнительные параметры созданного теста +</p>
	<div id="spoiler2">
	<label for="univer_id" id="univer_label">Университет:</label>
	<select name="univer" id="univer" onchange="getFaculty()">
	<option value="0">Выберите университет для изменения теста \/</option>
	';
	
	//выборка университетов
	foreach($univers as $k=>$v)
		{
			if($v['id']==$item['theme_id'])
				$result.='<option selected="selected" value="'.$v['id'].'">'.$v['name'].'</option>';
			else
				$result.='<option value="'.$v['id'].'">'.$v['name'].'</option>';
		}
	
	$result.='</select><br>
	
	
	<label id="label_faculty" for="faculty">Выберите факультет:</label>
	<select id="faculty" onchange="getCourse()" name="faculty"></select><br>
	
	<label id="label_course" for="course">Выберите курс:</label>
	<span id="course"><select name="course"></select><br></span>
	
	<label id="label_group" for="group">Выберите тестируемую группу:</label>
	<select id="group" onchange="getLessons()" name="group"></select><br>
	
	<label id="label_lessons" for="group">Выберите предмет:</label>
	<select id="lessons" name="lessons"></select><br>
	
	</div>
	';
	
	$result.='<input type="hidden" name="test_id" value="'.$item['id'].'">
	<center>
	<input type="submit" name="edit" value="Сохранить"><input type="submit" name="delete" value="Удалить"></center><hr>
	</form>';
	
	
	}
	
	return $result.'<br>
	<i>Измененения вступят в силу после нажатия кнопки "Сохранить", для того чтобы удалить тест, нажмиет на кнопку "Удалить", а для редактирования вопросов теста нажмите на кнопку с количеством вопросов в столбце "Вопросы"</i><br><br>';
	}
	else
		return 'Вы не создали ни одного университетского теста!<br><span id="add_test_error"><a href="/create"><< К форме создания тестов</a></span>';
}

//функция выводит университет студента и список всех тестов для данного университета
function getStudentUniver($myuniver, $univertests)
{
	if(is_array($myuniver))
	{
		$res='<h3>'.$myuniver['name'].'</h3>
		<img src="'.$myuniver['image'].'"><br><br>
		<b>Описание: </b><br>'.$myuniver['about'].'<br><br>
		<b>Город: </b>'.$myuniver['city_id'].'<br><br>
		<b>Адрес: </b>'.$myuniver['address'].'<br><br>
		<b>Телефон: </b>'.$myuniver['tel'].'<br><br>
		<b>Адрес электронной почты: </b><a href="mailto:'.$myuniver['email'].'">'.$myuniver['email'].'</a><br><br>
		<b>Адрес сайта: </b><a href="http://'.$myuniver['site'].'">'.$myuniver['site'].'</a><br><br>';
		
		if(is_array($univertests))
		{
			$res.='<center><h4>Всего создано тестов для ВУЗ`а: '.count($univertests).'</h4><br><br>';
			
			$num=1;
			foreach($univertests as $test)
			{
				$res.='<h4>'.$num.'. <a title="Пройти тест..." href="/execute/univertest/id/'.$test['id'].'">'.$test['name'].'</a></h4>';
				$num++;
			}
			$res.='</center><br><br><br><br><br><br>';
			
			
		}
		else
			$res.='<h5>Для данного ВУЗ`а ещё не создано тестов!</h5>';
		
		return $res;
	}
	else
		return '<b>Вас нет в списках студентов какого-либо ВУЗ`а!</b>';
}

//функция выводит группу студента и созданные для данной группы тесты
function getStudentGroupTests($mygroup, $grouptests)
{
	if(is_array($mygroup))
	{
		$res='<h3>Студенческая группа: '.$mygroup['name'].'</h3>
		<b>Год набора группы: </b>'.$mygroup['year'].' <br>
		<b>Специальность: </b>'.$mygroup['specialty_id'].' <br>
		<b>Курс: </b>'.$mygroup['course_id'].' <br>
		<b>Студентов в группе: </b>'.$mygroup['students'].' <br>
		';
		
		if(is_array($grouptests))
		{
			$num=1;
			$res.='<center><h4>Всего созданно тестов для данной группы: '.count($grouptests).'</h4><br>';
			foreach($grouptests as $test)
			{
				$res.='<hr><h4>'.$num.'. <a title="Пройти тест..." href="/execute/univertest/id/'.$test['id'].'">'.$test['name'].'</a></h4><br>
				<b>Время на выполнение теста: </b>'.$test['time_min'].' мин.<br>
				<p><i>Описание теста:</i><br>'.$test['description'].'</p><br>';
				$num++;	
			}	
			
			return $res.'</center>';
		}
		else 
			$res.='<h5>Для данной студенческой группы ещё не создано тестов!</h5>';
		
		
		return $res;
	}
	else	
		 return '<b>Вы не числитесь ни в одной студенческой группе!</b>';
}

//возвращаем предметы и тесты созданные для них
function getTestsByLessons($mylessons, $lessonstests)
{
	if(is_array($mylessons))
	{
		$res='<h1>Всего предметов: '.count($mylessons).'</h1>
		Всего тестов: <b>'.count($lessonstests).'</b><br><br>';
		
		
		$num=1;
		foreach($mylessons as $lesson)
		{
			$res.='<h3>'.$num.'. '.$lesson['name'].':</h3><br>';
			
			if(is_array($lessonstests))
			{
				
				
				foreach($lessonstests as $test)
				{
				
					if($lesson['id']==$test['lesson_id'])
					{
					
				    $res.=' <center><b> - Название теста: <a title="Пройти тест..." href="/execute/univertest/id/'.$test['id'].'">'.$test['name'].'</a></b>	</center><br><br>
					<i><b>Описание теста: </b>'.$test['description'].'</i><br><br>
					Время на выполнение теста: <b>'.$test['time_min'].' мин.</b><br>
					Количество вопросов в тесте: <b>'.$test['quantity'].'</b><br><br>';
					$i=true;
					}
					
				
				}
				
					if(!isset($i))
					$res.=' <center>- <b>Для данного предмета ещё не создано тестов!</b><br></center>';
					unset($i);
			}
			else
				 $res.=' - <b>Для данного предмета ещё не создано тестов!</b><br>';
			
			$num++;	
			$res.='<hr><br>';
		}
		
		return $res;
	}
	else return '<b>Для Вашей группы еще не добавлено ни одного предмета!</b>';
	
}

//функция возвращает все школы добавленные пользователем
function getMySchools($myschools, $cities)
{	

	if(is_array($myschools))
	{
		if(count($myschools)==0)
			return 'Вы еще не добавляли школ!<br><span id="add_test_error"><a href="/schools/add">Добавить школу > > </a></span>';
		
		$result='Всего школ добавленых Вами <b>'.count($myschools).'</b>:<br>';
		$num=1;
		foreach($myschools as $school)
		{
			$result.='<h5>Порядковый номер школы № '.$num.'</h5>
			<form action="/schools/edit" method="post">
			<label for="name">Название школы:</label><br>
			<input type="text" size="70" maxlength="255" name="name" value="'.$school['name'].'"><br>
			<img src="'.$school['image'].'"/><br>
			<label for="image">Адрес изображения:</label><br>
			<input type="text" size="70" maxlength="255" name="image" value="'.$school['image'].'"><br>
			<label for="city">Город в котором расположена школа:</label>
			<select name="city">'.getCities($school['city_id'], $cities).'</select><br>
			<label for="address">Адрес школы:</label><br>
			<input type="text" size="70" maxlength="255" name="address" value="'.$school['address'].'"><br>
			<label for="about">О школе:</label><br>
			<textarea name="about" cols="54" rows="6">'.$school['about'].'</textarea><br>
			<label for="site">Адрес сайта школы:</label><br>
			<input type="text" size="70" maxlength="255" name="site" value="'.$school['site'].'"><br>
			<label for="email">Адрес электронной почты школы:</label><br>
			<input type="email" size="70" maxlength="255" name="email" value="'.$school['email'].'"><br>
		    <label for="tel">Номер телефона:</label><br>
			<input type="tel" size="70" maxlength="255" name="tel" value="'.$school['tel'].'"><br>
			<input type="hidden" name="school_id" value="'.$school['id'].'">
			<input type="hidden" name="user_id" value="'.$_SESSION['loginfo']['id'].'">
			<input type="submit" name="classes" formaction="/schools/classes" value="Классы школы">
			<input type="submit" name="save" value="Сохранить изменения"> <input type="submit" name="delete" value="Удалить школу">
			<br><br><hr></form>';
			$num++;
		}
		
		return $result.='<span id="add_test_error"><a href="/schools/add">Добавить школу > > </a></span><br><br><br>';
	}	
	else return 'Ошибка при выборке школ пользователя!';
}

//выводим классы школы
function getClasses($classes, $school_id)
{
	if(is_array($classes))
	{
		$result='<h4>Всего добавлено классов к школе: '.count($classes).'</h4><br><br>';
		
		$num=1;
		foreach($classes as $classes)
		{
			$result.='<h5>Класс № '.$num.'</h5><form action="/schools/classes" method="post">
			<label for="name">Название класса:</label><br>
			<input type="text" name="name" value="'.$classes['name'].'" size="75"><br>
			<input type="hidden" name="class_id" value="'.$classes['id'].'">
			<input type="submit" name="lessons" formaction="/schools/lessons" value="Предметы для данного класса">
			<input type="submit" name="save" value="Сохранить изменения">
			<input type="submit" title="Удаляет все результаты прохождения тестов, тесты, и учеников класса!" name="delete" value="Удалить класс"><hr>
			</form>';
			$num++;	
		}
		
		$result.='<form action="/schools/classes" method="post">
		<input type="hidden" name="school_id" value="'.$school_id.'">
		<h5 title="Добавить новый класс..." id="add_class">Добвать класс > ></h5>
		</form>
		';
		
		return $result;
	}
	else 
		return '<b>Ошибка при выборке классов!</b>';
}

//функция выводит все предметы для данного класса
function getSchoolLessons($lessons, $class_id)
{
	if(is_array($lessons))
	{
		$result='<h4>Всего предметов для данного класса: '.count($lessons).'</h4><br><br>';
		
		$num=1;
		foreach($lessons as $lesson)
		{
			$result.='<h5>Предмет № '.$num.'</h5><form action="/schools/lessons" method="post">
			<label for="name">Название предмета:</label><br>
			<input type="text" name="name" value="'.$lesson['name'].'" size="75"><br>
			<input type="hidden" name="lesson_id" value="'.$lesson['id'].'">
			<input type="submit" name="save" value="Сохранить изменения">
			<input type="submit" title="Удаляет все результаты прохождения тестов, тесты, и учеников класса!" name="delete" value="Удалить предмет"><hr>
			</form>';
			$num++;	
		}
		
		$result.='<form action="/schools/lessons" method="post">
		<input type="hidden" name="class_id" value="'.$class_id.'">
		<h5 title="Добавить новый предмет..." id="add_lesson">Добвать предмет > ></h5>
		</form>
		';
		
		return $result;
	}
	else 
		return '<b>Ошибка при выборке предметов класса!</b>';
}

//функция возвращает всех школьников добавленых пользователем
function getMyPupils($mypupils, $cities)
{
	if(is_array($mypupils))
	{
		
		$cts=getCities('0', $cities);
		
		$result='Всего Вами добавлено школьников: <b>'.count($mypupils).'<b><br><br>';
		
		foreach($mypupils as $pupil)
		{
			$result.='<form method="post" action="/schools/mypupils">
			<label for="fio">Ф.И.О. ученика:</label><br>
			<input type="text" maxlength="255" size="65" name="fio" value="'.$pupil['fio'].'"><br>
			<label for="email">Адрес электронной почты ученика:</label><br>
			<input type="email" maxlength="255" size="65" name="email" value="'.$pupil['email'].'"><br>
			<label for="tel">Номер телефона ученика:</label><br>
			<input type="tel" maxlength="255" size="65" name="tel" value="'.$pupil['tel'].'"><br>
			<label for="address">Адрес ученика:</label><br>
			<input type="text" maxlength="255" size="65" name="address" value="'.$pupil['address'].'"><br>
			<p id="pspoiler3">Дополнительные параметры +</p>
			<div id="spoiler3">
		
			<label id="label_scity" for="city">Город в котором расположена школа ученика:</label>
			<select id="scity" onchange="getSchool()" name="city"><option value="0">Выберите город \/</option>'.$cts.'
			</select><br>
			<label id="label_school" for="school">Выберите школу:</label>
			<select id="school" onchange="getClass()" name="school"></select><br>
	
			<label id="label_class" for="class">Выберите класс в котором учится школьник:</label>
			<select id="class" onchange="getButton()" name="class"></select><br>
			</div>
	
			
			<br>
			<input type="hidden" name="pupil_id" value="'.$pupil['id'].'">
			<input type="submit" name="save" value="Сохранить изменения">
			<input type="submit" name="delete" value="Удалить ученика и его результаты"><hr>
			</form>
			';
		}
		
		return $result.'<br><span id="add_test_error"><a href="/schools/addpupil">Добавить ученика > > </a></span><br><br><br><br>';
	}
	else return 'Вы еще не добавляли школьников в базу!<br><span id="add_test_error"><a href="/schools/addpupil">Добавить ученика > > </a></span><br><br>';
}

//функция выводит форму для добавления нового ученика
function addNewPupil($addpupil, $cities)
{
	$cts=getCities('0', $cities);

	return $addpupil.'<br><br>
	<form action="/schools/addpupil" method="post">
	<label for="fio">Введите Ф.И.О. школьника:</label><br>
	<input type="text" size="65" maxlength="255" name="fio"><br>
	<label for="email">Введите адрес электронной почты ученика:</label><br>
	<input type="email" size="65" maxlength="255" name="email"><br>
	<label for="tel">Введите телефон ученика:</label><br>
	<input type="tel" size="65" maxlength="255" name="tel"><br>
	<label for="address">Введите адрес школьника:</label><br>
	<input type="tel" size="65" maxlength="255" name="address"><br>
	<label for="city">Город в котором расположена школа ученика:</label>
	<select id="scity" onchange="getSchool()" name="city"><option value="0">Выберите город \/</option>'.$cts.'
	</select><br>
	<label id="label_school" for="school">Выберите школу:</label>
	<select id="school" onchange="getClass()" name="school"></select><br>
	
	<label id="label_class" for="class">Выберите класс в котором учится школьник:</label>
	<select id="class" onchange="getButton()" name="class"></select><br>
	
	<input type="submit" id="addbutton" name="add" value="Добавить ученика">
	</form>
	';
}

//функция выводит школу и список созданных для неё тестов
function getPupilSchool($myschool, $schooltests)
{
	if(is_array($myschool))
	{
		$res='<h3>'.$myschool['name'].'</h3>
		<img src="'.$myschool['image'].'"><br><br>
		<b>Описание: </b><br>'.$myschool['about'].'<br><br>
		<b>Город: </b>'.$myschool['city_id'].'<br><br>
		<b>Адрес: </b>'.$myschool['address'].'<br><br>
		<b>Телефон: </b>'.$myschool['tel'].'<br><br>
		<b>Адрес электронной почты: </b><a href="mailto:'.$myschool['email'].'">'.$myschool['email'].'</a><br><br>
		<b>Адрес сайта: </b><a href="http://'.$myschool['site'].'">'.$myschool['site'].'</a><br><br>';
		
		if(is_array($schooltests))
		{
			$res.='<center><h4>Всего создано тестов для школы: '.count($schooltests).'</h4><br><br>';
			
			$num=1;
			foreach($schooltests as $test)
			{
				$res.='<h4>'.$num.'. <a title="Пройти тест..." href="/execute/schooltest/id/'.$test['id'].'">'.$test['name'].'</a></h4>';
				$num++;
			}
			$res.='</center><br><br><br><br><br><br>';
			
			
		}
		else
			$res.='<h5>Для данной школы ещё не создано тестов!</h5>';
		
		return $res;
	}
	else
		return '<b>Вас нет в списках учеников каких-либо школ!</b>';
}

//функция возвращает списки предметов и созданых для них тестов
function getSchoolTestsByLessons($mylessons, $lessonstests)
{
	if(is_array($mylessons))
	{
		$res='<h1>Всего предметов: '.count($mylessons).'</h1>
		Всего тестов: <b>'.count($lessonstests).'</b><br><br>';
		
		
		$num=1;
		foreach($mylessons as $lesson)
		{
			$res.='<h3>'.$num.'. '.$lesson['name'].':</h3><br>';
			
			if(is_array($lessonstests))
			{
				foreach($lessonstests as $test)
				{
					if($lesson['id']==$test['lesson_id'])
					{
					
				    $res.=' <center><b> - Название теста: <a title="Пройти тест..." href="/execute/schooltest/id/'.$test['id'].'">'.$test['name'].'</a></b>	</center><br><br>
					<i><b>Описание теста: </b>'.$test['description'].'</i><br><br>
					Время на выполнение теста: <b>'.$test['time_min'].' мин.</b><br>
					Количество вопросов в тесте: <b>'.$test['quantity'].'</b><br><br>';
					$i=true;
					}
					
				
				}
				
					if(!isset($i))
					$res.=' <center>- <b>Для данного предмета ещё не создано тестов!</b><br></center>';
					unset($i);
			}
			else
				 $res.=' - <b>Для данного предмета ещё не создано тестов!</b><br>';
			
			$num++;	
			$res.='<hr><br>';
		}
		
		return $res;
	}
	else return '<b>Для Вашего класса еще не добавлено ни одного предмета!</b>';
}

//функция выводит информацию о классе ученика и тестах созданных для данного класса
function getPupilClassTests($myclass, $classtests)
{
	if(is_array($myclass))
	{
		$res='<h3>Класс: '.$myclass['name'].'</h3>
		<b>Учеников в классе: </b>'.$myclass['count'].' <br>
		';
		
		if(is_array($classtests))
		{
			$num=1;
			$res.='<center><h4>Всего созданно тестов для данного класса: '.count($classtests).'</h4><br>';
			foreach($classtests as $test)
			{
				$res.='<hr><h4>'.$num.'. <a title="Пройти тест..." href="/execute/schooltest/id/'.$test['id'].'">'.$test['name'].'</a></h4><br>
				<b>Время на выполнение теста: </b>'.$test['time_min'].' мин.<br>
				<p><i>Описание теста:</i><br>'.$test['description'].'</p><br>';
				$num++;	
			}	
			
			return $res.'</center>';
		}
		else 
			$res.='<h5>Для данного класса школы ещё не создано тестов!</h5>';
		
		
		return $res;
	}
	else	
		 return '<b>Вы не числитесь ни в одном классе школы!</b>';
}

//функция выводит оценку данного теста
function getMark($rating)
{
	if(is_null($rating))
		return 'Оценок ещё нет';
	else
		return '<i>'.$rating.'</i>';
}

//функция выводит средний результат данного теста
function getResult($results, $tested)
{
	if(is_null($results))
		return '0';
	else
		{
			if($tested==0)
				return '<i>0%</i>';
			else
				return '<i>'.(int)($results/$tested).'%</i>';
		}
}

//функция выводит информацию о пользователях и созданных ими тестов
function getUserTests($userstests)
{
	if(is_array($userstests['users']))
	{
		$count_users=count($userstests['users']);
		$count_tests=count($userstests['tests']);
		$res.='<br>Всего пользователей: <b>'.$count_users.'</b><br>Пользовательских тестов всего: <b>'.$count_tests.'</b><br>';
		
		if($count_users==0)
			return '<h5>Пользователей нет в базе данных!</h5>';
		
		foreach($userstests['users'] as $user)
		{
			$res.='<center><h3>Пользователь: '.$user['login'].
			'</h3></center><br><img src="'.$user['image'].'"><br>'.
			'<br><b>Ф.И.О.:</b> '.$user['fio'].
			'<br><b>Страна:</b> '.$user['country'].
			'<br><b>Город:</b> '.$user['city'].'<br><b>Тип:</b> '.$user['type'].
			'<br><b>О пользователе:</b><br>'.$user['about'].'<br><b>email:</b> <a href="mailto:'.$user['email'].'">'.$user['email'].'</a><br>';
			$res.='<center><h4>Тесты пользователя:</h4><br></center>';
				
				if(is_array($userstests['tests']))
				{
					foreach($userstests['tests'] as $tests)
					{
						if($tests['user_id']==$user['id'])
						{
							$res.='<center><h3><a title="Пройти тест..." href="/execute/usertest/id/'.$tests['id'].'">'.$tests['name'].'</a></h3></center><b>Название:</b> '.$tests['name'].'<br><b>Тема:</b> '.$tests['theme'].
							'<br><b>Описание теста:</b><br>'.$tests['description'].'<br><b>Количество вопросов:</b> '.$tests['quantity'].
							'<br><b>Дата создания:</b> '.$tests['date'].'<br><b>Время на выполнение:</b> '.$tests['time_min'].' <b>мин.</b>'.
							'<br><b>Оценка теста:</b> '.getMark($tests['rating']).'<br><b>Прошедших тест:</b> '.$tests['count'].
							'<br><b>Средний результат:</b> '.getResult($tests['results'], $tests['count']);
							$i=true;
						}
						
					}
					
					if(!isset($i))
						$res.='<br><center><b>Ещё не создано тестов для пользователей!</b></center><br>';
					unset($i);
				}
				
				$res.='<hr><br>';
		}
	}
	else 
		return 'Возникла ошибка при выборке пользователей!';
	
	return $res;
}

//функция выводит все школы и созданные для неё тесты
function getAllSchools($allschools)
{
	if(is_array($allschools['schools']))
	{
		$count_schools=count($allschools['schools']);
		$count_tests=count($allschools['tests']);
		$res.='<br>Всего школ: <b>'.$count_schools.'</b><br>Школьных тестов всего: <b>'.$count_tests.'</b><br>';
		
		if($count_schools==0)
			return '<h5>Школ нет в базе данных!</h5>';
		
		foreach($allschools['schools'] as $school)
		{
			$res.='<center><h3>Школа: '.$school['name'].
			'</h3></center><br><img src="'.$school['image'].'"><br>'.
			'<br><b>Добавил школу:</b> '.$school['user'].
			'<br><b>Страна:</b> '.$school['country'].
			'<br><b>Город:</b> '.$school['city'].'<br>
			<b>Адрес:</b> '.$school['address'].
			'<br><b>О школе:</b><br>'.$school['about'].'
			<br><b>Телефон: </b>'.$school['tel'].'
			<br><b>Сайт школы: </b><a href="http://'.$school['site'].'">'.$school['site'].'</a>
			<br><b>email:</b> <a href="mailto:'.$school['email'].'">'.$school['email'].'</a><br>';
			$res.='<center><h4>Тесты для данной школы:</h4><br></center>';
				
				if(is_array($allschools['tests']))
				{
					foreach($allschools['tests'] as $tests)
					{
						if($tests['school_id']==$school['id'])
						{
							$res.='<center><h3><a title="Пройти тест..." href="/execute/schooltest/id/'.$tests['id'].'">'.$tests['name'].'</a></h3></center><b>Название:</b> '.$tests['name'].'<br><b>Для класса:</b> '.$tests['class'].
							'<br><b>По предмету:</b> '.$tests['lesson'].
							'<br><b>Описание теста:</b><br>'.$tests['description'].'<br><b>Количество вопросов:</b> '.$tests['quantity'].
							'<br><b>Дата создания:</b> '.$tests['date'].'<br><b>Время на выполнение:</b> '.$tests['time_min'].' <b>мин.</b>'.
							'<br><b>Оценка теста:</b> '.getMark($tests['rating']).'<br><b>Прошедших тест:</b> '.$tests['count'].
							'<br><b>Средний результат:</b> '.getResult($tests['results'], $tests['count']);
							$i=true;
						}
						
					}
					
					if(!isset($i))
						$res.='<br><center><b>Ещё не создано тестов для данной школы!</b></center><br>';
					unset($i);
				}
				
				$res.='<hr><br>';
		}
	}
	else 
		return 'Возникла ошибка при выборке школ!';
	
	return $res;
}

//функция выводит все организации и тесты созданные для них
function getAllOrgTests($orgtests)
{
	if(is_array($orgtests['orgs']))
	{
		$count_orgs=count($orgtests['orgs']);
		$count_tests=count($orgtests['tests']);
		$res.='<br>Всего организаций: <b>'.$count_orgs.'</b><br>Тестов для организаций всего: <b>'.$count_tests.'</b><br>';
		
		if($count_orgs==0)
			return '<h5>Организаций нет в базе данных!</h5>';
		
		foreach($orgtests['orgs'] as $org)
		{
			$res.='<center><h3>Организация: '.$org['name'].
			'</h3></center><br><img src="'.$org['image'].'"><br>'.
			'<br><b>Добавил организацию:</b> '.$org['user'].
			'<br><b>Страна:</b> '.$org['country'].
			'<br><b>Город:</b> '.$org['city'].'<br>
			<b>Адрес:</b> '.$org['address'].
			'<br><b>Описание организации:</b><br>'.$org['about'].'
			<br><b>Телефон: </b>'.$org['tel'].'
			<br><b>Сайт: </b><a href="http://'.$org['site'].'">'.$org['site'].'</a>
			<br><b>email:</b> <a href="mailto:'.$org['email'].'">'.$org['email'].'</a><br>';
			$res.='<center><h4>Тесты для данной организации:</h4><br></center>';
				
				if(is_array($orgtests['tests']))
				{
					foreach($orgtests['tests'] as $tests)
					{
						if($tests['org_id']==$org['id'])
						{
							$res.='<center><h3><a title="Пройти тест..." href="/execute/orgtest/id/'.$tests['id'].'">'.$tests['name'].'</a></h3></center><b>Название:</b> '.$tests['name'].
							'<br><b>По теме:</b> '.$tests['theme'].
							'<br><b>Описание теста:</b><br>'.$tests['description'].'<br><b>Количество вопросов:</b> '.$tests['quantity'].
							'<br><b>Дата создания:</b> '.$tests['date'].'<br><b>Время на выполнение:</b> '.$tests['time_min'].' <b>мин.</b>'.
							'<br><b>Оценка теста:</b> '.getMark($tests['rating']).'<br><b>Прошедших тест:</b> '.$tests['count'].
							'<br><b>Средний результат:</b> '.getResult($tests['results'], $tests['count']);
							$i=true;
						}
						
					}
					
					if(!isset($i))
						$res.='<br><center><b>Ещё не создано тестов для данной организации!</b></center><br>';
					unset($i);
				}
				
				$res.='<hr><br>';
		}
	}
	else 
		return 'Возникла ошибка при выборке организаций!';
	
	return $res;
}

//функция выводит все университеты и созданные для них тесты
function getAllUniverTests($universtests)
{
	
	if(is_array($universtests['univers']))
	{
		$count_univers=count($universtests['univers']);
		$count_tests=count($universtests['tests']);
		$res.='<br>Всего ВУЗ`ов: <b>'.$count_univers.'</b><br>Тестов для ВУЗ`ов всего: <b>'.$count_tests.'</b><br>';
		
		if($count_univers==0)
			return '<h5>ВУЗ`ов нет в базе данных!</h5>';
		
		foreach($universtests['univers'] as $univer)
		{
			$res.='<center><h3>ВУЗ: '.$univer['name'].
			'</h3></center><br><img src="'.$univer['image'].'"><br>'.
			'<br><b>Добавил ВУЗ:</b> '.$univer['user'].
			'<br><b>Страна:</b> '.$univer['country'].
			'<br><b>Город:</b> '.$univer['city'].'<br>
			<b>Адрес:</b> '.$univer['address'].
			'<br><b>Описание организации:</b><br>'.$univer['about'].'
			<br><b>Телефон: </b>'.$univer['tel'].'
			<br><b>Сайт: </b><a href="http://'.$univer['site'].'">'.$univer['site'].'</a>
			<br><b>email:</b> <a href="mailto:'.$univer['email'].'">'.$univer['email'].'</a><br>';
			$res.='<center><h4>Тесты для данного ВУЗ`а:</h4><br></center>';
				
				if(is_array($universtests['tests']))
				{
					foreach($universtests['tests'] as $tests)
					{
						if($tests['university_id']==$univer['id'])
						{
							$res.='<center><h3><a title="Пройти тест..." href="/execute/univertest/id/'.$tests['id'].'">'.$tests['name'].'</a></h3></center><b>Название:</b> '.$tests['name'].
							'<br><b>Факультет:</b> '.$tests['faculty'].
							'<br><b>Специальность:</b> '.$tests['spec'].
							'<br><b>Курс:</b> '.$tests['course'].
							'<br><b>Группа:</b> '.$tests['groups'].
							'<br><b>Предмет:</b> '.$tests['lesson'].
							'<br><b>Описание теста:</b><br>'.$tests['description'].'<br><b>Количество вопросов:</b> '.$tests['quantity'].
							'<br><b>Дата создания:</b> '.$tests['date'].'<br><b>Время на выполнение:</b> '.$tests['time_min'].' <b>мин.</b>'.
							'<br><b>Оценка теста:</b> '.getMark($tests['rating']).'<br><b>Прошедших тест:</b> '.$tests['count'].
							'<br><b>Средний результат:</b> '.getResult($tests['results'], $tests['count']);
							$i=true;
						}
						
					}
					
					if(!isset($i))
						$res.='<br><center><b>Ещё не создано тестов для данного ВУЗ`а!</b></center><br>';
					unset($i);
				}
				
				$res.='<hr><br>';
		}
	}
	else 
		return 'Возникла ошибка при выборке ВУЗ`ов!';
	
	return $res;
}

//функция выводит формы для поиска информации
function getSerachForms($usertests, $orgtests)
{
	$res='<h5>Поиск среди всех тестов:</h5>
	<form action="/search" method="post">
	<label for="searchtest">Поиск по названию и описанию теста:</label><br>
	<input type="text" name="searchtest" size="80" maxlength="255" min="3">
	<input type="submit" formaction="/search/byname" name="search" value="Поиск"><br>
	<label for="articles">Поиск статей по названию и описанию:</label><br>
	<input type="text" name="articles" maxlength="255" min="3" size="80">
	<input type="submit" formaction="/search/articles" name="searcharticles" value="Поиск"><br>
	<label for="date">Поиск тестов по дате ( <i>в формате: 2000-12-31</i> ):</label><br>
	<input type="date" name="date" maxlength="255" min="3">
	<input type="submit" formaction="/search/bydate" name="searchdate" value="Поиск"><br>
	<h4>Категории пользовательских тестов:</h4><br>Всего категорий: <b>'
	.count($usertests).'</b><br>'
	;
	
	if(is_array($usertests))
	{
		$num=1;
		foreach($usertests as $test)
			{
				$res.='<h4>'.$num.') <a title="Показать список тестов данной темы..." href="/search/usertest/id/'.$test['id'].'">'.$test['name'].'</a></h4><br>';
				$num++;
			}
	}
	else
		$res.='Ещё не добавлено ни одной категории!';
	
	
	$res.='<br><h4>Категории организационных тестов:</h4><br>Всего категорий: <b>'
	.count($orgtests).'</b><br>'
	;
	
	if(is_array($orgtests))
	{
		$num=1;
		foreach($orgtests as $test)
			{
				$res.='<h4>'.$num.') <a title="Показать список тестов данной темы..." href="/search/orgtest/id/'.$test['id'].'">'.$test['themes'].'</a></h4><br>';
				$num++;
			}
	}
	else
		$res.='Ещё не добавлено ни одной категории!';
	
	
	
	$res.='</form>';	
	
	return $res;
}

//выводим результат поиска по всем тестам
function getSearchAllResults($nameabout, $key)
{
	$res='<h4>Результат поиска по: '.$key.'</h4><br>';
	if(!is_array($nameabout))
		return $res.'Ошибка при поиске! Вы должны ввести минимум три символа!';
	if(!is_array($nameabout['user']))
		return $res.'Ошибка при поиске пользовательских тестов!';
	if(!is_array($nameabout['org']))
		return $res.'Ошибка при поиске организационных тестов!';
	if(!is_array($nameabout['univer']))
		return $res.'Ошибка при поиске университетских тестов !';
	if(!is_array($nameabout['school']))
		return $res.'Ошибка при поиске школьных тестов!';
	
	//вывод совпадений из пользовательских тестов
	$user_count=count($nameabout['user']);
	$res.='<h3>Пользовательские тесты</h3><br>';
	
	if($user_count==0)
		$res.='Не найдено среди пользовательских тестов!<br><br><hr>';
	else
	{	
		$res.='Найдено тестов: <b>'.$user_count.'</b>';
		
		foreach($nameabout['user'] as $tests)
		{
			$res.='<center><h4><a href="/execute/usertest/id/'.$tests['id'].'">'.$tests['name'].'</a></h4></center><br>
			<i>Описание теста:</i><br>'.$tests['description'].'<br>
			<b>Тест создал: </b>'.$tests['fio'].'<br>
			<b>Страна: </b>'.$tests['country_id'].'<br>
			<b>Город: </b>'.$tests['city_id'].'<br>
			<b>Время на выполнение: </b>'.$tests['time_min'].'<br>
			<b>Вопросов в тесте: </b>'.$tests['quantity'].'<br>
			<b>Дата создания: </b>'.$tests['date'].'<br>
			<b>Оценка: </b>'.getMark($tests['rating']).'<br>
			<b>Средний результат: </b>'.getResult($tests['results']).'<br>
			<b>Количество прошедших: </b>'.$tests['count'].'<br><br>';
		}
		$res.='<hr>';
	}
	
	//вывод совпадений из организационных тестов
	$org_count=count($nameabout['org']);
	$res.='<h3>Организационные тесты</h3><br>';
	
	if($org_count==0)
		$res.='Не найдено среди тестов организаций!<br><br><hr>';
	else
	{	
		$res.='Найдено тестов: <b>'.$org_count.'</b>';
		
		foreach($nameabout['org'] as $tests)
		{
			$res.='<center><h4><a href="/execute/orgtest/id/'.$tests['id'].'">'.$tests['name'].'</a></h4></center><br>
			<i>Описание теста:</i><br>'.$tests['description'].'<br>
			<b>Тест создал: </b>'.$tests['fio'].'<br>
			<b>Страна: </b>'.$tests['country_id'].'<br>
			<b>Город: </b>'.$tests['city_id'].'<br>
			<b>Время на выполнение: </b>'.$tests['time_min'].'<br>
			<b>Вопросов в тесте: </b>'.$tests['quantity'].'<br>
			<b>Дата создания: </b>'.$tests['date'].'<br>
			<b>Оценка: </b>'.getMark($tests['rating']).'<br>
			<b>Средний результат: </b>'.getResult($tests['results'], $tests['count']).'<br>
			<b>Количество прошедших: </b>'.$tests['count'].'<br><br>';
		}
		$res.='<hr>';
	}
	
	//вывод совпадений из школьных тестов
	$school_count=count($nameabout['school']);
	$res.='<h3>Школьные тесты</h3><br>';
	
	if($school_count==0)
		$res.='Не найдено среди тестов для школьников!<br><br><hr>';
	else
	{	
		$res.='Найдено тестов: <b>'.$school_count.'</b>';
		
		foreach($nameabout['school'] as $tests)
		{
			$res.='<center><h4><a href="/execute/schooltest/id/'.$tests['id'].'">'.$tests['name'].'</a></h4></center><br>
			<i>Описание теста:</i><br>'.$tests['description'].'<br>
			<b>Тест создал: </b>'.$tests['fio'].'<br>
			<b>Страна: </b>'.$tests['country_id'].'<br>
			<b>Город: </b>'.$tests['city_id'].'<br>
			<b>Время на выполнение: </b>'.$tests['time_min'].'<br>
			<b>Вопросов в тесте: </b>'.$tests['quantity'].'<br>
			<b>Дата создания: </b>'.$tests['date'].'<br>
			<b>Оценка: </b>'.getMark($tests['rating']).'<br>
			<b>Средний результат: </b>'.getResult($tests['results'], $tests['count']).'<br>
			<b>Количество прошедших: </b>'.$tests['count'].'<br><br>';
		}
		$res.='<hr>';
	}
	
	//вывод совпадений из университетских тестов
	$univer_count=count($nameabout['univer']);
	$res.='<h3>Университетские тесты</h3><br>';
	
	if($univer_count==0)
		$res.='Не найдено среди тестов для студентов!<br><br><hr>';
	else
	{	
		$res.='Найдено тестов: <b>'.$univer_count.'</b>';
		
		foreach($nameabout['univer'] as $tests)
		{
			$res.='<center><h4><a href="/execute/univertest/id/'.$tests['id'].'">'.$tests['name'].'</a></h4></center><br>
			<i>Описание теста:</i><br>'.$tests['description'].'<br>
			<b>Тест создал: </b>'.$tests['fio'].'<br>
			<b>Страна: </b>'.$tests['country_id'].'<br>
			<b>Город: </b>'.$tests['city_id'].'<br>
			<b>Время на выполнение: </b>'.$tests['time_min'].'<br>
			<b>Вопросов в тесте: </b>'.$tests['quantity'].'<br>
			<b>Дата создания: </b>'.$tests['date'].'<br>
			<b>Оценка: </b>'.getMark($tests['rating']).'<br>
			<b>Средний результат: </b>'.getResult($tests['results'], $tests['count']).'<br>
			<b>Количество прошедших: </b>'.$tests['count'].'<br><br>';
		}
		$res.='<hr>';
	}
	
	return $res.'<br><br>';
}

//выводим результаты поиска тестов по дате
function getSearchByDate($testsdate, $key)
{
	$res='<h4>Результат поиска по: '.$key.'</h4><br>';
	if(!is_array($testsdate))
		return $res.'Ошибка при поиске! Вы должны ввести дату корректно в формате <i>2013-12-31</i>';
	if(!is_array($testsdate['user']))
		return $res.'Ошибка при поиске пользовательских тестов!';
	if(!is_array($testsdate['org']))
		return $res.'Ошибка при поиске организационных тестов!';
	if(!is_array($testsdate['univer']))
		return $res.'Ошибка при поиске университетских тестов !';
	if(!is_array($testsdate['school']))
		return $res.'Ошибка при поиске школьных тестов!';
	
	//вывод совпадений из пользовательских тестов
	$user_count=count($testsdate['user']);
	$res.='<h3>Пользовательские тесты</h3><br>';
	
	if($user_count==0)
		$res.='Не найдено среди пользовательских тестов!<br><br><hr>';
	else
	{	
		$res.='Найдено тестов: <b>'.$user_count.'</b>';
		
		foreach($testsdate['user'] as $tests)
		{
			$res.='<center><h4><a href="/execute/usertest/id/'.$tests['id'].'">'.$tests['name'].'</a></h4></center><br>
			<i>Описание теста:</i><br>'.$tests['description'].'<br>
			<b>Тест создал: </b>'.$tests['fio'].'<br>
			<b>Страна: </b>'.$tests['country_id'].'<br>
			<b>Город: </b>'.$tests['city_id'].'<br>
			<b>Время на выполнение: </b>'.$tests['time_min'].'<br>
			<b>Вопросов в тесте: </b>'.$tests['quantity'].'<br>
			<b>Дата создания: </b>'.$tests['date'].'<br>
			<b>Оценка: </b>'.getMark($tests['rating']).'<br>
			<b>Средний результат: </b>'.getResult($tests['results'], $tests['count']).'<br>
			<b>Количество прошедших: </b>'.$tests['count'].'<br><br>';
		}
		$res.='<hr>';
	}
	
	//вывод совпадений из организационных тестов
	$org_count=count($testsdate['org']);
	$res.='<h3>Организационные тесты</h3><br>';
	
	if($org_count==0)
		$res.='Не найдено среди тестов организаций!<br><br><hr>';
	else
	{	
		$res.='Найдено тестов: <b>'.$org_count.'</b>';
		
		foreach($testsdate['org'] as $tests)
		{
			$res.='<center><h4><a href="/execute/orgtest/id/'.$tests['id'].'">'.$tests['name'].'</a></h4></center><br>
			<i>Описание теста:</i><br>'.$tests['description'].'<br>
			<b>Тест создал: </b>'.$tests['fio'].'<br>
			<b>Страна: </b>'.$tests['country_id'].'<br>
			<b>Город: </b>'.$tests['city_id'].'<br>
			<b>Время на выполнение: </b>'.$tests['time_min'].'<br>
			<b>Вопросов в тесте: </b>'.$tests['quantity'].'<br>
			<b>Дата создания: </b>'.$tests['date'].'<br>
			<b>Оценка: </b>'.getMark($tests['rating']).'<br>
			<b>Средний результат: </b>'.getResult($tests['results'], $tests['count']).'<br>
			<b>Количество прошедших: </b>'.$tests['count'].'<br><br>';
		}
		$res.='<hr>';
	}
	
	//вывод совпадений из школьных тестов
	$school_count=count($testsdate['school']);
	$res.='<h3>Школьные тесты</h3><br>';
	
	if($school_count==0)
		$res.='Не найдено среди тестов для школьников!<br><br><hr>';
	else
	{	
		$res.='Найдено тестов: <b>'.$school_count.'</b>';
		
		foreach($testsdate['school'] as $tests)
		{
			$res.='<center><h4><a href="/execute/schooltest/id/'.$tests['id'].'">'.$tests['name'].'</a></h4></center><br>
			<i>Описание теста:</i><br>'.$tests['description'].'<br>
			<b>Тест создал: </b>'.$tests['fio'].'<br>
			<b>Страна: </b>'.$tests['country_id'].'<br>
			<b>Город: </b>'.$tests['city_id'].'<br>
			<b>Время на выполнение: </b>'.$tests['time_min'].'<br>
			<b>Вопросов в тесте: </b>'.$tests['quantity'].'<br>
			<b>Дата создания: </b>'.$tests['date'].'<br>
			<b>Оценка: </b>'.getMark($tests['rating']).'<br>
			<b>Средний результат: </b>'.getResult($tests['results'], $tests['count']).'<br>
			<b>Количество прошедших: </b>'.$tests['count'].'<br><br>';
		}
		$res.='<hr>';
	}
	
	//вывод совпадений из университетских тестов
	$univer_count=count($testsdate['univer']);
	$res.='<h3>Университетские тесты</h3><br>';
	
	if($univer_count==0)
		$res.='Не найдено среди тестов для студентов!<br><br><hr>';
	else
	{	
		$res.='Найдено тестов: <b>'.$univer_count.'</b>';
		
		foreach($testsdate['univer'] as $tests)
		{
			$res.='<center><h4><a href="/execute/univertest/id/'.$tests['id'].'">'.$tests['name'].'</a></h4></center><br>
			<i>Описание теста:</i><br>'.$tests['description'].'<br>
			<b>Тест создал: </b>'.$tests['fio'].'<br>
			<b>Страна: </b>'.$tests['country_id'].'<br>
			<b>Город: </b>'.$tests['city_id'].'<br>
			<b>Время на выполнение: </b>'.$tests['time_min'].'<br>
			<b>Вопросов в тесте: </b>'.$tests['quantity'].'<br>
			<b>Дата создания: </b>'.$tests['date'].'<br>
			<b>Оценка: </b>'.getMark($tests['rating']).'<br>
			<b>Средний результат: </b>'.getResult($tests['results'], $tests['count']).'<br>
			<b>Количество прошедших: </b>'.$tests['count'].'<br><br>';
		}
		$res.='<hr>';
	}
	
	return $res.'<br><br>';
}


//функция возвращает результат поиска статей
function getSearchArticle($articles, $key)
{
	$res='<h4>Результат поиска по: '.$key.'</h4><br>';
	if(!is_array($articles))
		return $res.'Ошибка при поиске! Вы должны ввести минимум три символа!';
	
	//вывод совпадений из пользовательских тестов
	$count=count($articles);
	$res.='<h3>Статьи</h3><br>';
	
	if($count==0)
		$res.='Не найдено среди статей!<br><br><hr>';
	else
	{	
		$res.='Найдено статей: <b>'.$count.'</b>';
		
		foreach($articles as $art)
		{
			$res.='<center><h4><a title="Прочитать полностью..." href="/articles/get/id/'.$art['id'].'">'.$art['title'].'</a></h4><br>
			<img src="'.$art['img'].'"><br></center>
			<i>Аннотация:</i><br>'.$art['meta_description'].'<br>
			<b>Статью добавил: </b>'.$art['user'].'<br>
			<b>Дата добавления: </b>'.$art['date'].'<br>
			<b>Оценка: </b>'.getMark($art['rating']).'<br>
			<b>Голосовавших: </b>'.getResult($art['rating'], $art['votes']).'<br>
			<b>Просмотров </b>'.$art['count'].'<br><br><hr>';
		}
		$res.='<br>';
	}
	
	return $res;
}

//функция выводит пользовательские тесты по категории
function getSearchTheme($usertests)
{
	if(!is_array($usertests))
		return $res.'Ошибка при поиске! Нет такой категории!';
	if(!is_array($usertests['tests']))
		return $res.'Ошибка при поиске пользовательских тестов!';
	if(!is_array($usertests['theme']))
		return $res.'Ошибка при при выборке категории тестов!';
		
		
	$res.='<h4>Название категории: '.$usertests['theme']['name'].'</h4><br><b>Описание: </b> '.$usertests['theme']['description'].'<br>';
		
		
		
	//вывод совпадений из пользовательских тестов
	$count=count($usertests['tests']);
	$res.='<h3>Пользовательские тесты</h3><br>';
	
	if($count==0)
		$res.='Не найдено среди тестов!<br><br><hr>';
	else
	{	
		$res.='Найдено тестов: <b>'.$count.'</b>';
		
		foreach($usertests['tests'] as $tests)
		{
			$res.='<center><h4><a href="/execute/usertest/id/'.$tests['id'].'">'.$tests['name'].'</a></h4></center><br>
			<i>Описание теста:</i><br>'.$tests['description'].'<br>
			<b>Тест создал: </b>'.$tests['fio'].'<br>
			<b>Страна: </b>'.$tests['country_id'].'<br>
			<b>Город: </b>'.$tests['city_id'].'<br>
			<b>Время на выполнение: </b>'.$tests['time_min'].'<br>
			<b>Вопросов в тесте: </b>'.$tests['quantity'].'<br>
			<b>Дата создания: </b>'.$tests['date'].'<br>
			<b>Оценка: </b>'.getMark($tests['rating']).'<br>
			<b>Средний результат: </b>'.getResult($tests['results'], $tests['count']).'<br>
			<b>Прошедших тест: </b>'.$tests['count'].'<br><br>';
		}
		$res.='<br>';
	}
	
	return $res;
}

//функция выводит организационные тесты по категории
function getSearchOrgTheme($orgtests)
{
	if(!is_array($orgtests))
		return $res.'Ошибка при поиске! Нет такой категории!';
	if(!is_array($orgtests['tests']))
		return $res.'Ошибка при поиске организационных тестов!';
	if(!is_array($orgtests['theme']))
		return $res.'Ошибка при при выборке категории тестов!';
		
		
	$res.='<h4>Название категории: '.$orgtests['theme']['themes'].'</h4><br><b>Описание: </b> '.$orgtests['theme']['description'].'<br>';
		
	//вывод совпадений из организационных тестов
	$count=count($orgtests['tests']);
	$res.='<h3>Организационные тесты</h3><br>';
	
	if($count==0)
		$res.='Не найдено среди тестов!<br><br><hr>';
	else
	{	
		$res.='Найдено тестов: <b>'.$count.'</b>';
		
		foreach($orgtests['tests'] as $tests)
		{
			$res.='<center><h4><a href="/execute/orgtest/id/'.$tests['id'].'">'.$tests['name'].'</a></h4></center><br>
			<i>Описание теста:</i><br>'.$tests['description'].'<br>
			<b>Тест создал: </b>'.$tests['fio'].'<br>
			<b>Страна: </b>'.$tests['country_id'].'<br>
			<b>Город: </b>'.$tests['city_id'].'<br>
			<b>Время на выполнение: </b>'.$tests['time_min'].'<br>
			<b>Вопросов в тесте: </b>'.$tests['quantity'].'<br>
			<b>Дата создания: </b>'.$tests['date'].'<br>
			<b>Оценка: </b>'.getMark($tests['rating']).'<br>
			<b>Средний результат: </b>'.getResult($tests['results'], $tests['count']).'<br>
			<b>Прошедших тест: </b>'.$tests['count'].'<br><br>';
		}
		$res.='<br>';
	}
	
	return $res;
}

//функция выводит форму голосования за статью
function setArticleVote($id)
{
	$res='<div id="testmark"><center>';
	
	//счетчик просмотров
	if(!isset($_SESSION['readart']['id'.$id]))
	{
		Articles::get()->addCountArticles($id);
	}

	//оценка статьи пользователем
	if(!isset($_SESSION['votes']['article'.$id]))
	{
		$res.='<br><b>Оцените прочитанную статью: </b><br><br> 
	<form action="/articles/vote" method="post">
	<input type="hidden" value="'.$id.'" name="id">
	<input title="Очень плохо" type="submit" name="mark" value="1">
	<input title="Плохо" type="submit" name="mark" value="2">
	<input title="Нормально" type="submit" name="mark" value="3">
	<input title="Хорошо" type="submit" name="mark" value="4">
	<input title="Отлично" type="submit" name="mark" value="5">
	</form>';
	
	}
	else 
		$res.='<b>Вы уже оценивали данную статью!</b><br>';
	
	$res.='</center></div>';
	
	return $res;
}

//функция для редактирования стран
function countriesEditForm($countries)
{
	if(!is_array($countries))
		return 'Возникла ошибка при выборке стран!';
	
	$res='Всего стран: <b>'.count($countries).'</b><br><br>';

	$num=1;
	foreach($countries as $country)
	{
		
		$res.='<form action="/authorization/countries" method="post"><label for="name'.$country['id'].'">#'.$num.') Название: </label>
		<input type="text" size="50" maxlength="255" name="name" value="'.$country['name'].'">
		<input type="submit" formaction="/authorization/cities" name="cities" value="Города">
		<input type="hidden" name="country_id" value="'.$country['id'].'">
		<input type="submit" name="edit" value="Сохранить название"></form>';
		
		$num++;
	}
	
	
	$res.='<form action="/authorization/countries" method="post">
	<h4>Добавить страну:</h4><br><br>
	<label for="name">Введите название страны:</label><br>
	<input type="text" name="name" size="70" maxlength="255">
	<input type="submit" name="add" value="Добавить страну">
	</form>';
	
	
	return $res;
}

//функция для редактирования городов
function citiesEditForm($cities, $country_id)
{
	if(!is_array($cities))
		return 'Возникла ошибка при выборке городов!';
	
	$res='Всего городов: <b>'.count($cities).'</b><br>';
	
	$num=1;
	foreach($cities as $city)
	{
		
		$res.='<form action="/authorization/cities" method="post"><label for="name'.$city['id'].'">#'.$num.') Название: </label>
		<input type="text" size="50" maxlength="255" name="name" value="'.$city['name'].'">
		<input type="hidden" name="city_id" value="'.$city['id'].'">
		<input type="submit" name="edit" value="Сохранить название">
		</form>';
		
		$num++;
	}
	
	$res.='<form action="/authorization/cities" method="post">
	<h4>Добавить город:</h4><br><br>
	<label for="name">Введите название города:</label><br>
	<input type="text" name="name" size="70" maxlength="255">
	<input type="hidden" name="country_id" value="'.$country_id.'">
	<input type="submit" name="add" value="Добавить город">
	</form>';
	
	
	return $res;
}

//функция редактирования пользовательских типов
function userTypesEdit($users)
{
	if(!is_array($users))
		return 'Ошибка при выборке типов пользователей';
	
	
	$res='<h4>Типы пользователей</h4><br><br>';
	//типы пользователей
	foreach($users as $type)
	{
		if($type['id']==1)
			$res.='<form action="/users/types" method="post">user <b>id='.$type['id'].'</b><br><label for="name">Название:</label> <input type="text" maxlength="255" name="name" sieze=30 value="'.$type['name'].'"><input type="hidden" name="type_id" value="'.$type['id'].'"><input type="submit" name="save" value="Сохранить название"></form>';
		if($type['id']==2)
			$res.='<form action="/users/types" method="post"> student <b>id='.$type['id'].'</b><br><label for="name">Название:</label> <input type="text" maxlength="255" name="name" sieze=30 value="'.$type['name'].'"><input type="hidden" name="type_id" value="'.$type['id'].'"><input type="submit" name="save" value="Сохранить название"></form>';
		if($type['id']==3)
			$res.='<form action="/users/types" method="post"> pupil <b>id='.$type['id'].'</b><br><label for="name">Название:</label> <input type="text" maxlength="255" name="name" sieze=30 value="'.$type['name'].'"><input type="hidden" name="type_id" value="'.$type['id'].'"><input type="submit" name="save" value="Сохранить название"></form>';
		if($type['id']==4)
			$res.='<form action="/users/types" method="post"> teacher <b>id='.$type['id'].'</b><br><label for="name">Название:</label> <input type="text" maxlength="255" name="name" sieze=30 value="'.$type['name'].'"><input type="hidden" name="type_id" value="'.$type['id'].'"><input type="submit" name="save" value="Сохранить название"></form>';
		if($type['id']==5)
			$res.='<form action="/users/types" method="post"> worker <b>id='.$type['id'].'</b><br><label for="name">Название:</label> <input type="text" maxlength="255" name="name" sieze=30 value="'.$type['name'].'"><input type="hidden" name="type_id" value="'.$type['id'].'"><input type="submit" name="save" value="Сохранить название"></form>';
		if($type['id']==6)
			$res.='<form action="/users/types" method="post"> chief <b>id='.$type['id'].'</b><br><label for="name">Название:</label> <input type="text" maxlength="255" name="name" sieze=30 value="'.$type['name'].'"><input type="hidden" name="type_id" value="'.$type['id'].'"><input type="submit" name="save" value="Сохранить название"></form>';
			
	}
	return $res;
}

//функция для редактирования пользователей системы
function usersEdit($users)
{
	if(!is_array($users))	
		return 'Ошибка при выборке пользователей системы!';
	
	$res='Всего пользователей в системе: <b>'.count($users).'</b><br><br>';
	
	$num=1;
	foreach($users as $user)
	{
			$res.='<form action="/users/edit" method="post">
			<h4>Пользователь № '.$num.' логин: <b>'.$user['login'].'</b> </h4><br><center>
			Страна: <b>'.$user['country'].'</b> <br>
			Город: <b>'.$user['city'].'</b> <br>
			Тип: <b>'.$user['type'].'</b><br>
			email: <b>'.$user['email'].'</b><br>
			Дата регистрации: <b>'.$user['date'].'</b><br>
			Создано пользовательских тестов: <b>'.$user['usertests'].'</b><br>';
			
			if($user['type_id']==4)
				$res.='Создано школьных тестов: <b>'.$user['schooltests'].'</b><br>
			Создано университетских тестов: <b>'.$user['univertests'].'</b><br>';
			elseif($user['type_id']==6)
				$res.='Создано организационных тестов: <b>'.$user['orgtests'].'</b><br>';
			
			$res.='Создано статей: <b>'.$user['articles'].'</b><br>
			<input type="hidden" name="user_id" value="'.$user['id'].'">
			<input type="hidden" name="user_type" value="'.$user['type_id'].'">';
			
			if($user['type_id']==1 and $user['login']!='admin')
			{
			$res.='<input type="submit" title="Будут удалены все заведения, тестируемые, статьи и тесты добавленные данным пользователем!!!" name="delete" value="Удалить пользователя и все созданные им данные">';
			}
			
			$res.='</center><hr>
			</form>
			';
			$num++;
	}
	
	
	return $res;
}

//функция выводит школьные тесты для редактирования!!!!!!!!!!!!!!!!!!!!!!!!!!!!
function schoolTests($content)
{
	if(count($content)!=0)
	{
	$result='Всего создано школьных тестов: <b>'.count($content).'</b><br>
	<h3>Школьные тесты</h3>';

	//выборка тематики школьных тестов
	$schools=Schools::get()->getSchoolIdName($_SESSION['loginfo']['id']);
		
	foreach($content as $item)
	{	
	$result.='<form method="post" action="/authorization/editschooltest"><h4>Тест № '.$item['id'].'</h4><h5>Добавлен: '.$item['date'].'</h5>
	<label for="test_name">Название теста:</label><br>
	<input type="text" name="test_name" maxlength="255" size="100" value="'.$item['name'].'"><br>
	<label for="test_description">Описание теста:</label><br>
	<textarea name="test_description" id="schl_tsts_about'.$item['id'].'" cols="77" rows="5">'.$item['description'].'</textarea><script language="JavaScript">
  generate_wysiwyg("schl_tsts_about'.$item['id'].'");
</script><br>
	<label for="test_time">Время на выполнение теста:</label>
	<input size="4" maxlength="3" type="text" value="'.$item['time_min'].'" name="test_time"> мин.<br>
	<label for="tests">Вопросы теста:</label>
	<input type="submit" name="tests" value="'.$item['quantity'].' +/-"><br>
	<p id="pspoiler4">Дополнительные параметры созданного теста +</p>
	<div id="spoiler4">
	<label for="school" id="school_label">Школа:</label>
	<select name="school" id="school" onchange="getClass()">
	<option selected="selected" value="0">Выберите школу для изменения теста \/</option>';
	
	//выборка школ
	foreach($schools as $k=>$v)
		{
			$result.='<option value="'.$v['id'].'">'.$v['name'].'</option>';
		}
	
	$result.='</select><br>
	
	
	<label id="label_class" for="class">Выберите класс:</label>
	<select id="class" onchange="getPredmet()" name="class"></select><br>
	
	<label id="label_lessonsx" for="lessons">Выберите предмет:</label>
	<select id="lessonsx" name="lesson"></select><br>
	
	
	</div>
	';
	
	$result.='<input type="hidden" name="test_id" value="'.$item['id'].'">
	<center>
	<input type="submit" name="edit" value="Сохранить"><input type="submit" name="delete" value="Удалить"></center><hr>
	</form>';
	
	
	}
	
	return $result.'<br>
	<i>Измененения вступят в силу после нажатия кнопки "Сохранить", для того чтобы удалить тест, нажмиет на кнопку "Удалить", а для редактирования вопросов теста нажмите на кнопку с количеством вопросов в столбце "Вопросы"</i><br><br>';
	}
	else
		return 'Вы не создали ни одного университетского теста!<br><span id="add_test_error"><a href="/create"><< К форме создания тестов</a></span>';
}

?>