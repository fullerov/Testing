<? //класс реализующий изменение и удаление тестов и вопросов
class Edit
{
	private function __construct(){}
	private function __clone(){}
	private function __wakeup(){}
	private function __sleep(){}
	
	//соединение с базой данных
	private static function db()
	{
		require_once('DBconnection.php');
		return DBconnection::getDB();
	}
	
	 //метод возвращает имя таблицы с описанием теста по предаваемому пареметру - типу теста
	 private static function getTestTables($id)
	 {
		 $db=self::db();
		
		 switch($id)
		 {
			 case '1': $table['testname_user']='user_test_name'; $table['tests_user']='user_tests'; break; //пользователь
			 case '4':  																				   //учитель
			 $table['testname_univer']='university_test_name'; $table['tests_univer']='university_tests'; 
			 $table['testname_school']='school_test_name'; $table['tests_school']='school_tests'; 
			 $table['testname_user']='user_test_name'; $table['tests_user']='user_tests'; break;
		
			 case '2': $table['testname_user']='user_test_name'; $table['tests_user']='user_tests'; break;  //студент
			 case '3': $table['testname_user']='user_test_name'; $table['tests_user']='user_tests'; break;  //школьник
			 case '5': 																						
			 $table['testname_user']='user_test_name'; $table['tests_user']='user_tests';					//рабочий
			 
			 case '6': $table['testname_org']='org_test_name';  $table['tests_org']='org_tests';			//начальник
			 		   $table['testname_user']='user_test_name'; $table['tests_user']='user_tests'; break;
			
			 break;
			 default: $table['testname_user']='user_test_name'; $table['tests_user']='user_tests'; break;
		 }
		 return $table;
	 }
	 
	//метод изменяет данные о тесте и сохраняет их в базу
	public static function saveTest($id, $name, $description, $time, $theme, $type)
	{
		$db=self::db();
		$id=$db->quote($id);
		$name=$db->quote($name);
		$description=$db->quote($description);
		$time=$db->quote($time);
		$type=$db->quote($type);
		$theme=$db->quote($theme);
		
		$table=self::getTestTables($type);

		$update=$db->prepare("UPDATE $table[testname_user] SET name=$name, description=$description, theme_id=$theme, time_min=$time WHERE id=$id");
		$update->execute();
		
		if($update->errorCode()==00000)
		{
			return true;	
		}
		else
			return false;
		
	}
	
	//метод сохраняет измененный вопрос теста
	public static function saveQuestion($id, $question, $answer, array $vars, $type)
	{
		$db=self::db();
		$id=$db->quote($id);
		$question=$db->quote($question);
		$answer=$db->quote($answer);
		
		//проверка типа теста и задание соответствующей таблицы с вопросами теста
		$table=self::getQuestionTable($type);
		
		//формирование строки запроса
		$query="UPDATE $table SET question=$question, answer=$answer, ";
		
		for($i=0;$i<count($vars);$i++)
		{
			$num=$i+1;
			
				if($num==count($vars))
					$query.="var".$num."='$vars[$i]' ";
				else
					$query.="var".$num."='$vars[$i]', ";
		}
		
		$query.="WHERE id=$id";

		//подготовка запроса и выполнение
		$update=$db->prepare($query);
		$update->execute();
		
		//возвращение результата в соответствии с ответом сервера
		if($update->errorCode()==00000)
			return true;
		else 
			return false;
		
	}
	
	//метод сохраняет измененный вопрос теста без вариантов ответа
	public static function saveQuestionParticle($id, $question, $answer, $type)
	{
		$db=self::db();
		$id=$db->quote($id);
		$question=$db->quote($question);
		$answer=$db->quote($answer);
		
		//проверка типа теста и задание соответствующей таблицы с вопросами теста
		$table=self::getQuestionTable($type);

		//подготовка запроса и выполнение
		$update=$db->prepare("UPDATE $table SET question=$question, answer=$answer WHERE id=$id");
		$update->execute();
		
		//возвращение результата в соответствии с ответом сервера
		if($update->errorCode()==00000)
			return true;
		else 
			return false;
		
	}
	
	//метод добавляет новый вопрос к тесту
	public static function addQuestion($id, $question, $answer, array $vars, $type)
	{
		$db=self::db();
		$id=$db->quote($id);
		$question=$db->quote($question);
		$answer=$db->quote($answer);
		
		$check=self::checkQuestionExist($id, $question, $answer, $type);
		
		if($check==false)
			return false;
		
		//получение таблицы с вопросами тестов
		$table=self::getQuestionTable($type);
		//формирование строки запроса на добавление вопроса
		$query="INSERT INTO $table (`id`, `test_id`, `question`, `answer`, `time_sec`, ";
		
		for($i=1;$i<=30;$i++)
		{
			if($i==30)
				$query.="`var".$i."` ";
			else 
				$query.="`var".$i."`, ";
		}
		$query.=") VALUES (NULL, $id, $question, $answer, NULL, ";

		for($i=1;$i<=30;$i++)
		{
			if(!empty($vars[$i-1]) and $i!=30)
				$query.="'".$vars[$i-1]."', ";
			elseif(empty($vars[$i-1]) and $i!=30) 
				$query.="NULL, ";
			elseif(!empty($vars[$i-1]) and $i==30)
				$query.="'".$vars[$i-1]."')";
			elseif(empty($vars[$i-1]) and $i==30)
				$query.="NULL)";
		}

		$insert=$db->prepare($query);
		$insert->execute();
		
		//результат добавления теста
		if($insert->errorCode()==00000)
		{
			//выборк таблицы по передаваемому типу
			$testtable=self::getTestnameTable($type);
			//выборка количества вопросов теста
			$select=$db->prepare("SELECT id FROM $table WHERE test_id=$id");
			$select->execute();
		
			if($select->errorCode()==00000)
			{
				$quantity=$select->fetchAll(PDO::FETCH_NUM);
				$count=count($quantity);
				//обновляем информацию о количестве тестов в базе
				$update=self::TestnameUpdateQuantity($id, $testtable, $count);
				//проверка результата обновления
				if($update==true)
				{
					return true;
				}
				else return false;
		
			}
			else return false;
		}
		else return false;
	}
	
	//метод добавляет новый вопрос к тесту!!!!!!!!!!!!!!&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&7
	public static function addQuestionParticle($id, $question, $answer, $type)
	{
		$db=self::db();
		$id=$db->quote($id);
		$question=$db->quote($question);
		$answer=$db->quote($answer);
		$check=self::checkQuestionExist($id, $question, $answer, $type);
		
		if($check==false)
			return false;
		
		//получение таблицы с вопросами тестов
		$table=self::getQuestionTable($type);
		//формирование строки запроса на добавление вопроса
		$query="INSERT INTO $table (`id`, `test_id`, `question`, `answer`, `time_sec`, ";
		
		for($i=1;$i<=30;$i++)
		{
			if($i==30)
				$query.="`var".$i."` ";
			else 
				$query.="`var".$i."`, ";
		}
		$query.=") VALUES (NULL, $id, $question, $answer, NULL, ";

		for($i=1;$i<=30;$i++)
		{
			if($i!=30) 
				$query.="NULL, ";
			if($i==30)
				$query.="NULL)";
		}
		
		$insert=$db->prepare($query);
		$insert->execute();

		//результат добавления теста
		if($insert->errorCode()==00000)
		{
			//выборк таблицы по передаваемому типу
			$testtable=self::getTestnameTable($type);
			//выборка количества вопросов теста
			$select=$db->prepare("SELECT id FROM $table WHERE test_id=$id");
			$select->execute();
		
			if($select->errorCode()==00000)
			{
				$quantity=$select->fetchAll(PDO::FETCH_NUM);
				$count=count($quantity);
				//обновляем информацию о количестве тестов в базе
				$update=self::TestnameUpdateQuantity($id, $testtable, $count);
				//проверка результата обновления
				if($update==true)
				{
					return true;
				}
				else return false;
		
			}
			else return false;
		}
		else return false;
	}
	
	
	//метод возвращает название таблицы с описанием теста по передаваемому параметру
	private static function getTestnameTable($type)
	{
		switch($type)
		{
		case 'user': $table='user_test_name'; break;
		case 'org': $table='org_test_name'; break;
		case 'school': $table='school_test_name'; break;
		case 'univer': $table='university_test_name'; break;
		default: $table='user_test_name'; break;
		}
		return $table;
	}
	
	//метод обновляет информацию о количестве тестов в базе
	private static function TestnameUpdateQuantity($id, $table, $value)
	{
		$db=self::db();
		$newcount=$db->prepare("UPDATE $table SET quantity='$value' WHERE id=$id");
		$newcount->execute();
		if($newcount->errorCode()==00000)
		{
			return true;
		}
		else
			return false;

	}
	
	//метод удаляет вопрос из теста
	public static function deleteQuestion($id, $type, $test_id)
	{
		$db=self::db();
		$id=$db->quote($id);
		$test_id=$db->quote($test_id);
		
		$table_del=self::getQuestionTable($type);
		$table_up=self::getTestnameTable($type);
		
		//формирование запросов на удаление вопроса 
		$delete=$db->prepare("DELETE FROM $table_del WHERE id=$id");
		$delete->execute();
		

		if($delete->errorCode()==00000)
		{
			//выборка количества вопросов теста
			$select=$db->prepare("SELECT id FROM $table_del WHERE test_id=$test_id");
			$select->execute();
		
			if($select->errorCode()==00000)
			{
				$quantity=$select->fetchAll(PDO::FETCH_NUM);
				$count=count($quantity);
				//обновляем информацию о количестве тестов в базе
				$update=self::TestnameUpdateQuantity($test_id, $table_up, $count);
				//проверка результата обновления
				if($update==true)
				{
					return true;
				}
				else return false;
			}
		else return false;
		
		}
		else
			return false;
		
	}
	
	//метод возвращает имя таблицы с вопросами по передаваемому параметру типу теста
	private static function getQuestionTable($type)
	{
		switch($type)
		{
			case 'user': $table='user_tests'; break;
			case 'org': $table='org_tests'; break;
			case 'univer': $table='university_tests'; break;
			case 'school': $table='school_tests'; break;
		}
		return $table;
	}
	
	//метод возвращает вопросы теста по прередаваемому параметру идентификатору теста
	public static function getQuestions($id, $type)
	{
		$db=self::db();
		$id=$db->quote($id);
		
		//проверка типа теста и задание соответствующей таблицы с вопросами теста
	    $table=self::getQuestionTable($type);
	
		$select=$db->prepare("SELECT id, test_id, question, answer, time_sec, var1, var2, var3, var4, var5, var6, var7, var8, var9, var10, var11, var12, var13, var14, var15, var16, var17, var18, var19, var20, var21, var22, var23, var24, var25, var26, var27, var28, var29, var30 FROM $table WHERE test_id=$id");
		$select->execute();
		
		if($select->errorCode()==00000)
			 return $select->fetchAll(PDO::FETCH_ASSOC);
		else return false;
		
	}
	
	//метод удаляет тест и вопросы теста
	public static function deleteTest($id,$type)
	{
		$db=self::db();
		$table=self::getTestTables($type);

		$drop=$db->prepare("DELETE FROM $table[tests_user] WHERE test_id='$id';
							DELETE FROM $table[testname_user] WHERE id='$id'");
		$drop->execute();
		
		if($drop->errorCode()==00000)
			return true;
		else return false;
	}
	
	
	//метод возвращает список тестов пользователя по прередаваемому параметру идентификатору пользователя
	public static function getMyUserTests($session)
	{
		$id=$session['id'];
		$db=self::db();
	
		$testname="SELECT id, user_id, country_id, city_id, theme_id, name, description, time_min, rating, date, quantity, results, count, submits FROM user_test_name WHERE user_id='$id'";

		$query=$db->prepare($testname);
		$query->execute();
		//при успешном завершении сценария возвращаем массив с данными иначе возвращаем false
		if($query->errorCode()==00000)
			return $query->fetchAll(PDO::FETCH_ASSOC);
		else 
			return false;
			
	}
	
	//метод возвращает список организационных тестов пользователя по прередаваемому параметру идентификатору пользователя
	public static function getMyOrgTests($session)
	{
		$id=$session['id'];
		$db=self::db();
	
		$testname="SELECT id, user_id, country_id, city_id, theme_id, org_id, name, description, time_min, rating, date, quantity, results, count, submits FROM org_test_name WHERE user_id='$id'";;

		$query=$db->prepare($testname);
		$query->execute();
		//при успешном завершении сценария возвращаем массив с данными иначе возвращаем false
		if($query->errorCode()==00000)
			return $query->fetchAll(PDO::FETCH_ASSOC);
		else 
			return false;
			
	}
	
	//метод возвращает список университетских тестов пользователя по прередаваемому параметру идентификатору пользователя
	public static function getMyUniverTests($session)
	{
		$id=$session['id'];
		$db=self::db();
	
		$testname="SELECT id, user_id, country_id, city_id, university_id, faculty_id, specialty_id, course_id, group_id, lesson_id, name, description, time_min, rating, date, quantity, results, count, submits FROM university_test_name WHERE user_id='$id'";

		$query=$db->prepare($testname);
		$query->execute();
		//при успешном завершении сценария возвращаем массив с данными иначе возвращаем false
		if($query->errorCode()==00000)
			return $query->fetchAll(PDO::FETCH_ASSOC);
		else 
			return false;
			
	}
	
	//метод возвращает список школьных тестов пользователя по прередаваемому параметру идентификатору пользователя
	public static function getMySchoolTests($session)
	{
		$id=$session['id'];
		$db=self::db();
	
		$testname="SELECT id, user_id, country_id, city_id, school_id, class_id, lesson_id, name, description, time_min, rating, date, quantity, results, count, submits FROM school_test_name WHERE user_id='$id'";

		$query=$db->prepare($testname);
		$query->execute();
		//при успешном завершении сценария возвращаем массив с данными иначе возвращаем false
		if($query->errorCode()==00000)
			return $query->fetchAll(PDO::FETCH_ASSOC);
		else 
			return false;
			
	}
//метод изменяет данные об организационном тесте и сохраняет их в базу
	public static function saveOrgTest($id, $test_org, $name, $description, $time, $theme, $type)
	{
		$db=self::db();
		$id=$db->quote($id);
		$name=$db->quote($name);
		$description=$db->quote($description);
		$time=$db->quote($time);
		$type=$db->quote($type);
		$theme=$db->quote($theme);
		$test_org=$db->quote($test_org);

		$update=$db->prepare("UPDATE org_test_name SET org_id=$test_org, name=$name, description=$description, theme_id=$theme, time_min=$time WHERE id=$id");
		$update->execute();
		
		if($update->errorCode()==00000)
			 return true;	
		else return false;
		
	}
	
	//метод удаляет организационный тест и его вопросы 
	public static function deleteOrgTest($id)
	{
		$db=self::db();
		$drop=$db->prepare("DELETE FROM org_tests WHERE test_id='$id';
							DELETE FROM org_test_name WHERE id='$id'");
		$drop->execute();
		
		if($drop->errorCode()==00000)
			 return true;
		else return false;
	}
	
	//метод проверяет существование аналогичного вопроса в тесте
    private static function checkQuestionExist($id, $question, $answer, $type)
	{
		$db=self::db();
		
		if($type=='univer')
			$select=$db->prepare("SELECT id FROM university_tests WHERE test_id=$id AND question=$question AND answer=$answer");
		elseif($type=='user')
			$select=$db->prepare("SELECT id FROM user_tests WHERE test_id=$id AND question=$question AND answer=$answer");
		elseif($type=='org')
			$select=$db->prepare("SELECT id FROM org_tests WHERE test_id=$id AND question=$question AND answer=$answer");
		elseif($type=='school')
			$select=$db->prepare("SELECT id FROM school_tests WHERE test_id=$id AND question=$question AND answer=$answer");
		else return false;
		
		$select->execute();
		
		if($select->errorCode()!=00000)
			return false;
		
		if($select->rowCount()==0)
			return true;
		else return false;
	}
}
?>