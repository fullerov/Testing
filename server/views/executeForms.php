<?
//функция выводит список пользовательских тестов
function getUserTests($tests)
	{
		if(!is_array($tests))
		return $res.'Ошибка при при выборке тестов!';
		
	   $str='<br><h2><img title="Для того чтобы пройти тест щелкните на его название в заголовке..." src="http://'.$_SERVER['HTTP_HOST'].'/images/check.gif"><a href="/execute/userall" title="Все пользовательские тесты">Пользовательские тесты:</a></h2>';
			 
	   $str.='<p id="right_count"><b>'.count($tests).'</b> шт.</p>';
	   
	   $str.='<span class="execute_test">';
	   
		//если функция принимает значение типа массив, то выводим тесты, иначе выводим тест ошибки
		if(is_array($tests))
		{
			foreach($tests as $test)
			{
				$str.='<br><h3><a href="/execute/usertest/id/'.$test['id'].'" title="Пройти тест...">'.$test['name'].'</a></h3>
				<p>'.$test['description'].'<p>
				<br><h5> Тема теста: <a href="search/usertest/id/'.$test['theme_id'].'">'.Execute::getUserTestTheme($test['theme_id']).'</a><br>Тест добавил: <a href="mailto:'.Registration::getUserEmail($test['user_id']).'">'.Registration::getUserLogin($test['user_id']).'</a><h6>Оценка теста: '.(($test['rating']==NULL) ? 'нет':$test['rating']).'<br>Прошедших тест: '.$test['count'].'<br>Дата добавления: '.$test['date'].'</h6></h5><br>';
				
			}
			return $str.'</span><hr><br>';
		}
		else{ return $str.'<br></span><hr><br>'.$tests;}
		
		
	}
	
//функция выводит список организационных тестов!!!!!!!!!!!!!!!!!!!!!!!!!!
function getOrgTests($tests)
	{
		if(!is_array($tests))
		return 'Ошибка при при выборке тестов!';
		
		$str='<h2><img title="Для того чтобы пройти тест щелкните на его название в заголовке..."  src="http://'.$_SERVER['HTTP_HOST'].'/images/check.gif"><a href="/execute/orgall" title="Все тесты организаций">Организационные тесты:</a></h2>';
		
		$str.='<p id="right_count"><b>'.count($tests).'</b> шт.</p>';
		
		$str.='<span class="execute_test">';
		//если функция принимает значение типа массив, то выводим тесты, иначе выводим тест ошибки
		if(is_array($tests))
		{
			foreach($tests as $test)
			{
				$str.='<br><h3><a href="/execute/orgtest/id/'.$test['id'].'" title="Пройти тест...">'.$test['name'].'</a></h3>
				<p>'.$test['description'].'<p>
				<br><h5>Тема теста: <a href="search/orgtest/id/'.$test['theme_id'].'">'.Execute::getOrgTestTheme($test['theme_id']).'</a><br>Тест добавил: <a href="mailto:'.Registration::getUserEmail($test['user_id']).'">'.Registration::getUserLogin($test['user_id']).'</a><br>Для организации: '.Organizations::get()->getOrgNameById($test['org_id']).'</h5><h6>Оценка теста: '.(($test['rating']==NULL) ? 'нет':$test['rating']).'<br>Прошедших тест: '.$test['count'].'<br>Дата добавления: '.$test['date'].'</h6><br>';
				
			}
			return $str.'</span><hr><br>';
		}
		else{ return $str.'<br></span><hr><br>'.$tests;}
	}

//функция выводит бланк и тестовыми вопросами	!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
function beginUserTest($tests,$id)
{
	if(is_array($tests))
	{
		$str='Ответьте на следующие вопросы и нажмите на кнопку "Готово"<hr><form action="/execute/result" method="post">';
		for($i=0;$i<count($tests);$i++)
		{
			$num=$i+1;
			$str.= '<h4>'.($num).') Вопрос: </h4><br>
				<p id="question">'.$tests[$i]['question'].'</p>
				';
			
			
			
			//если вариантов ответа нет
			if($tests[1]['var1']==NULL)
			{
				$str.='<h5>Введите ответ:</h5><p class="select_answ"><center><input id="answr" type="text" maxlength="255" size="90" name="q'.$num.'" value=""></center>';
			}
			else
			{
				
				
				
				if($tests[$i]['var1']!=NULL)
				{
					$str.='<h5>Введите ответ:</h5><p class="select_answ"><br>';
					$str.='<input id="answr" type="radio" name="q'.$num.'"><br>';
				}
				else
				{
					$str.='<h5>Выберите вариант ответа:</h5><p class="select_answ">';
					for($j=1;$j<=30;$j++)
					{
				
						if($tests[$i]['var'.$j]!=NULL)
						$str.=''.$j.'.<input id="answr" type="radio" name="q'.$num.'" value="'.$tests[$i]['var'.$j].'">'.$tests[$i]['var'.$j].'<br>';
					}
				}
			}
			$str.='</p><hr></span>';	
			//установка текущего идентификатора вопроса
			$_SESSION['exec']['q'.$num]=$tests[$i]['id'];	
		}
		$str.='<span id="done">
		<input type="hidden" name="time" id="user_time" value="">
		<input type="submit" id="submit" name="submit" value="Готово"></span>
		</form>';
		
		//установка данных о пройденом тесте в сессию
		$_SESSION['exec']['type']='user';
		$_SESSION['exec']['id']=$id;
		$_SESSION['exec']['count']=count($tests);
		return $str;
	}
	else{return $tests;}
}

//функция выводит бланк и тестовыми вопросами
function beginOrgTest($org_id, $tests, $id)
{
	
	if(is_array($tests))
	{
		$str='Ответьте на следующие вопросы и нажмите на кнопку "Готово"<hr><form action="/execute/orgresult" method="post">';
		for($i=0;$i<count($tests);$i++)
		{
			$num=$i+1;
			$str.= '<h4>'.($num).') Вопрос: </h4><br>
				<p id="question">'.$tests[$i]['question'].'</p>';
			
			//если вариантов ответа нет
			if($tests[1]['var1']==NULL)
			{
				$str.='<h5>Введите ответ:</h5><p class="select_answ"><center><input id="answr" type="text" maxlength="255" size="90" name="q'.$num.'" value=""></center>';
			}
			else
			{
				
				$str.='<h5>Выберите вариант ответа:</h5><p class="select_answ">';
				
				for($j=1;$j<=30;$j++)
				{
				
					if($tests[$i]['var'.$j]!=NULL)
					$str.=''.$j.'.<input type="radio" name="q'.$num.'" value="'.$tests[$i]['var'.$j].'">'.$tests[$i]['var'.$j].'<br>';
				}
			}
			$str.='</p><hr></span>';
			
			//установка текущего идентификатора вопроса
			$_SESSION['exec']['q'.$num]=$tests[$i]['id'];	
		}
		$str.='<input type="hidden" name="org_id" value="'.$org_id.'">
			   <input type="hidden" name="test_id" value="'.$id.'">
			   <input type="hidden" name="time" id="user_time" value="">
		<span id="done"><input type="submit" id="submit" name="submit" value="Готово"></span>
		</form>';
		
		//установка данных о пройденом тесте в сессию
		$_SESSION['exec']['type']='org';
		$_SESSION['exec']['id']=$id;
		$_SESSION['exec']['count']=count($tests);
		return $str;
	}
	else{return $tests;}
}

//функция выводит результат прохождения организационного теста!!!!!!!!!!!
function orgTestResult($count, $answers, $result, $org_id, $test_id, $time)
{
	$_SESSION['results']['org_id']=$org_id;
	$_SESSION['results']['test_id']=$test_id;
	$_SESSION['results']['result']=(int)$result;
	(!empty($time))? $time=(int)$time : $time=0;
	$_SESSION['results']['time_min']=$time;
	$_SESSION['results']['date']=date("Y-m-d");
	
	$res='<h5>И Т О Г О : </h5>
	Время затраченое на прохождение теста: <b>'.$time.'</b> мин.<br>
	Всего вопросов: <b>'.$count.'</b> правильных ответов: <b>'.$answers.'</b><br>Результат: <b>'.$result.'%</b><br>Оценка по десятибальной системе: <b>'.(int)($result/10).'</b> по пятибальной системе <b>'.(int)(($result/10)/2).'</b><br><br><center>';
	
	if(!isset($_SESSION['loginfo']))
	{$res.='<h4>Если Вы сотрудник организации '.Organizations::get()->getOrgLinkByTestid($test_id).'</h4><h5>Вам необходимо ввести свои идентификационные данные для добавления результата прохождения теста в базу данных:</h5>
	<form action="/execute/addorgresult" method="post">
	<label for="fio">Введите своё Ф.И.О.:</label><br>
	<input type="text" maxlength="255" size="70" name="fio" value=""><br>
	<label for="address">Введите свой адрес:</label><br>
	<input type="text" maxlength="255" size="70" name="address" value=""><br>
	<label for="tel">Введите свой номер телефона:</label><br>
	<input type="tel" maxlength="255" size="70" name="tel" value=""><br>
	<input type="submit" name="check" value="Готово">
	</form></center><div id="testmark"><center>';
	
	//занесение результата прохождения теста в базу
	if(!isset($_SESSION['exectest']['org'.$test_id]))
	{
		Execute::addCountAndResult($test_id, 'org', $result);
	}

	//оценка теста пользователем
	if(!isset($_SESSION['votes']['otest'.$test_id]))
	{
		$res.='<br><b>Оцените пройденый тест: </b><br><br> 
	<form action="/execute/vote" method="post">
	<input type="hidden" value="'.$test_id.'" name="test_id">
	<input type="hidden" value="org" name="test_type">
	<input type="hidden" name="result" value="'.$result.'">
	<input title="Очень плохо" type="submit" name="mark" value="1">
	<input title="Плохо" type="submit" name="mark" value="2">
	<input title="Нормально" type="submit" name="mark" value="3">
	<input title="Хорошо" type="submit" name="mark" value="4">
	<input title="Отлично" type="submit" name="mark" value="5">
	</form>';
	
	}
	else 
		$res.='<b>Вы уже оценивали данный тест!</b><br>';
	
	$res.='</center></div><h4><a href="/execute">Иначе, проходите другие тесты</a></h4><br><br><br><br>';}
	
	return $res;
	
}

//функция выводит результат прохождения теста сотрудником и сообщение об успешности добавления результата в базу данных
function orgWorkerResult($check, $count, $answers, $result, $org_id, $test_id)
{
	$_SESSION['results']['org_id']=$org_id;
	$_SESSION['results']['test_id']=$test_id;
	$_SESSION['results']['result']=(int)$result;
	$_SESSION['results']['time_min'];
	$_SESSION['results']['date']=date("Y-m-d");
	
	$res='<h5>И Т О Г О : </h5>
	Всего вопросов: <b>'.$count.'</b> правильных ответов: <b>'.$answers.'</b><br>Результат: <b>'.$result.'%</b><br>Оценка по десятибальной системе: <b>'.(int)($result/10).'</b> по пятибальной системе <b>'.(int)(($result/10)/2).'</b><br><br><center>
	<h5>'.$check.'</h5><div id="testmark">';
	
	//занесение результата прохождения теста в базу
	if(!isset($_SESSION['exectest']['org'.$test_id]))
	{
		Execute::addCountAndResult($test_id, 'org', $result);
	}
	
	//оценка теста пользователем
	if(!isset($_SESSION['votes']['otest'.$test_id]))
	{
		$res.='<br><b>Оцените пройденый тест: </b><br><br> 
	<form action="/execute/vote" method="post">
	<input type="hidden" value="'.$test_id.'" name="test_id">
	<input type="hidden" value="org" name="test_type">
	<input type="hidden" name="result" value="'.$result.'">
	<input title="Очень плохо" type="submit" name="mark" value="1">
	<input title="Плохо" type="submit" name="mark" value="2">
	<input title="Нормально" type="submit" name="mark" value="3">
	<input title="Хорошо" type="submit" name="mark" value="4">
	<input title="Отлично" type="submit" name="mark" value="5">
	</form>';
	
	}
	else 
		$res.='<b>Вы уже оценивали данный тест!</b><br>';
	
	$res.='</div><h4><a href="/execute">< < Пройти другие тесты</a></h4><br><br><br><br>';
	
	return $res;
}

//функция выводит результат прохождения пользовательского теста
function userTestResult($test_id, $r_answers, $res, $count, $time)
{
	$result= '<h5>И Т О Г О : </h5>
	Всего вопросов: <b>'.$count.'</b> правильных ответов: <b>'.$r_answers.'</b><br>Результат: <b>'.$res.'%</b><br>Оценка по десятибальной системе: <b>'.(int)($res/10).'</b> по пятибальной системе <b>'.(int)(($res/10)/2).'</b><br>
	Тест пройден за: <b>'.(int)$time.'</b> мин.
	<br><center><br><br>
	<div id="testmark">';
	
	//занесение результата прохождения теста в базу
	if(!isset($_SESSION['exectest']['user'.$test_id]))
	{
		Execute::addCountAndResult($test_id, 'user', $res);
	}
	
	//оценка теста пользователем
	if(!isset($_SESSION['votes']['utest'.$test_id]))
	{
		$result.='<b>Оцените пройденый тест: </b><br><br> 
	<form action="/execute/vote" method="post">
	<input type="hidden" value="'.$test_id.'" name="test_id">
	<input type="hidden" value="user" name="test_type">
	<input type="hidden" name="result" value="'.$res.'">
	<input title="Очень плохо" type="submit" name="mark" value="1">
	<input title="Плохо" type="submit" name="mark" value="2">
	<input title="Нормально" type="submit" name="mark" value="3">
	<input title="Хорошо" type="submit" name="mark" value="4">
	<input title="Отлично" type="submit" name="mark" value="5">
	</form>';
	
	}
	else 
		$result.='<b>Вы уже оценивали данный тест!</b>';
		
	$result.='</div>
	<br><br><br><br>
	<h4><a href="/execute">< < Пройти другие тесты</a></h4><br><br><br><br>';
	
	return $result;
}

//функция выводит список университетских тестоа!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
function getUniverTests($tests)
{
	$str='<h2><img title="Для того чтобы пройти тест щелкните на его название в заголовке..."  src="http://'.$_SERVER['HTTP_HOST'].'/images/check.gif"><a href="/execute/univerall" title="Все тесты университетов">Университетские тесты:</a></h2>';
	
    $str.='<p id="right_count"><b>'.count($tests).'</b> шт.</p>';
	
	$str.='<span class="execute_test">';
		//если функция принимает значение типа массив, то выводим тесты, иначе выводим текст ошибки
		if(is_array($tests))
		{
			foreach($tests as $test)
			{
				$str.='<br><h3><a href="/execute/univertest/id/'.$test['id'].'" title="Пройти тест...">'.$test['name'].'</a></h3>
				<p>'.$test['description'].'<p>
				<br><h5>Курс: '.Universities::get()->getCourseName($test['course_id']).'<br>Группа: '.Universities::get()->getGroupName($test['group_id']).'<br>Предмет: '.Universities::get()->getLessonName($test['lesson_id']).'</a><br>Тест добавил: <a href="mailto:'.Registration::getUserEmail($test['user_id']).'">'.Registration::getUserLogin($test['user_id']).'</a><br>Оценка теста: '.(($test['rating']==NULL) ? 'нет':$test['rating']).'<br>Прошедших тест: '.$test['count'].'</h5><h6>ВУЗ: '.Universities::get()->getUniverName($test['university_id']).'<br>Факультет: '.Universities::get()->getFacultyName($test['faculty_id']).'<br>Специальность: '.Universities::get()->getSpecName($test['specialty_id']).'<br>Дата добавления: '.$test['date'].'</h6><br>';
				
			}
			return $str.'</span><hr><br>';
		}
		else{ return $str.'<br></span><hr><br>'.$tests;}
}

//функция возвращает боанк с тестовыми вопросами
function beginUniverTest($univer_id, $tests, $id)
{
	if(is_array($tests))
	{
		$str='Ответьте на следующие вопросы и нажмите на кнопку "Готово"<hr><form action="/execute/univerresult" method="post">';
		for($i=0;$i<count($tests);$i++)
		{
			$num=$i+1;
			$str.= '<h4>'.($num).') Вопрос: </h4><br>
				<p id="question">'.$tests[$i]['question'].'</p>';
			
			//если вариантов ответа нет
			if($tests[1]['var1']==NULL)
			{
				$str.='<h5>Введите ответ:</h5><p class="select_answ"><center><input id="answr" type="text" maxlength="255" size="90" name="q'.$num.'" value=""></center>';
			}
			else
			{
				
				$str.='<h5>Выберите вариант ответа:</h5><p class="select_answ">';
				
				for($j=1;$j<=30;$j++)
				{
				
				  if($tests[$i]['var'.$j]!=NULL)
					$str.=''.$j.'.<input type="radio" name="q'.$num.'" value="'.$tests[$i]['var'.$j].'">'.$tests[$i]['var'.$j].'<br>';
				}
			}
			$str.='</p><hr></span>';
				
			//установка текущего идентификатора вопроса
			$_SESSION['exec']['q'.$num]=$tests[$i]['id'];	
		}
		$str.='<input type="hidden" name="univer_id" value="'.$univer_id.'">
			   <input type="hidden" name="test_id" value="'.$id.'">
			   <input type="hidden" name="time" id="user_time" value="">
		<span id="done"><input type="submit" id="submit" name="submit" value="Готово"></span>
		</form>';
		
		//установка данных о пройденом тесте в сессию
		$_SESSION['exec']['type']='univer';
		$_SESSION['exec']['id']=$id;
		$_SESSION['exec']['count']=count($tests);
		return $str;
	}
	else{return $tests;}
}


//функция возвращает результат прохождения университетского теста
function univerTestResult($count, $answers, $result, $univer_id, $test_id, $time)
{
	$_SESSION['results']['univer_id']=$univer_id;
	$_SESSION['results']['test_id']=$test_id;
	$_SESSION['results']['result']=(int)$result;
	(!empty($time))? $time=(int)$time : $time=0;
	$_SESSION['results']['time_min']=$time;
	$_SESSION['results']['date']=date("Y-m-d");
	
	$res='<h5>И Т О Г О : </h5>
	Время затраченое на прохождение теста: <b>'.$time.'</b> мин.<br>
	Всего вопросов: <b>'.$count.'</b> правильных ответов: <b>'.$answers.'</b><br>Результат: <b>'.$result.'%</b><br>Оценка по десятибальной системе: <b>'.(int)($result/10).'</b> по пятибальной системе <b>'.(int)(($result/10)/2).'</b><br><br><center>';
	
	if(!isset($_SESSION['loginfo']))
	{$res.='<h4>Если Вы студент ВУЗа: '.Universities::get()->getUniverNameLink($univer_id).'</h4><h5>Вам необходимо ввести свои идентификационные данные для добавления результата прохождения теста в базу данных:</h5>
	<form action="/execute/adduniverresult" method="post">
	<label for="fio">Введите своё Ф.И.О.:</label><br>
	<input type="text" maxlength="255" size="70" name="fio" value=""><br>
	<label for="address">Введите свой адрес:</label><br>
	<input type="text" maxlength="255" size="70" name="address" value=""><br>
	<label for="tel">Введите свой номер телефона:</label><br>
	<input type="tel" maxlength="255" size="70" name="tel" value=""><br>
	<input type="submit" name="check" value="Готово">
	</form><div id="testmark">';
	
	
	//занесение результата прохождения теста в базу
	if(!isset($_SESSION['exectest']['univer'.$test_id]))
	{
		Execute::addCountAndResult($test_id, 'univer', $result);
	}
	
	//оценка теста пользователем
	if(!isset($_SESSION['votes']['untest'.$test_id]))
	{
		$res.='<br><b>Оцените пройденый тест: </b><br><br> 
	<form action="/execute/vote" method="post">
	<input type="hidden" value="'.$test_id.'" name="test_id">
	<input type="hidden" value="univer" name="test_type">
	<input type="hidden" name="result" value="'.$result.'">
	<input title="Очень плохо" type="submit" name="mark" value="1">
	<input title="Плохо" type="submit" name="mark" value="2">
	<input title="Нормально" type="submit" name="mark" value="3">
	<input title="Хорошо" type="submit" name="mark" value="4">
	<input title="Отлично" type="submit" name="mark" value="5">
	</form>';
	
	}
	else 
		$res.='<b>Вы уже оценивали данный тест!</b><br>';
	
	
	$res.='</div></center><h4><a href="/execute">Иначе, проходите другие тесты</a></h4><br><br><br><br>';}
	
	return $res;
}

//функция выводит результат прохождения теста зарегистрированным студентом
function studentTestResult($count, $answers, $result, $univer_id, $test_id, $time, $check)
{
	$_SESSION['results']['univer_id']=$univer_id;
	$_SESSION['results']['test_id']=$test_id;
	$_SESSION['results']['result']=(int)$result;
	(!empty($time))? $time=(int)$time : $time=0;
	$_SESSION['results']['time_min']=$time;
	$_SESSION['results']['date']=date("Y-m-d");
	
	$res='<h5>И Т О Г О : </h5>
	Время затраченое на прохождение теста: <b>'.$time.'</b> мин.<br>
	Всего вопросов: <b>'.$count.'</b> правильных ответов: <b>'.$answers.'</b><br>Результат: <b>'.$result.'%</b><br>Оценка по десятибальной системе: <b>'.(int)($result/10).'</b> по пятибальной системе <b>'.(int)(($result/10)/2).'</b><br><br><center><div id="testmark">';
	
	
	//занесение результата прохождения теста в базу
	if(!isset($_SESSION['exectest']['univer'.$test_id]))
	{
		Execute::addCountAndResult($test_id, 'univer', $result);
	}
	
	//оценка теста пользователем
	if(!isset($_SESSION['votes']['untest'.$test_id]))
	{
		$res.='<br><b>Оцените пройденый тест: </b><br><br> 
	<form action="/execute/vote" method="post">
	<input type="hidden" value="'.$test_id.'" name="test_id">
	<input type="hidden" value="univer" name="test_type">
	<input type="hidden" name="result" value="'.$result.'">
	<input title="Очень плохо" type="submit" name="mark" value="1">
	<input title="Плохо" type="submit" name="mark" value="2">
	<input title="Нормально" type="submit" name="mark" value="3">
	<input title="Хорошо" type="submit" name="mark" value="4">
	<input title="Отлично" type="submit" name="mark" value="5">
	</form>';
	
	}
	else 
		$res.='<b>Вы уже оценивали данный тест!</b><br>';
	
	$res.='</div><h5>'.$check.'</h5>
	<h4><a href="/execute">< < Пройти другие тесты</a></h4><br><br><br><br>';
	
	return $res;
}


//функция возвращает описание школьных тесты для прохождения!!!!!!!!!!!!!!!
function getSchoolTests($tests)
{
	$str='<br><h2><img title="Для того чтобы пройти тест щелкните на его название в заголовке..." src="http://'.$_SERVER['HTTP_HOST'].'/images/check.gif"><a href="/execute/schoolall" title="Все тесты школ">Школьные тесты:</a></h2>';
	
	$str.='<p id="right_count"><b>'.count($tests).'</b> шт.</p>';
	
	$str.='<span class="execute_test">';
	
			 
		//если функция принимает значение типа массив, то выводим тесты, иначе выводим текст ошибки
		if(is_array($tests))
		{
			foreach($tests as $test)
			{
				$str.='<br><h3><a href="/execute/schooltest/id/'.$test['id'].'" title="Пройти тест...">'.$test['name'].'</a></h3>
				<p>'.$test['description'].'<p>
				<br><h5><br>Класс: '.Schools::get()->getClassName($test['class_id']).'<br>Предмет: '.Schools::get()->getLessonName($test['lesson_id']).'</a><br>Тест добавил: <a href="mailto:'.Registration::getUserEmail($test['user_id']).'">'.Registration::getUserLogin($test['user_id']).'</a></h5><h6>Школа: '.Schools::get()->getSchoolName($test['school_id']).'<br>Оценка теста: '.(($test['rating']==NULL) ? 'нет':$test['rating']).'<br>Прошедших тест: '.$test['count'].'<br>Дата добавления: '.$test['date'].'</h6><br>';
				
			}
			return $str.'</span><br><hr><br>';
		}
		else{ return $str.'<br></span><br><hr><br>'.$tests;}
}

//функция возвращает список с вопросами школьного теста!!!!!!!!!!!!!!!
function beginSchoolTest($tests, $school_id, $class_id, $lesson, $id)
{	
	if(is_array($tests))
	{
		$str='Ответьте на следующие вопросы и нажмите на кнопку "Готово"<hr><form action="/execute/schoolresult" method="post">';
		for($i=0;$i<count($tests);$i++)
		{
			$num=$i+1;
			$str.= '<h4>'.($num).') Вопрос: </h4><br>
				<p id="question">'.$tests[$i]['question'].'</p>';
					
			
			//если вариантов ответа нет
			if($tests[1]['var1']==NULL)
			{
				$str.='<h5>Введите ответ:</h5><p class="select_answ"><center><input id="answr" type="text" maxlength="255" size="90" name="q'.$num.'" value=""></center>';
			}
			else
			{
				
				$str.='<h5>Выберите вариант ответа:</h5><p class="select_answ">';
				
				for($j=1;$j<=30;$j++)
				{
				
				 if($tests[$i]['var'.$j]!=NULL)
					$str.=''.$j.'.<input type="radio" name="q'.$num.'" value="'.$tests[$i]['var'.$j].'">'.$tests[$i]['var'.$j].'<br>';
				}
			}
			$str.='</p><hr></span>';
			
			//установка текущего идентификатора вопроса
			$_SESSION['exec']['q'.$num]=$tests[$i]['id'];	
		}
		$str.='<input type="hidden" name="school_id" value="'.$school_id.'">
			   <input type="hidden" name="class_id" value="'.$class_id.'">
			   <input type="hidden" name="lesson" value="'.$lesson.'">
			   <input type="hidden" name="test_id" value="'.$id.'">
			   <input type="hidden" name="time" id="user_time" value="">
		<span id="done"><input type="submit" id="submit" name="submit" value="Готово"></span>
		</form>';
		
		//установка данных о пройденом тесте в сессию
		$_SESSION['exec']['type']='school';
		$_SESSION['exec']['id']=$id;
		$_SESSION['exec']['count']=count($tests);
		return $str;
	}
	else{return $tests;}
		
}

//вывод результата прохождения школьного теста
function schoolTestResult($count, $answers, $result, $school_id, $class_id, $lesson, $test_id, $time)
{

	$_SESSION['results']['school_id']=$school_id;
	$_SESSION['results']['class_id']=$class_id;
	$_SESSION['results']['lesson']=$lesson;
	$_SESSION['results']['test_id']=$test_id;
	$_SESSION['results']['result']=(int)$result;
	(!empty($time))? $time=(int)$time : $time=0;
	$_SESSION['results']['time_min']=$time;
	$_SESSION['results']['date']=date("Y-m-d");
	
	$res='<h5>И Т О Г О : </h5>
	Время затраченое на прохождение теста: <b>'.$time.'</b> мин.<br>
	Всего вопросов: <b>'.$count.'</b> правильных ответов: <b>'.$answers.'</b><br>Результат: <b>'.$result.'%</b><br>Оценка по десятибальной системе: <b>'.(int)($result/10).'</b> по пятибальной системе <b>'.(int)(($result/10)/2).'</b><br><br><center><h5>'.$check.'</h5><div id="testmark">';
	
	
	if(!isset($_SESSION['loginfo']))
	{$res.='<h4>Если Вы ученик школы: '.Schools::get()->getSchoolName($school_id).'</h4><h5>Вам необходимо ввести свои идентификационные данные для добавления результата прохождения теста в базу данных:</h5>
	<form action="/execute/addschoolresult" method="post">
	<label for="fio">Введите своё Ф.И.О.:</label><br>
	<input type="text" maxlength="255" size="70" name="fio" value=""><br>
	<label for="address">Введите свой адрес:</label><br>
	<input type="text" maxlength="255" size="70" name="address" value=""><br>
	<label for="tel">Введите свой номер телефона:</label><br>
	<input type="tel" maxlength="255" size="70" name="tel" value=""><br>
	<input type="submit" name="check" value="Готово">
	</form>';

	}
	
	//занесение результата прохождения теста в базу
	if(!isset($_SESSION['exectest']['school'.$test_id]))
	{
		Execute::addCountAndResult($test_id, 'school', $result);
	}
	
	//оценка теста пользователем
	if(!isset($_SESSION['votes']['stest'.$test_id]))
	{
		$res.='<br><b>Оцените пройденый тест: </b><br><br> 
	<form action="/execute/vote" method="post">
	<input type="hidden" value="'.$test_id.'" name="test_id">
	<input type="hidden" value="school" name="test_type">
	<input type="hidden" name="result" value="'.$result.'">
	<input title="Очень плохо" type="submit" name="mark" value="1">
	<input title="Плохо" type="submit" name="mark" value="2">
	<input title="Нормально" type="submit" name="mark" value="3">
	<input title="Хорошо" type="submit" name="mark" value="4">
	<input title="Отлично" type="submit" name="mark" value="5">
	</form>';
	
	}
	else 
		$res.='<b>Вы уже оценивали данный тест!</b><br>';
	
	
	$res.='</div><h4><a href="/execute">< < Пройти другие тесты</a></h4><br><br><br><br>';
	
	return $res;
}

//функция выводит результат тестирования зарегистртрованного школьника
function pupilTestResult($count, $answers, $result, $school_id, $class_id, $lesson, $test_id, $time, $check)
{
	$_SESSION['results']['school_id']=$school_id;
	$_SESSION['results']['class_id']=$class_id;
	$_SESSION['results']['lesson']=$lesson;
	$_SESSION['results']['test_id']=$test_id;
	$_SESSION['results']['result']=(int)$result;
	(!empty($time))? $time=(int)$time : $time=0;
	$_SESSION['results']['time_min']=$time;
	$_SESSION['results']['date']=date("Y-m-d");
	
	$res='<h5>И Т О Г О : </h5>
	Время затраченое на прохождение теста: <b>'.$time.'</b> мин.<br>
	Всего вопросов: <b>'.$count.'</b> правильных ответов: <b>'.$answers.'</b><br>Результат: <b>'.$result.'%</b><br>Оценка по десятибальной системе: <b>'.(int)($result/10).'</b> по пятибальной системе <b>'.(int)(($result/10)/2).'</b><br><br><center>';
	$res.='<h5>'.$check.'</h5><div id="testmark">';
	
	//занесение результата прохождения теста в базу
	if(!isset($_SESSION['exectest']['school'.$test_id]))
	{
		Execute::addCountAndResult($test_id, 'school', $result);
	}
	
	//оценка теста пользователем
	if(!isset($_SESSION['votes']['stest'.$test_id]))
	{
		$res.='<br><b>Оцените пройденый тест: </b><br><br> 
	<form action="/execute/vote" method="post">
	<input type="hidden" value="'.$test_id.'" name="test_id">
	<input type="hidden" value="school" name="test_type">
	<input type="hidden" name="result" value="'.$result.'">
	<input title="Очень плохо" type="submit" name="mark" value="1">
	<input title="Плохо" type="submit" name="mark" value="2">
	<input title="Нормально" type="submit" name="mark" value="3">
	<input title="Хорошо" type="submit" name="mark" value="4">
	<input title="Отлично" type="submit" name="mark" value="5">
	</form>';
	
	}
	else 
		$res.='<b>Вы уже оценивали данный тест!</b><br>';
	
	$res.='</div><h4><a href="/execute">< < Пройти другие тесты</a></h4><br><br><br><br>';
	
	return $res;
}



?>