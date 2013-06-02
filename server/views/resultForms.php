<? //функции для вывода результатов тестирования

function getOrgResult(array $content, $test_name, $worker_fio, $org_name)
{
	if(count($content)!=0)
	{
	//выбока имен Ф.И.О. сотрудников
	if(is_array($worker_fio))
	{
		foreach($worker_fio as $fio)
		{
			$workerfio[$fio['id']]=$fio['fio'];
		}	
		
	}
	
	//выбока имен тестов
	if(is_array($test_name))
	{
		foreach($test_name as $name)
		{
			$testname[$name['id']]=$name['name'];
		}	
		
	}
	
	//выборка названий организаций
	if(is_array($org_name))
	{
		foreach($org_name as $name)
		{
			$orgname[$name['id']]=$name['name'];
		}	
	}
	
	$result='<h5>Результаты тестирования сотрудников Ваших организаций:</h5>
	<table cellpadding="2" cellspacing="2" border="1"><tr><th>Тест</th><th>Тестируемый</th><th>Организация</th><th>Результат в %</th><th>Дата прохождения теста</th><th>Затраченное время в мин.</th></tr>
	';
	
	foreach($content as $worker)
	{
		$result.='<tr><td>'.$testname[$worker['test_id']].'</td><td>'.$workerfio[$worker['employee_id']].'</td><td>'.$orgname[$worker['org_id']].'</td><td>'.$worker['result'].'</td><td>'.$worker['date'].'</td><td>'.$worker['time_min'].'</td></tr>';
	}
		
	$result.='</table>';
	}
	else $result='Ваши тесты ещё не проходил ни один сотрудник!';
	
	return $result;
	
	
}


//функция возвращает время за которое тестируемый прошел тест в минутах
function getTimeMin($time_min)
{
	if($time_min==0)
		$res='менее мин.';
	else 
		$res=$time_min.' мин.';
		
	return $res;
}

//функция выводит результаты прохождения всех тестов созданных учителем
function allResults($students, $pupils)
{
	if(is_array($students))
	{
		$result='<h3>Результаты прохождения университетских тестов:</h3>';
		$count=count($students);
		
		if($count==0)
		  $result.='<p>Студенты ещё не прошли ни одного Вашего теста...</p>';
		else
		{
			$result.='<p>Всего студентов прошедших тесты: <b>'.$count.'</b></p>
			<table border="1" cellpadding="2" cellspacing="2">
			<tr><th>Название теста</th><th>Ф.И.О. студента</th><th>Университет</th><th>Специальность</th><th>Курс</th><th>Группа</th><th>Факультет</th><th>Результат в %</th><th>Дата</th><th>Время</th></tr>';
			foreach($students as $student)
			{
				$result.='<tr>
				<td>'.$student['test_id'].'</td>
				<td>'.$student['student_id'].'</td>
				<td>'.$student['univer_id'].'</td>
				<td>'.$student['specialty_id'].'</td>
				<td>'.$student['course_id'].'</td>
				<td>'.$student['group_id'].'</td>
				<td>'.$student['faculty_id'].'</td>
				<td>'.$student['result'].'</td>
				<td>'.$student['date'].'</td>
				<td>'.getTimeMin($student['time_min']).'</td>
				</tr>';
			}
			
			$result.='</table>';
		}
		
	}
	
	if(is_array($pupils))
	{
		$result.='<h3>Результаты прохождения школьных тестов:</h3>';
		$count=count($pupils);
		
		if($count==0)
		  $result.='<p>Ученики ещё не прошли ни одного Вашего теста...</p>';
		else
		{
			$result.='<p>Всего школьников прошедших тесты: <b>'.$count.'</b></p>
			<table border="1" cellpadding="2" cellspacing="2">
			<tr><th>Название теста</th><th>Ф.И.О. школьника</th><th>Школа</th><th>Класс</th><th>Предмет</th><th>Результат в %</th><th>Дата</th><th>Время</th></tr>';
			foreach($pupils as $pupil)
			{
				$result.='<tr>
				<td>'.$pupil['test'].'</td>
				<td>'.$pupil['pupil'].'</td>
				<td>'.$pupil['school'].'</td>
				<td>'.$pupil['clas'].'</td>
				<td>'.$pupil['lesson'].'</td>
				<td>'.$pupil['result'].'</td>
				<td>'.$pupil['date'].'</td>
				<td>'.getTimeMin($pupil['time_min']).'</td>
				</tr>';
			}
			
			$result.='</table>';
		}
		
	}
	
	return $result.'<br><br><br>';
}

//функция возвращает результат прохождения тестов сутенту!!!!!!!!!!!!!!!!!!!!!!
function getStudentResults($myresults)
{
	if(is_array($myresults))
	{
		$res='<h3>'.$_SESSION['loginfo']['fio'].' результаты прохождения тестов:</h3>';
		
		$count=count($myresults);
		if($count==0)
			return '<b>Вы еще не проходили ни одного теста!</b><br><span id="add_test_error"><a href="/universities/grouptests">Пройти тесты созданные для моей группы > ></a></span>';
		
		$res.='Всего тестов пройдено: <b>'.$count.'</b>';
		
		$res.='<table border="1" cellpadding="2" cellspacing="2">
			<tr><th>Название теста</th><th>Ф.И.О. студента</th><th>Университет</th><th>Факультет</th><th>Специальность</th><th>Курс</th><th>Группа</th><th>Результат в %</th><th>Дата</th><th>Время</th></tr>';
			foreach($myresults as $student)
			{
				$res.='<tr>
				<td>'.$student['test'].'</td>
				<td>'.$student['student'].'</td>
				<td>'.$student['univer'].'</td>
				<td>'.$student['faculty'].'</td>
				<td>'.$student['spec'].'</td>
				<td>'.$student['course'].'</td>
				<td>'.$student['group_name'].'</td>
				<td>'.$student['result'].'</td>
				<td>'.$student['date'].'</td>
				<td>'.getTimeMin($student['time_min']).'</td>
				</tr>';
			}
			
			$res.='</table>';
		
		return $res;
	}
	else return '<b>Ошибка при выборки данных о результатах прохождения тестов!</b>';
	
}

//функция выводит результат тестирования школьника
function getPupilResults($mytestsresults)
{
	if(is_array($mytestsresults))
	{
		$res='<h3>'.$_SESSION['loginfo']['fio'].' результаты прохождения тестов:</h3>';
		
		$count=count($mytestsresults);
		if($count==0)
			return '<b>Вы еще не проходили ни одного теста!</b><br><span id="add_test_error"><a href="/schools/classtests">Пройти тесты созданные для моего класса > ></a></span>';
		
		$res.='Всего тестов пройдено: <b>'.$count.'</b>';
		
		$res.='<table border="1" cellpadding="2" cellspacing="2">
			<tr><th>Название теста</th><th>Ф.И.О. ученика</th><th>Школа</th><th>Класс</th><th>Предмет</th><th>Результат в %</th><th>Дата</th><th>Время</th></tr>';
			foreach($mytestsresults as $pupil)
			{
				$res.='<tr>
				<td>'.$pupil['test'].'</td>
				<td>'.$pupil['pupil'].'</td>
				<td>'.$pupil['school'].'</td>
				<td>'.$pupil['class_id'].'</td>
				<td>'.$pupil['lesson'].'</td>
				<td>'.$pupil['result'].'</td>
				<td>'.$pupil['date'].'</td>
				<td>'.getTimeMin($pupil['time_min']).'</td>
				</tr>';
			}
			
			$res.='</table>';
		
		return $res;
	}
	else return '<b>Ошибка при выборки данных о результатах прохождения тестов!</b>';
}

?>
