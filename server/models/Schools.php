<?
//модель содержит медоты для работы с данными о школах и их учениках
class Schools
{
	//поле содержащее экземпляр данного класса
	private static $school;
	
	private function __construct(){}
	private function __clone(){}
	private function __wakeup(){}
	private function __sleep(){}

	
	public static function get()
	{
		if(self::$school instanceof self)
			return self::$school;
		else
			return self::$school=new Schools();
	}
	
	private function db()
	{
		require_once('DBconnection.php');
		return DBconnection::getDB();
	}
	
	//метод возвращает идентификатор последний таблицы c производит префиксный инкримент
	private static function getTableId($table)
	{
		/* если тестов нет устанавливаем заначение автоинкримента равным 1, 
		иначе номер теста приравниевается инкременту последного теста из базы */
			
			$db=self::db();
					$select=$db->prepare("SELECT id FROM $table WHERE id=(select max(id) from $table)");
					
			$select->execute();
			$result=$select->fetch(PDO::FETCH_NUM);
			if($select->rowCount()==0)
			{
				$db->exec('SET FOREIGN_KEY_CHEKS=0');
		        $db->exec('ALTER TABLE $table AUTO_INCREMENT=1');
				$db->exec('SET FOREIGN_KEY_CHEKS=1');
				$id=1;
			}
			else
				$id=(int)$result[0]; 
			
		return ++$id;
	}
	
	//метод возвращает школы добааленные пользователем в виде ассоциативного массива
	public function getMySchool($user_id)
	{
		$db=$this->db();
		$user_id=$db->quote($user_id);
		
		$select=$db->prepare("SELECT id, city_id, user_id, name, address, tel, email, site, about, image FROM schools WHERE user_id=$user_id");
		$select->execute();
		
		if($select->errorCode()==00000)
			 return $select->fetchAll(PDO::FETCH_ASSOC);
		else return false;
	}
	
	//метод возвращает массив со списком городов
	public function getCities()
	{
		$db=self::db();
		$query=$db->query('SELECT id, name FROM cities');
		if($query->rowCount()!=0)
		    return $query->fetchAll(PDO::FETCH_ASSOC);
		else return false;
	}
	
	//метод добавляет школу в базу данных
	public function addSchool($name, $about, $image, $site, $email, $tel, $city, $address, $user_id)
	{
		$db=self::db();
		$user_id=$db->quote($user_id);
		$name=$db->quote($name);
		$about=$db->quote($about);
		$image=$db->quote($image);
		$site=$db->quote($site);
		$email=$db->quote($email);
		$tel=$db->quote($tel);
		$city=$db->quote($city);
		$address=$db->quote($address);
		
		$check=self::checkSchool($name, $site, $email, $city);
		$id=self::getTableId('schools');
		
		if($check==true)
		{
			$insert=$db->prepare("INSERT INTO schools(`id`,`city_id`,`user_id`,`name`,`address`,`tel`,`email`,`site`,`about`,`image`) VALUES ('$id', $city, $user_id, $name, $address, $tel, $email, $site, $about, $image)");
			$insert->execute();
			if($insert->errorCode()==00000)
				return true;
			else return false;
		}
		else return false;	
	}
	
	//метод проверки на наличия школы в базе данных, если университета нет в БД возвращается true
	private function checkSchool($name, $site, $email, $city)
	{
		$db=$this->db();
		$select=$db->prepare("SELECT id FROM schools WHERE name=$name AND site=$site AND email=$email AND city_id=$city");
		$select->execute();
		if($select->errorCode()==00000)
		{
			if($select->rowCount()==0)
				return true;
			else return false;
		}
		else return false;
	}
	
	//метод для редактирования школы
	public function editMySchool($school_id, $user_id, $name, $image, $city, $address, $about, $site, $email, $tel)
	{
		$db=$this->db();
		$school_id=$db->quote($school_id);
		$user_id=$db->quote($user_id);
		$name=$db->quote($name);
		$image=$db->quote($image);
		$city=$db->quote($city);
		$address=$db->quote($address);
		$about=$db->quote($about);
		$site=$db->quote($site);
		$email=$db->quote($email);
		$tel=$db->quote($tel);
		
		$update=$db->prepare("UPDATE schools SET name=$name, address=$address, tel=$tel, email=$email, site=$site, about=$about, image=$image, city_id=$city WHERE user_id=$user_id AND id=$school_id");
		$update->execute();
		
		if($update->errorCode()==00000)
			return true;
		else return false;
		
	}
	
	//метод для удаления школы из базы данных и все упомининия о ней
	public function deleteMySchool($school_id, $user_id)
	{
		$db=$this->db();
		$school_id=$db->quote($school_id);
		$user_id=$db->quote($user_id);
		
		$sel_testname=$db->prepare("SELECT id FROM school_test_name WHERE school_id=$school_id");
		$sel_testname->execute();
		$rows=$sel_testname->fetchAll(PDO::FETCH_ASSOC);
		
		foreach($rows as $row)
		{
			$delete_tests.="DELETE FROM school_tests WHERE test_id='".$row['id']."';";
			$delete_results.="DELETE FROM school_results WHERE test_id='".$row['id']."';";
		}
		
		$del_tests=$db->prepare($delete_tests);
		$del_results=$db->prepare($delete_results);
		$del_testname=$db->prepare("DELETE FROM school_test_name WHERE school_id=$school_id");
		
		$sel_classes=$db->prepare("SELECT id FROM school_classes WHERE school_id=$school_id");
		$sel_classes->execute();
		
		$classes=$sel_classes->fetchAll(PDO::FETCH_ASSOC);
		
		foreach($classes as $class)
		{
			$del_lesson.="DELETE FROM school_lessons WHERE class_id='".$class['id']."';";
			$del_pupils.="DELETE FROM pupils WHERE class_id='".$class['id']."';";
		}
	
		$del_lessons=$db->prepare($del_lesson);
		$del_pupil=$db->prepare($del_pupils);
		$del_classes=$db->prepare("DELETE FROM school_classes WHERE school_id=$school_id");
		$del_school=$db->prepare("DELETE FROM schools WHERE id=$school_id");
		
		$del_tests->execute();
		$del_results->execute();
		$del_testname->execute();
		$del_lessons->execute();
		$del_pupil->execute();
		$del_classes->execute();
		$del_school->execute();
		
		$res=true;
		
		if($del_tests->errorCode()!=00000)
			$res='Ошибка при удалении тестовых вопросов! / ';
		if($del_results->errorCode()!=00000)
			$res.='Ошибка при удалении результатов теста! / ';
		if($del_testname->errorCode()!=00000)
			$res.='Ошибка при удалении тестов! / ';
		if($del_lessons->errorCode()!=00000)
			$res.='Ошибка при удалении уроков! / ';
		if($del_pupil->errorCode()!=00000)
			$res.='Ошибка при удалении учеников! / ';
		if($del_classes->errorCode()!=00000)
			$res.='Ошибка при удалении классов! / ';
		if($del_school->errorCode()!=00000)
			$res.='Ошибка при удалении школ! / ';
			
		return $res;
	}
	
	//метод выводит классы школы
	public function getSchoolClasses($school_id)
	{
		$db=$this->db();	
		$school_id=$db->quote($school_id);
		
		$select=$db->prepare("SELECT id, name FROM school_classes WHERE school_id=$school_id");
		$select->execute();
		
		if($select->errorCode()==00000)
			return $select->fetchAll(PDO::FETCH_ASSOC);
		else return false;
		
	}
	
	//метод добавляет новый класс в школу
	public function addNewClass($school_id, $name)
	{
		$db=$this->db();
		$school_id=$db->quote($school_id);
		$name=$db->quote($name);
		$id=self::getTableId('school_classes');
		
		$check=self::checkNewClass($school_id,$name);
		if($check==false)
			return false;
		
		$insert=$db->prepare("INSERT INTO school_classes (`id`, `name`, `school_id`) VALUES ('$id', $name, $school_id)");
		$insert->execute();
		
		if($insert->errorCode()!=00000)
			return false;
		else return true;
		


	}
	
	//метод проверяет наличие аналогичного класса в базу данных
	private function checkNewClass($school_id, $name)
	{
		$db=$this->db();

		$select=$db->prepare("SELECT id FROM school_classes WHERE name=$name AND school_id=$school_id");
		$select->execute();
		
		if($select->errorCode()!=00000)
			return false;
			
		if($select->rowCount()==0)
			return true;
		else return false;
	}
	
	//метод удаляет класс и все сопутствующие ему данные
	public function deleteClass($class_id)
	{
		$db=$this->db();
		$class_id=$db->quote($class_id);
		
		
		$sel_testname=$db->prepare("SELECT id FROM school_test_name WHERE class_id=$class_id");
		$sel_testname->execute();
		$rows=$sel_testname->fetchAll(PDO::FETCH_ASSOC);
		
		foreach($rows as $row)
		{
			$delete_tests.="DELETE FROM school_tests WHERE test_id='".$row['id']."';";
			$delete_results.="DELETE FROM school_results WHERE test_id='".$row['id']."';";
		}
		
		$del_tests=$db->prepare($delete_tests);
		$del_results=$db->prepare($delete_results);
		$del_testname=$db->prepare("DELETE FROM school_test_name WHERE class_id=$class_id");
		
		$sel_classes=$db->prepare("SELECT id FROM school_classes WHERE id=$class_id");
		$sel_classes->execute();
		
		$classes=$sel_classes->fetchAll(PDO::FETCH_ASSOC);
		
		foreach($classes as $class)
		{
			$del_lesson.="DELETE FROM school_lessons WHERE class_id='".$class['id']."';";
			$del_pupils.="DELETE FROM pupils WHERE class_id='".$class['id']."';";
		}
	
		$del_lessons=$db->prepare($del_lesson);
		$del_pupil=$db->prepare($del_pupils);
		$del_classes=$db->prepare("DELETE FROM school_classes WHERE id=$class_id");
		
		$del_tests->execute();
		$del_results->execute();
		$del_testname->execute();
		$del_lessons->execute();
		$del_pupil->execute();
		$del_classes->execute();

		
		$res=true;
		
		if($del_tests->errorCode()!=00000)
			$res='Ошибка при удалении тестовых вопросов! / ';
		if($del_results->errorCode()!=00000)
			$res.='Ошибка при удалении результатов теста! / ';
		if($del_testname->errorCode()!=00000)
			$res.='Ошибка при удалении тестов! / ';
		if($del_lessons->errorCode()!=00000)
			$res.='Ошибка при удалении уроков! / ';
		if($del_pupil->errorCode()!=00000)
			$res.='Ошибка при удалении учеников! / ';
		if($del_classes->errorCode()!=00000)
			$res.='Ошибка при удалении класса! / ';
		
			
		return $res;
		
	}
	
	//метод сохраняет измененную информацию о классе школы
	public function editClass($class_id, $name)
	{
		$db=$this->db();
		$class_id=$db->quote($class_id);
		$name=$db->quote($name);
		
		$update=$db->prepare("UPDATE school_classes SET name=$name WHERE id=$class_id");
		$update->execute();
		
		if($update->errorCode()==00000)
			return true;
		else return false;
		
	}
	
	//метод выводит все предметы данного класса школы
	public function getAllLessons($class_id)
	{
		$db=$this->db();
		$class_id=$db->quote($class_id);
		
		$select=$db->prepare("SELECT id, name, class_id FROM school_lessons WHERE class_id=$class_id");
		$select->execute();
		
		if($select->errorCode()==00000)
			return $select->fetchAll(PDO::FETCH_ASSOC);
		else return false;
	}
	
	//метод добавляет новый предмет к классу
	public function addLesson($class_id, $name)
	{
		$db=$this->db();
		$class_id=$db->quote($class_id);
		$name=$db->quote($name);
		
		$check=self::checkLesson($class_id,$name);
		if($check==false)
			return false;
		
		$id=self::getTableId('school_lessons');
		
		$insert=$db->prepare("INSERT INTO school_lessons(`id`,`name`,`class_id`) VALUES ('$id',$name,$class_id)");
		$insert->execute();
		
		if($insert->errorCode()==00000)
			return true;
		else return false;
	}
	
	//метод проверяет наличие аналогичного предмета в базе данных
	private function checkLesson($class_id, $name)
	{
		$db=$this->db();
		
		$select=$db->prepare("SELECT id FROM school_lessons WHERE class_id=$class_id AND name=$name");
		$select->execute();
		
		if($select->errorCode()==00000)
		{
			if($select->rowCount()==0)
				return true;
			else return false;	
		}
		else return false;
	}
	
	//метод сохраняет измененные данные о предмете класса
	public function editLesson($lesson_id, $name)
	{
		$db=$this->db();	
		$lesson_id=$db->quote($lesson_id);
		$name=$db->quote($name);
		
		$update=$db->prepare("UPDATE school_lessons SET name=$name WHERE id=$lesson_id");
		$update->execute();
		
		if($update->errorCode()==00000)
			return true;
		else return false;
		
	}
	
	//удаление предмета класса
	public function deleteLesson($lesson_id)
	{
		$db=$this->db();
		$lesson_id=$db->quote($lesson_id);
		
		$sel_testname=$db->prepare("SELECT id FROM school_test_name WHERE lesson_id=$lesson_id");
		$sel_testname->execute();
		$rows=$sel_testname->fetchAll(PDO::FETCH_ASSOC);
		
		foreach($rows as $row)
		{
			$delete_tests.="DELETE FROM school_tests WHERE test_id='".$row['id']."';";
			$delete_results.="DELETE FROM school_results WHERE test_id='".$row['id']."';";
		}
		
		$del_tests=$db->prepare($delete_tests);
		$del_results=$db->prepare($delete_results);
		$del_testname=$db->prepare("DELETE FROM school_test_name WHERE lesson_id=$lesson_id");
		$del_lessons=$db->prepare("DELETE FROM school_lessons WHERE id=$lesson_id");
		
		$del_tests->execute();
		$del_results->execute();
		$del_testname->execute();
		$del_lessons->execute();
		
		$res=true;
		
		if($del_tests->errorCode()!=00000)
			$res='Ошибка при удалении тестовых вопросов! / ';
		if($del_results->errorCode()!=00000)
			$res.='Ошибка при удалении результатов теста! / ';
		if($del_testname->errorCode()!=00000)
			$res.='Ошибка при удалении тестов! / ';
		if($del_lessons->errorCode()!=00000)
			$res.='Ошибка при удалении уроков! / ';
	
		return $res;
	}
	
	
	//метод возвращает строки со списком стран
	public function getCountries()
	{
		$db=$this->db();
		$select=$db->prepare("SELECT id, name FROM countries");	
		$select->execute();
		
		if($select->errorCode()!=00000)
			return false;
		
		$countries=$select->fetchAll(PDO::FETCH_ASSOC);
		
		foreach($countries as $country)
		{
			$str.='<option value="'.$country['id'].'">'.$country['name'].'</option>';
		}
		
		return $str;
		
	}
	
	//метод возвращает всех школьников добавленых пользователем
	public function getMyPupils($user_id)
	{
		$db=$this->db();
		$user_id=$db->quote($user_id);
		
		$schools=$db->prepare("SELECT id FROM schools WHERE user_id=$user_id");
		$schools->execute();
		
		if($schools->errorCode()!=00000)
			return false;
		
		$rows=$schools->fetchAll(PDO::FETCH_ASSOC);
		
		$count=count($rows);
		$num=1;
		
		$sel_pupils="SELECT id, city_id, school_id, class_id, fio, address, date, tel, email FROM pupils WHERE ";
		
		foreach($rows as $school)
		{
			if($num!=$count)
				$sel_pupils.="school_id='".$school['id']."' OR ";
			else 
				$sel_pupils.="school_id='".$school['id']."';";
		}
		
		$pupils=$db->prepare($sel_pupils);
		$pupils->execute();
		
		if($pupils->errorCode()!=00000)
			return false;
		else return  $pupils->fetchAll(PDO::FETCH_ASSOC);
		
	}
	
	//метод возвращает строку со списком школ города
	public function getSchoolByCity($city_id)
	{
		$db=$this->db();	
		$city_id=$db->quote($city_id);
		
		$select=$db->prepare("SELECT id, name FROM schools WHERE city_id=$city_id");
		$select->execute();
		
		if($select->errorCode()!=00000)
			return false;
		
		$schools=$select->fetchAll(PDO::FETCH_ASSOC);
		
		
		if($select->rowCount()==0)
			return '<option value="0">В этом городе нет школ!</option>';
		
		foreach($schools as $school)
		{
			$res.='<option value="'.$school['id'].'">'.$school['name'].'</option>';	
		}
		
		return $res;
		
	}
	
	//метод возвращает все классы данной школы
	public function getClassesBySchool($school_id)
	{
		$db=$this->db();
		$school_id=$db->quote($school_id);
		$select=$db->prepare("SELECT id, name FROM school_classes WHERE school_id=$school_id");
		$select->execute();
		
		if($select->errorCode()!=00000)
			return false;
		
		$rows=$select->fetchAll(PDO::FETCH_ASSOC);
		
		foreach($rows as $row)
		{
			$result.='<option value="'.$row['id'].'">'.$row['name'].'</option>';	
		}
		
		return $result;
	}
	
	//метод добавляет нового ученика в базу данных
	public function addNewPupil($fio, $email, $tel, $address, $city, $school, $class)
	{
		$db=$this->db();	
		$fio=$db->quote($fio);
		$email=$db->quote($email);
		$tel=$db->quote($tel);
		$address=$db->quote($address);
		$city=$db->quote($city);
		$school=$db->quote($school);
		$class=$db->quote($class);
		$date=date("Y-m-d");
		
		$id=self::getTableId('pupils');
		
		$insert=$db->prepare("INSERT INTO pupils(`id`, `city_id`, `school_id`, `class_id`, `fio`, `address`, `date`, `tel`, `email`) VALUES('$id', $city, $school, $class, $fio, $address, '$date', $tel, $email)");
		$insert->execute();
		
		if($insert->errorCode()==00000)
			return true;
		else
			return false;

	}
	
	//метод полностью обновляет информацию о школьнике
	public function updatePupil($pupil_id, $fio, $email, $tel, $address, $city, $school, $class)
	{
		$db=$this->db();
		$pupil_id=$db->quote($pupil_id);
		$fio=$db->quote($fio);
		$email=$db->quote($email);
		$tel=$db->quote($tel);
		$address=$db->quote($address);
		$city=$db->quote($city);
		$school=$db->quote($school);
		$class=$db->quote($class);
		
		$update=$db->prepare("UPDATE pupils SET fio=$fio, email=$email, tel=$tel, address=$address, city_id=$city, school_id=$school, class_id=$class WHERE id=$pupil_id");
		$update->execute();
		
		if($update->errorCode()==00000)
			return true;
		else return false;

	}
	
	//метод для частичного обновления информации о школьнике
	public function updatePupilPartly($pupil_id, $fio, $email, $tel, $address)
	{
		$db=$this->db();
		$pupil_id=$db->quote($pupil_id);
		$fio=$db->quote($fio);
		$email=$db->quote($email);
		$tel=$db->quote($tel);
		$address=$db->quote($address);
		
		$update=$db->prepare("UPDATE pupils SET fio=$fio, email=$email, tel=$tel, address=$address WHERE id=$pupil_id");
		$update->execute();
		
		if($update->errorCode()==00000)
			return true;
		else return false;
		
	}
	
	//метод удаляет ученика школы и результаты прохождения им тестов
	public function deletePupil($pupil_id)
	{
		$db=$this->db();
		$pupil_id=$db->quote($pupil_id);
		
		$del_results=$db->prepare("DELETE FROM school_results WHERE pupil_id=$pupil_id");
		$del_results->execute();
		$del_pupil=$db->prepare("DELETE FROM pupils WHERE id=$pupil_id");
		$del_pupil->execute();
		
		if($del_pupil->errorCode()==00000 and $del_results->errorCode()==00000)
			return true;
		else return false;
	}
	
	//метод возвращает строку со списком предметов класса
	public function getLessonsByClass($class_id)
	{
		$db=$this->db();
		$class_id=$db->quote($class_id);
		
		$select=$db->prepare("SELECT id, name FROM school_lessons WHERE class_id=$class_id");
		$select->execute();
		
		if($select->errorCode()!=00000)
			return false;
		
		$rows=$select->fetchAll(PDO::FETCH_ASSOC);
		
		if($select->rowCount()==0)
			return '<option value="0">Нет предметов для данного класса!</option>';
		
		foreach($rows as $row)
		{
			$res.='<option value="'.$row['id'].'">'.$row['name'].'</option>';
		}
		
		return $res;
	}
	
	//метод возвращает имя школы для которой создан тест
	public function getSchoolName($school_id)
	{
		$db=$this->db();
		$school_id=$db->quote($school_id);
		
		$select=$db->prepare("SELECT name FROM schools WHERE id=$school_id LIMIT 1");
		$select->execute();
		
		if($select->errorCode()!=00000)
			return false;
		
		$rows=$select->fetch(PDO::FETCH_NUM);
		if(!empty($rows[0]))
			return 	$rows[0];
		else return false;	
	}
	
	//метод возвращает имя класса для которого создан тест
	public function getClassName($group_id)
	{
		$db=$this->db();
		$group_id=$db->quote($group_id);
		
		$select=$db->prepare("SELECT name FROM school_classes WHERE id=$group_id LIMIT 1");
		$select->execute();
		
		if($select->errorCode()!=00000)
			return false;
		
		$rows=$select->fetch(PDO::FETCH_NUM);
		if(!empty($rows[0]))
			return 	$rows[0];
		else return false;	
	}
	
	//метод возвращает имя класса для которого создан тест
	public function getLessonName($lesson_id)
	{
		$db=$this->db();
		$lesson_id=$db->quote($lesson_id);
		
		$select=$db->prepare("SELECT name FROM school_lessons WHERE id=$lesson_id LIMIT 1");
		$select->execute();
		
		if($select->errorCode()!=00000)
			return false;
		
		$rows=$select->fetch(PDO::FETCH_NUM);
		if(!empty($rows[0]))
			return 	$rows[0];
		else return false;	
	}
	
	//метод возвращает результаты прохождения тестов студентов которые создал пользователь
	public function getTestResult($user_id)
	{
		$db=$this->db();
		
		$results="SELECT id, 
		(SELECT name FROM school_test_name WHERE id=test_id) AS test, 
		(SELECT fio FROM pupils WHERE id=pupil_id) AS pupil, 
		(SELECT name FROM schools WHERE id=school_id) AS school, 
		(SELECT name FROM school_classes WHERE id=class_id) AS clas,
		(SELECT name FROM school_lessons WHERE id=lesson_id) AS lesson,
		result, date, time_min FROM school_results WHERE ";
		
		$sel_school=self::getMySchool($user_id);
		
		if(!is_array($sel_school))
			return false;
		
		$count=count($sel_school);
		$num=1;
		foreach($sel_school as $school)
		{
			if($num!=$count)
				$results.="school_id='".$school['id']."' OR ";
			else
				$results.="school_id='".$school['id']."';";
			$num++;
		}
		
		$results=$db->prepare($results);
		$results->execute();
		
		if($results->errorCode()!=00000)
			return false;
		else
		    return $results->fetchAll(PDO::FETCH_ASSOC);
		 
	}
	
	//метод возвращает информацию о школе ученика
	public function getSchoolByPupil($loginfo)
	{
			
		$db=$this->db();
		$fio=$db->quote($loginfo['fio']);
		$email=$db->quote($loginfo['email']);
		
		$select=$db->prepare("SELECT id, city_id, user_id, name, address, tel, email, site, about, image FROM schools WHERE id=(SELECT school_id FROM pupils WHERE fio=$fio AND email=$email LIMIT 1)");
		$select->execute();
		
		if($select->errorCode()!=00000)
			return false;
		else 
		{
			$school=$select->fetch(PDO::FETCH_ASSOC);
			$school['city_id']=self::getCityName($school['city_id']);
			return $school;
		}
	}
	
	//метод возвращает название города по его идентификатору
	private function getCityName($city_id)
	{
		$db=$this->db();
		$city_id=$db->quote($city_id);
		
		$name=$db->prepare("SELECT name FROM cities WHERE id=$city_id");
		$name->execute();
		
		if($name->errorCode()!=00000)
			return false;
		$rows=$name->fetch(PDO::FETCH_ASSOC);
		return $rows['name'];
	}
	
	//метод возвращает все тесты для данной школы
	public function getSchoolTests($loginfo)
	{
		$db=$this->db();
		$fio=$db->quote($loginfo['fio']);
		$email=$db->quote($loginfo['email']);
		
		$sel_testnames=$db->prepare("SELECT id, name FROM school_test_name WHERE school_id=(SELECT school_id FROM pupils WHERE fio=$fio AND email=$email LIMIT 1)");
		$sel_testnames->execute();
		
		if($sel_testnames->errorCode()!=00000)
			return false;
		else
			return $sel_testnames->fetchAll(PDO::FETCH_ASSOC);
	}
	
	
	//метод возвращает список предметов для класса студента
	public function getPupilLessons($loginfo)
	{
		$db=$this->db();
		$class=self::getPupilClass($loginfo);
		
		if($class==false)
			return false;
			
		$sel_lessons=$db->prepare("SELECT id, class_id, name FROM school_lessons WHERE class_id=$class[id]");
		$sel_lessons->execute();
		
		if($sel_lessons->errorCode()!=00000)
			return false;
		else
			return $sel_lessons->fetchAll(PDO::FETCH_ASSOC);
			
	}
	
	
	//метод возвращает количество учеников в данном классе
	private function getClassCountPupils($class_id)
	{
		$db=$this->db();
		$class_id=$db->quote($class_id);
		
		$select=$db->prepare("SELECT count(id) FROM pupils WHERE class_id=$class_id");
		$select->execute();
		
		if($select->errorCode()==00000)
			return $select->rowCount();
		else return false;
	}
	
	
	//метод возвращает все тесты по предметам по передаваемому параметру массиву с данными о школьнике
	public function getLessonTestsByPupil($loginfo)
	{
		$db=$this->db();
		$lessons=self::getPupilLessons($loginfo);
		
		if($lessons==false)
			return false;
		
		$query="SELECT id, name, description, time_min, quantity, lesson_id FROM school_test_name WHERE ";
		
		$count=count($lessons);
		$num=1;
		
		foreach($lessons as $lesson)
		{
			if($count!=$num)
				$query.="lesson_id='".$lesson['id']."' OR ";
			else
				$query.="lesson_id='".$lesson['id']."';";
				$num++;
		}
		
		$select=$db->prepare($query);
		$select->execute();
		
		if($select->errorCode()!=00000)
			return false;
		else 
			return $select->fetchAll(PDO::FETCH_ASSOC);
	}
	
	//метод возвращает информацию о классе ученика 
	public function getPupilClass($loginfo)
	{
		$db=$this->db();
		$fio=$db->quote($loginfo['fio']);
		$email=$db->quote($loginfo['email']);
		
		$select=$db->prepare("SELECT id, school_id, name FROM school_classes WHERE id=(SELECT school_id FROM pupils WHERE fio=$fio AND email=$email LIMIT 1) LIMIT 1");
		$select->execute();
		
		if($select->errorCode()!=00000)
			return false;
		$class=$select->fetch(PDO::FETCH_ASSOC);
		
		$count=self::getCountCountPupils($class['id']);

		if($count==false)
			return false;
	
		$class['count']=$count;
		
		return $class;
	}
	
	//метод возвращает количество учеников в классе
	private function getCountCountPupils($class_id)
	{
		$db=$this->db();
		$class_id=$db->quote($class_id);
		
		$select=$db->prepare("SELECT count(id) FROM pupils WHERE class_id=$class_id");
		$select->execute();
		
		if($select->errorCode()!=00000)
			return false;
		$rows=$select->fetch(PDO::FETCH_NUM);
		return $rows[0];
		
	}
	
	//метод возвращает все тесты для класса пользователя
	public function getClassTestsByPupil($loginfo)
	{
		$db=$this->db();
		$fio=$db->quote($loginfo['fio']);
		$email=$db->quote($loginfo['email']);
		
		$class=self::getPupilClass($loginfo);
		
		if($class==false)
			return false;
		
		$sel_testname=$db->prepare("SELECT id, name, description, time_min FROM school_test_name WHERE class_id=$class[id]");	
		$sel_testname->execute();
		
		if($sel_testname->errorCode()!=00000)
			return false;
		else
			return $sel_testname->fetchAll(PDO::FETCH_ASSOC);
	}
	
	//метод выводит результат прохождения тестов школьником
	public function getPupilResults($loginfo)
	{
		$db=$this->db();
		$fio=$db->quote($loginfo['fio']);
		$email=$db->quote($loginfo['email']);
		
		$results=$db->prepare("SELECT id,
(SELECT name FROM school_test_name WHERE id=test_id) AS test, 
(SELECT fio FROM pupils WHERE id=pupil_id) AS pupil, 
(SELECT name FROM schools WHERE id=school_id) AS school, 
(SELECT name FROM school_classes WHERE id=class_id) AS class_id, 
(SELECT name FROM school_lessons WHERE id=lesson_id) AS lesson, 
result, date, time_min FROM school_results WHERE pupil_id=(SELECT id FROM pupils WHERE fio=$fio AND email=$email LIMIT 1)");
		$results->execute();
		
		if($results->errorCode()!=00000)
			return false;
		else return $results->fetchAll(PDO::FETCH_ASSOC);
	}
	
	//метод возвращает все школы и созданные для них тесты
	public function getAllSchoolsTests()
	{
		$db=$this->db();
		$select_schools=$db->prepare("SELECT id, (SELECT name FROM cities WHERE id=city_id) AS city, (SELECT fio FROM users WHERE id=user_id) AS user, (SELECT (SELECT name FROM countries WHERE id=country_id)AS country FROM cities WHERE id=city_id)AS country, name, address, about, tel, email, site, image FROM schools");
		$select_tests=$db->prepare("SELECT id, school_id, (SELECT name FROM school_classes WHERE id=class_id)AS class, (SELECT name FROM school_lessons WHERE id=lesson_id)AS lesson, name, description, time_min, rating, date, quantity, results, count, submits FROM school_test_name");	
		$select_schools->execute();
		$select_tests->execute();
		
		if($select_tests->errorCode()==00000 and $select_schools->errorCode()==00000)
		{
			$res['schools']=$select_schools->fetchAll(PDO::FETCH_ASSOC);
			$res['tests']=$select_tests->fetchAll(PDO::FETCH_ASSOC);
			return $res;
		}
		else
			return false;
	}
	
	//метод возвращает массив со школами пользователя
	public function getSchoolIdName($user_id)
	{
		$db=$this->db();
		$user_id=$db->quote($user_id);
		
		$select=$db->prepare("SELECT id, name FROM schools WHERE user_id=$user_id");
		$select->execute();
		
		if($select->errorCode()!=00000)
			return false;
		else
		return $select->fetchAll(PDO::FETCH_ASSOC);
		
	}
	
	//метод обновляет информацию о школьном тесте
	public function editSchoolTestname($user_id, $test_id, $test_name, $test_description, $test_time, $school, $class, $predmet)
	{
		$db=$this->db();
		$user_id=$db->quote($user_id);
		$test_name=$db->quote($test_name);
		$test_description=$db->quote($test_description);
		$test_time=$db->quote($test_time);
		$school=$db->quote($school);
		$class=$db->quote($class);
		$predmet=$db->quote($predmet);
		$test_id=$db->quote($test_id);
		
		$update=$db->prepare("UPDATE school_test_name SET school_id=$school, lesson_id=$predmet, name=$test_name, description=$test_description, class_id=$class, time_min=$test_time WHERE user_id=$user_id AND id=$test_id");
		$update->execute();
		
		if($update->errorCode()!=00000)
			return false;
		else return true;
		
	}
	
	//метод обновляет информацию о школьном тесте частично
	public function editSchoolTestnameParticle($user_id, $test_id, $test_name, $test_description, $test_time)
	{
		$db=$this->db();
		$user_id=$db->quote($user_id);
		$test_name=$db->quote($test_name);
		$test_description=$db->quote($test_description);
		$test_time=$db->quote($test_time);
		$test_id=$db->quote($test_id);
		
		$update=$db->prepare("UPDATE school_test_name SET name=$test_name, description=$test_description, time_min=$test_time WHERE user_id=$user_id AND id=$test_id");
		$update->execute();
		
		if($update->errorCode()!=00000)
			return false;
		else return true;
		
	}
	
	//метод удаляет тест, вопросы теста и результаты его прохождения
	public function deleteSchoolTest($test_id)
	{
		$db=$this->db();
		$test_id=$db->quote($test_id);
		
		$del_tests=$db->prepare("DELETE FROM school_tests WHERE test_id=$test_id");
		$del_results=$db->prepare("DELETE FROM school_results WHERE test_id=$test_id");
		$del_testname=$db->prepare("DELETE FROM school_test_name WHERE id=$test_id");
		
		$del_results->execute();
		$del_tests->execute();
		$del_testname->execute();
		
		if($del_results->errorCode()==00000 and $del_tests->errorCode()==00000 and $del_testname->errorCode()==00000)
			return true;
		else return false;
	}
}

?>