<?
// Передаем заголовки, возвращаем вермя
header('Content-type: text/plain; charset=utf-8');
header('Cache-Control: no-store, no-cache');
header('Expires: ' . date('r'));

//выводим все города выбранной страны
if(isset($_GET['country']))
{
	require_once('../models/Registration.php');
	$cities=Registration::getCities($_GET['country']);
	echo '<option value="0">Выберите город \/</option>'.$cities;
}
//выводим все университеты выбранного города
if(isset($_GET['ucity']))
{
	require_once('../models/Universities.php');
	$univers=Universities::get()->getUniverByCity($_GET['ucity']);
	echo '<option value="0">Выберите ВУЗ \/</option>'.$univers;
}
//выводим все факультеты выбранного университета
if(isset($_GET['univer']))
{
	require_once('../models/Universities.php');
	$faculty=Universities::get()->getFacultyByUniver($_GET['univer']);
	echo '<option value="0">Выберите факультет \/</option>'.$faculty;
}
//выводим все курсы и специальности выбранного факультета
if(isset($_GET['faculty']))
{
	require_once('../models/Universities.php');
	$course=Universities::get()->getCoursesByFaculty($_GET['faculty']);
	$spec=Universities::get()->getSpecByFaculty($_GET['faculty']);
	echo '<option value="0">Выберите курс \/</option>'.$course.'</select><br><label id="label_specialty" for="specialty">Выберите специальность студента:</label><select id="specialty" onchange="getGroup()" name="specialty"><option value="0">Выберите специальность \/</option>'.$spec.'</select><br>';
}
//выводим все группы по переданным параметрам спецаильности и курсу
if(isset($_GET['specialty']) and isset($_GET['course']))
{
 	require_once('../models/Universities.php');
	$groups=Universities::get()->getGroupsByParams($_GET['specialty'], $_GET['course']);
	echo '<option value="0">Выберите группу \/</option>'.$groups;
}
//выводим специальности и курсы по переданному параметру идентификатору факультета
if(isset($_GET['fac']))
{
	require_once('../models/Universities.php');
	$result=Universities::get()->getSpecAndCorses($_GET['fac']);
	
	if(is_string($result))
		echo $result;
	else 
		echo 'В базе нет групп или курсов! Добавте сначала их, а потом создайте группу!';
	
}

//выводим список предметов для группы
if(isset($_GET['group']))
{
	require_once('../models/Universities.php');
	$result=Universities::get()->getLessonsByGroup($_GET['group']);
	
	if(is_string($result))
		echo $result;
	
}

//выводим список городов для школы
if(isset($_GET['scity']))
{
	require_once('../models/Schools.php');
	$result=Schools::get()->getSchoolByCity($_GET['scity']);
	
	if(is_string($result))
		echo $result;
	
}

//выводим список классов школы
if(isset($_GET['school']))
{
	require_once('../models/Schools.php');
	$result=Schools::get()->getClassesBySchool($_GET['school']);
	
	if(is_string($result))
		echo $result;
	
}

//выводим список предметов класса
if(isset($_GET['class']))
{
	require_once('../models/Schools.php');
	$result=Schools::get()->getLessonsByClass($_GET['class']);
	
	if(is_string($result))
		echo $result;
	
}

?>