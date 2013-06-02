<?
//функция вывода форм создания теста для соответствующего типа пользователя
function getForm($type)
{
	switch($type)
	{
		case '1': return userForm(); break; //пользователь +
		case '2': return userForm(); break; //студент +
		case '3': return userForm(); break; //школьник +
		case '4': return teacherForm(); break; //учитель -
		case '5': return userForm(); break; //рабочий +
		case '6': return chiefForm(); break; //начальник +
		default: return '<span id="create_guest">Только <a title="Зарегистрироватся" href="/registration">зарегистрировавшиеся</a> пользователи могут создавать тесты!</span>'; break;
	}
	
}

//функция возвращает форму для создания пользовательского теста
function userForm()
	{
		return addUserTest();
	}

//функция выводит форму для создания пользовательского теста
function addUserTest()
{
	//выборка тематики пользовательских тестов
		$types=Create::getUserTestsTypes();
		
		if(is_array($types))
		{
		foreach($types as $k=>$v)
		{
			$test_types.='<option value="'.$v['id'].'">'.$v['name'].'</option>';
		}
		}
		else 
			$test_types='<option selected="selected" value="1">Создайте тему \/</option>';
		
		return '<h3>Создание пользовательского теста</h3>
		<center><form action="/create/usertest" method="post">
		<label for="test_name">Введите название создаваемого теста:</label><br>
		<input type="text" size="64" maxlength="255" name="test_name" value=""><br>
		<input type="hidden" maxlength="255" name="user_id" value="'.$_SESSION['loginfo']['id'].'">
		<input type="hidden" maxlength="255" name="country_id" value="'.$_SESSION['loginfo']['country_id'].'">
		<input type="hidden" maxlength="255" name="city_id" value="'.$_SESSION['loginfo']['city_id'].'">
		<label for="test_description">Введите описание теста:</label><br>
		<textarea name="test_description" rows="7" id="aboutusertest" cols="50"></textarea><script language="JavaScript">
  generate_wysiwyg("aboutusertest");
</script><br>
		<label for="test_question">Введите количество вопросов в тесте:</label>
		<input type="text" size="5" maxlength="3" name="test_question"><br>
		<label for="test_var">Введите количество вариантов ответа на вопрос:</label>
		<input type="text" size="5" maxlength="3" name="test_var"><br>
		<label for="test_min">Максимальное время на выполнения теста в минутах:</label>
		<input type="text" size="5" maxlength="3" name="test_min"><br>
		<label for="test_theme">Выберите тематику теста:</label>
		<select name="test_theme">'.$test_types.'</select><br>
		<label for="test_new_theme"><i>если Вы не нашли соответствующей темы, создайте новую:</i></label><br>
		<input type="text" name="test_new_theme" maxlength="255" size="65"><br>
		<label for="new_theme_descr"><i>введите описание новой темы:</i></label><br>
		<textarea cols="22" rows="2" name="new_theme_descr"></textarea>
		<span id="create_test_button"><br><br>
		<input type="submit" name="submit" value="Создать тест"></span>
		</form></center>
		';	
}

//функция для создания тестов для организаций!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
function chiefForm()
{
	return addOrgTest().'<hr>'.addUserTest();
}

//функция возвращает форму для создания организационного теста
function addOrgTest()
{
	//выборка тематики организационных тестов
		$types=Organizations::get()->getTestsTypes();
		$orgs=Organizations::get()->myOrganiztionsNames($_SESSION['loginfo']['id']);

		if(count($types)!=0)
			foreach($types as $k=>$v)
			{
				$test_types.='<option value="'.$v['id'].'">'.$v['themes'].'</option>';
			}
		else $test_types.='<option value="null">Создайте новую тему!</option>';
		
		if(count($orgs)!=0)
			foreach($orgs as $k=>$org)
			{
			$test_orgs.='<option value="'.$org['id'].'">'.$org['name'].'</option>';
			}
		else return 'Для создания теста Вам необходимо <span id="add_test_error"><a href="/organizations/add">добавить первую организацию >></a></span><br><br><br>';
		
		
		return '<h3>Создание организационного теста</h3>
		<center><form action="/create/orgtest" method="post">
		<label for="test_name">Введите название создаваемого теста:</label><br>
		<input type="text" size="64" maxlength="255" name="test_name" value=""><br>
		<input type="hidden" maxlength="255" name="user_id" value="'.$_SESSION['loginfo']['id'].'">
		<input type="hidden" maxlength="255" name="country_id" value="'.$_SESSION['loginfo']['country_id'].'">
		<input type="hidden" maxlength="255" name="city_id" value="'.$_SESSION['loginfo']['city_id'].'">
		<label for="test_description">Введите описание теста:</label><br>
		<textarea name="test_description" id="aboutorgtests" rows="7" cols="50"></textarea><script language="JavaScript">
  generate_wysiwyg("aboutorgtests");
</script><br>
		<label for="test_questions">Введите количество вопросов в тесте:</label>
		<input type="text" size="5" maxlength="3" name="test_questions"><br>
		<label for="test_var">Введите количество вариантов ответа на вопрос:</label>
		<input type="text" size="5" maxlength="3" name="test_var"><br>
		<label for="test_min">Максимальное время на выполнения теста в минутах:</label>
		<input type="text" size="5" maxlength="3" name="test_min"><br>
		<label for="test_org">Выберите тестируемую организацию:</label>
		<select name="test_org">'.$test_orgs.'</select><br>
		<label for="test_theme">Выберите тематику теста:</label>
		<select name="test_theme">'.$test_types.'</select><br>
		<label for="test_new_theme"><i>если Вы не нашли соответствующей темы, создайте новую:</i></label><br>
		<input type="text" name="test_new_theme" maxlength="255" size="65"><br>
		<label for="new_theme_descr"><i>введите описание новой темы:</i></label><br>
		<textarea cols="22" rows="2" name="new_theme_descr"></textarea>
		<span id="create_test_button"><br><br>
		<input type="submit" name="submit" value="Создать тест"></span>
		</form></center>
		';
}

//функция возвращает формы для создания вопросов к тесту, количество созданных форм определяется передаваемым параметром
function getQuestionsForms($quantity,$var)
{	
	//установка ограничения на количество ответов на вопрос
	if($var>30)
	{$var=30;}
	
	//установка ограничения на количество вопросов
	if($quantity>500)
	{$quantity=500;}
	
	$forms='<form action="http://'.$_SERVER['HTTP_HOST'].'/create/add" method="post">';
	
	for($i=0;$i<$quantity;$i++)
	{
		$forms.='<br><label id="question_label">Введите вопрос №'.($i+1).':</label><br>
				<textarea name="question'.($i+1).'" id="qstnqstn'.($i+1).'" rows="10" cols="60"></textarea><script language="JavaScript">
  generate_wysiwyg("qstnqstn'.($i+1).'");
</script><br>
				<label>Введите ответ на вопрос:</label><br>
				<input type="text" name="answer'.($i+1).'" size="70" maxlength="255"><br>
		';
		
		//тестируемый должне будет ввести вариант ответа
		if($var==1 or $var==0)
		{
			$forms.='<h5>Тестируемый должен будет ввести ответ на вопрос самостоятельно, в полном соответствии с Вашим ответом</h5>';
		}
		//тестируемый должен будет выбрать один из вариантов ответа
		else
		{
			
			$forms.='<label>Введите варианты ответа:</label><br>
				<span id="vars">';
			
			for($j=0;$j<$var;$j++)
			{
					$forms.='<span id="num_answer">'.($j+1).')</span> <input class="answ" type="text" name="q'.($i+1).'var'.($j+1).'" size="60" maxlength="255"><br>';

			}
			
			$forms.='</span>';
		}
				
	}
	
	
	$forms.='<input type="hidden" id="hidden_q" name="quantity" value="">
	<br><input type="button" id="add" name="add_question" value="Добавить еще вопрос"><span id="create_test_button"><br>
	<input type="submit" name="add_test" value="Создать тест">
	</span></form>';
	
	return $forms;
}

//функция возвращает формы для создания вопросов к тесту, количество созданных форм определяется передаваемым параметром!!!!!
function getOrgQuestionsForms($quantity,$var)
{	
	//установка ограничения на количество ответов на вопрос
	if($var>30)
	{$var=30;}
	
	//установка ограничения на количество вопросов
	if($quantity>500)
	{$quantity=500;}
	
	$forms='<form action="http://'.$_SERVER['HTTP_HOST'].'/create/add" method="post">';
	
	for($i=0;$i<$quantity;$i++)
	{
		$forms.='<br><label id="question_label">Введите вопрос №'.($i+1).':</label><br>
				<textarea name="question'.($i+1).'" id="orgqstn'.($i+1).'" rows="10" cols="60"></textarea><script language="JavaScript">
  generate_wysiwyg("orgqstn'.($i+1).'");
</script><br>
				<label>Введите ответ на вопрос:</label><br>
				<input type="text" name="answer'.($i+1).'" size="70" maxlength="255"><br>';
		
		
		//тестируемый должне будет ввести вариант ответа
		if($var==1 or $var==0)
		{
			$forms.='<h5>Тестируемый должен будет ввести ответ на вопрос самостоятельно, в полном соответствии с Вашим ответом</h5>';
		}
		//тестируемый должен будет выбрать один из вариантов ответа
		else
		{
			
			$forms.='<label>Введите варианты ответа:</label><br>
				<span id="vars">';
			
			for($j=0;$j<$var;$j++)
			{
					$forms.='<span id="num_answer">'.($j+1).')</span> <input class="answ" type="text" name="q'.($i+1).'var'.($j+1).'" size="60" maxlength="255"><br>';

			}
			
			$forms.='</span>';
		}
		
			
	}
	
	
	$forms.='<input type="hidden" id="hidden_q" name="quantity" value="'.$var.'">
	<br><input type="button" id="add" name="add_question" value="Добавить еще вопрос"><span id="create_test_button"><br>
	<input type="submit" name="add_test" value="Создать тест">
	</span></form>';
	
	return $forms;
}

//метод для создания новой организации
function createOrganization($user_id, $cities)
{
	
	if(is_array($cities))
	{
		foreach($cities as $city)
		{
			$cts.='<option value="'.$city['id'].'">'.$city['name'].'</option>';
		}
	}
	else $cts='<option>Городов нет в БД!</option>';	
	
	return '<h4>Добавления новой организации</h4><br>
	<p>Для того чтобы добавить свою организацию заполните все нижеследующие поля формы:</p>
	<form action="/organizations/add" method="post">
	<label for="name">Введите название организации:</label><br>
	<input type="text" name="name" size="105" maxlength="255" value=""><br>
	<label for="about">Напишите о Вашей организиции:</label><br>
	<textarea name="about" id="orgabouttxt" cols="80" rows="9" ></textarea><script language="JavaScript">
  generate_wysiwyg("orgabouttxt");
</script><br>
	<label for="image">Введите адрес лейбла/герба Вашей организации:</label><br>
	<input type="text" size="80" maxlength="255" name="image" value="http://'.$_SERVER['HTTP_HOST'].'/css/img/no_img.gif"><br>
	<label for="site">Введите адрес сайта добавляемой организации:</label><br>
	<input type="text" name="site" size="80" maxlength="255" value=""><br>
	<label for="email">Введите адрес электронной почты организации:</label><br>
	<input type="email" size="80" maxlength="255" name="email"><br>
	<label for="tel">Введите телефон организации:</label><br>
	<input type="tel" size="80" maxlength="255" name="tel"><br>
	<label for="city">Выберите город, где расположена организация:</label>
	<select name="city">'.$cts.'</select><br>
	<label for="address">Введите адрес организации:</label><br>
	<input type="text" size="80" maxlength="255" name="address"><br>
	<input type="hidden" name="user_id" value="'.$user_id.'"><br>
	<input type="submit" name="add" value="Добавить организацию">
	</form>';
}

//функция возвращает строку со списком городов
function returnCitiesStr(array $cities)
{
	if(is_array($cities))
	{
		foreach($cities as $city)
		{
			$cts.='<option value="'.$city['id'].'">'.$city['name'].'</option>';
		}
	}
	else $cts='<option>Городов нет в БД!</option>';	
	
	return $cts;
}


//функция выводит форму для добавления нового университета!!!!!!!!!!!!
function addNewUniver($user_id, $cities)
{
	$cts=returnCitiesStr($cities);
	
	return '<h4>Добавления нового университета</h4><br>
	<p>Для того чтобы добавить университет в базу заполните все нижеследующие поля формы:</p>
	<form action="/universities/add" method="post">
	<label for="name">Введите название университета:</label><br>
	<input type="text" name="name" size="105" maxlength="255" value=""><br>
	<label for="about">Опишите добавляемый университет:</label><br>
	<textarea name="about" id="univerabouttxt" cols="80" rows="9" ></textarea><script language="JavaScript">
  generate_wysiwyg("univerabouttxt");
</script><br>
	<label for="image">Введите адрес лейбла/герба университета:</label><br>
	<input type="text" size="80" maxlength="255" name="image" value="http://'.$_SERVER['HTTP_HOST'].'/css/img/no_img.gif"><br>
	<label for="site">Введите адрес сайта добавляемого университета:</label><br>
	<input type="text" name="site" size="80" maxlength="255" value=""><br>
	<label for="email">Введите адрес электронной почты университета:</label><br>
	<input type="email" size="80" maxlength="255" name="email"><br>
	<label for="tel">Введите телефон университета:</label><br>
	<input type="tel" size="80" maxlength="255" name="tel"><br>
	<label for="city">Выберите город, где расположен университет:</label>
	<select name="city">'.$cts.'</select><br>
	<label for="address">Введите адрес университета:</label><br>
	<input type="text" size="80" maxlength="255" name="address"><br>
	<input type="hidden" name="user_id" value="'.$user_id.'"><br>
	
	
	<input type="submit" name="add" value="Добавить университет">
	</form>';	
}

//функция для добавления учительских тестов
function teacherForm()
{
	return addStudentTest().'<hr>'.addSchoolTest().'<hr>'.addUserTest();
}

//функция возвращает форму для создания теста для школьников
function addSchoolTest()
{
	$cities=Organizations::get()->getCities();;
	$cts=returnCitiesStr($cities);
	
	return '<h3>Создание школьного теста</h3>
	<center><form action="/create/pupiltest" method="post">
	<input type="hidden" maxlength="255" name="user_id" value="'.$_SESSION['loginfo']['id'].'">
	<input type="hidden" maxlength="255" name="country_id" value="'.$_SESSION['loginfo']['country_id'].'">
	<label for="name">Введите название создаваемого теста:</label><br>
	<input type="text" name="name" maxlength="255" size="64"><br>
	<label for="about">Введите описание теста:</label><br>
	<textarea name="about" id="schoolabouttxt" cols=50 rows=7></textarea><script language="JavaScript">
  generate_wysiwyg("schoolabouttxt");
</script><br>
	<label for="test_questions">Введите количество вопросов в тесте:</label>
	<input type="text" size="5" maxlength="3" name="test_questions"><br>
	<label for="test_var">Введите количество вариантов ответа на вопрос:</label>
	<input type="text" size="5" maxlength="3" name="test_var"><br>
	<label for="test_min">Максимальное время на выполнения теста в минутах:</label>
	<input type="text" size="5" maxlength="3" name="test_min"><br>
	<label for="city">Выберите город в котором расположена школа:</label>
	<select id="scity" onchange="getSchool()" name="city"><option value="0">Выберите город \/</option>'.$cts.'</select><br>
	<label id="label_school" for="school">Выберите школу:</label>
	<select id="school" onchange="getClass()" name="school"></select><br>
	<label id="label_class" for="class">Выберите класс в котором учится школьник:</label>
	<select id="class" onchange="getLessonsList()" name="class"></select><br>
	<label id="label_lesson" for="lesson">Выберите предмет:</label>
	<select id="lesson" onchange="showButton()" name="lesson"></select><br>
	<span id="create_test_button_appear2"><br><br>
	<input type="submit" name="submit" value="Создать тест"></span>
	</form></center>
	';	
}

//функция возвращает форму для создания теста для студентов
function addStudentTest()
{
	$cities=Organizations::get()->getCities();;
	$cts=returnCitiesStr($cities);
	
	return '<h3>Создание университетского теста</h3>
	<center><form action="/create/studtest" method="post">
	<input type="hidden" maxlength="255" name="user_id" value="'.$_SESSION['loginfo']['id'].'">
	<input type="hidden" maxlength="255" name="country_id" value="'.$_SESSION['loginfo']['country_id'].'">
	<label for="name">Введите название создаваемого теста:</label><br>
	<input type="text" name="name" maxlength="255" size="64"><br>
	<label for="about">Введите описание теста:</label><br>
	<textarea name="about" id="univertst" cols=50 rows=7></textarea><script language="JavaScript">
  generate_wysiwyg("univertst");
</script><br>
	<label for="test_questions">Введите количество вопросов в тесте:</label>
	<input type="text" size="5" maxlength="3" name="test_questions"><br>
	<label for="test_var">Введите количество вариантов ответа на вопрос:</label>
	<input type="text" size="5" maxlength="3" name="test_var"><br>
	<label for="test_min">Максимальное время на выполнения теста в минутах:</label>
	<input type="text" size="5" maxlength="3" name="test_min"><br>
	<label for="city">Выберите город, где расположен ВУЗ:</label>
	<select id="ucity" onchange="getUniver()" name="city"><option value="0">Выберите город \/</option>
	'.$cts.'</select><br>
	<label id="label_univer" for="univer">Выберите ВУЗ:</label>
	<select id="univer" onchange="getFaculty()" name="univer"></select><br>
	<label id="label_faculty" for="faculty">Выберите факультет:</label>
	<select id="faculty" onchange="getCourse()" name="faculty"></select><br>
	<label id="label_course" for="course">Выберите курс:</label>
	<span id="course"><select name="course"></select><br></span>
	<label id="label_group" for="group">Выберите студенческую группу:</label>
	<select id="group" onchange="getLessons()" name="group"></select><br>
	<label id="label_lessons" for="group">Выберите предмет:</label>
	<select id="lessons" onchange="showUniverTestButton()" name="lessons"></select><br>
	<span id="create_test_button_appear"><br><br>
		<input type="submit" name="submit" value="Создать тест"></span>
		</form></center>
	';	
	
}

//функция возвращает форму для добавления вопросов к университетскому тесту
function getStudQuestionForms($quantity,$var)
{	
	//установка ограничения на количество ответов на вопрос
	if($var>30)
	{$var=30;}
	
	//установка ограничения на количество вопросов
	if($quantity>500)
	{$quantity=500;}
	
	$forms='<form action="http://'.$_SERVER['HTTP_HOST'].'/create/add" method="post">';
	
	for($i=0;$i<$quantity;$i++)
	{
		$forms.='<br><label id="question_label">Введите вопрос №'.($i+1).':</label><br>
				<textarea name="question'.($i+1).'" id="studqstn'.($i+1).'" rows="10" cols="60"></textarea><script language="JavaScript">
  generate_wysiwyg("studqstn'.($i+1).'");
</script><br>
				<label>Введите ответ на вопрос:</label><br>
				<input type="text" name="answer'.($i+1).'" size="70" maxlength="255"><br>
				<label>Введите варианты ответа:</label><br>
				<span id="vars">
			
		';
		
			for($j=0;$j<$var;$j++)
			{
					$forms.='<span id="num_answer">'.($j+1).')</span> <input class="answ" type="text" name="q'.($i+1).'var'.($j+1).'" size="60" maxlength="255"><br>';

			}
			
			$forms.='</span>';
			
		
	}
	
	
	$forms.='<input type="hidden" id="hidden_q" name="quantity" value="'.$var.'">
	<br><input type="button" id="add" name="add_question" value="Добавить еще вопрос"><span id="create_test_button"><br>
	<input type="submit" name="add_test" value="Создать тест">
	</span></form>';
	
	return $forms;
	
}

//метод добавляет новую школу
function addSchool($cities, $user_id)
{
	return '
	<form action="/schools/add" method="post">
	<h4>Добавления новой школы</h4><br>
	<p>Для того чтобы добавить школу в базу заполните все нижеследующие поля формы:</p>
	<label for="name">Введите название школы:</label><br>
	<input type="text" name="name" size="105" maxlength="255" value=""><br>
	<label for="about">Введите описание школы:</label><br>
	<textarea name="about" id="addschoolabt" cols="80" rows="9" ></textarea><script language="JavaScript">
  generate_wysiwyg("addschoolabt");
</script><br>
	<label for="image">Введите адрес лейбла/герба школы:</label><br>
	<input type="text" size="80" maxlength="255" name="image" value="http://'.$_SERVER['HTTP_HOST'].'/css/img/no_img.gif"><br>
	<label for="site">Введите адрес сайта школы:</label><br>
	<input type="text" name="site" size="80" maxlength="255" value=""><br>
	<label for="email">Введите адрес электронной почты школы:</label><br>
	<input type="email" size="80" maxlength="255" name="email"><br>
	<label for="tel">Введите телефон школы:</label><br>
	<input type="tel" size="80" maxlength="255" name="tel"><br>
	<label for="city">Выберите город, где расположена школа:</label>
	<select name="city">'.returnCitiesStr($cities).'</select><br>
	<label for="address">Введите адрес школы:</label><br>
	<input type="text" size="80" maxlength="255" name="address"><br>
	<input type="submit" name="add" value="Добавить школу">
	<input type="hidden" name="user_id" value="'.$user_id.'"></form><br>
	';
}
	
	
//вывод формы обратной связи
function feedBack()
{
	return '
	<img title="Написать письмо автору проекта..." src="../../css/img/contacts.jpg"><br>
	<h5>Для отправки сообщения воспользуйтесь нижеследующей формой, заполните все поля!</h5>
	<form action="/about/contacts" method="post">
	<label for="name">Введите своё Ф.И.О.:</label><br>
	<input type="text" maxlength="255" size="100" name="name"><br>
	<label for="email">Введите свой e-mail:</label><br>
	<input type="email" maxlength="255" size="100" name="email"><br>
	<label for="message">Введите cообщение:</label><br>
	<textarea cols="90" rows="12" name="message"></textarea><br>
	<center><label for="captcha">Введите цифры с картинки:</label><br>
	<img src="../server/views/captcha.php" /><br>
	<input type="text" maxlength="255" size="8" name="captcha"><br><br>
	<input type="submit" name="add" value="Отправить сообщение"></center>
	</form>';	
}
	
?>