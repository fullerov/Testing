<? //класс реализующий прохождение тестов
class Execute
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
	
	//метод возвращает массив с пользовательскими тестами из указанного диапазона
	public static function getUserTests($first, $last)
	{
		if ($first==0 || $first ==1) { $first=0; } else { --$first; }
	    ++$last;
		$db=self::db();
		$select=$db->prepare("SELECT `id`, `user_id`, `country_id`, `city_id`, `theme_id`, `name`, `description`, `time_min`, `rating`, `date`, `quantity`, `results`, `count`, `submits` FROM user_test_name WHERE id > $first AND id < $last ORDER BY id DESC");
		$select->execute();
		if($select->rowCount()==0)
		{return false;}
		//проверка на корректность исполнения запроса
		if($select->errorCode()==00000)
		{
			return $select->fetchAll(PDO::FETCH_ASSOC);
		}
		else{return 'Возникла ошибка при выборке данных!<br>Код ошибки: '.$select->errorCode();}
		
	}
	
	//вывод типа пользовательского теста по идентификатору 
	public static function getUserTestTheme($id)
	{
		$db=self::db();
		$select=$db->prepare("SELECT name FROM user_themes WHERE id='$id' LIMIT 1");
		$select->execute();
		
		if($select->errorCode()==00000)
		{
			$res=$select->fetch(PDO::FETCH_ASSOC);
			return $res['name'];
		}
		else
		{return 'Возникла ошибка при выборке темы теста!<br>Код ошибки: '.$select->errorCode();}
		
	}
	
	//вывод типа организационного теста по идентификатору 
	public static function getOrgTestTheme($id)
	{
		$db=self::db();
		$select=$db->prepare("SELECT themes FROM org_themes WHERE id='$id' LIMIT 1");
		$select->execute();
		
		if($select->errorCode()==00000)
		{
			$res=$select->fetch(PDO::FETCH_ASSOC);
			return $res['themes'];
		}
		else
		{return 'Возникла ошибка при выборке темы теста!<br>Код ошибки: '.$select->errorCode();}
		
	}
	
	//метод возвращает массив с данными выбранного пользователем теста
	 public static function getTestById($id, $type)
	 {
		$db=self::db();
		$table=self::getTestsTableByType($type);
		$select=$db->prepare("SELECT id, test_id, question, answer, time_sec, var1, var2, var3, var4, var5, var6, var7, var8, var9, var10, var11, var12, var13, var14, var15, var16, var17, var18, var19, var20, var21, var22, var23, var24, var25, var26, var27, var28, var29, var30 FROM $table WHERE test_id='$id' ORDER BY RAND()");
		$select->execute();
		
		if($select->errorCode()==00000)
		{
			return $select->fetchAll(PDO::FETCH_ASSOC);
		}
		else
		{return 'Возникла ошибка при выборке теста!<br>Код ошибки: '.$select->errorCode();}
		 
	 }
	 
	 //метод возвращает имя и время на выполнения теста!!!!!!!!!!!!!!!!!!!!!!
	 public static function getTestData($id, $type)
	 {
		 $db=self::db();
		 
		 //передача типа теста методу и возврат имени таблицы с описанием теста
		 $table=self::getTestnameTableByType($type);
		 
		 if($type=='univer')
			 $select=$db->prepare("SELECT id, name, time_min, university_id FROM $table WHERE id='$id'");
		 elseif($type=='org')
			 $select=$db->prepare("SELECT id, name, time_min, org_id FROM $table WHERE id='$id'");
		 elseif($type=='school')
		 	 $select=$db->prepare("SELECT id, name, time_min, school_id, class_id, lesson_id FROM $table WHERE id='$id'");
		 else 
		 	 $select=$db->prepare("SELECT id, name, time_min FROM $table WHERE id='$id'");
		 
		 $select->execute();
		 
		 if($select->errorCode()==00000)
		 {
			 $res=$select->fetch(PDO::FETCH_ASSOC);
			 return $res;
		 }
		 else{ return 'Возникла ошибка при выборке данных о тесте теста!<br>Код ошибки: '.$select->errorCode();}
	 }
	 
	 //метод возвращает имя таблицы с впоросами тестов по предаваемому пареметру - типу теста
	 private static function getTestsTableByType($type)
	 {
		 switch($type)
		 {
			 case 'user': $table='user_tests'; break;
			 case 'univer': $table='university_tests'; break;
			 case 'org': $table='org_tests'; break;
			 case 'school': $table='school_tests'; break;
		 }
		 return $table;
	 }
	 
	 //метод возвращает имя таблицы с описанием теста по предаваемому пареметру - типу теста
	 private static function getTestnameTableByType($type)
	 {
		 switch($type)
		 {
			 case 'user': $table='user_test_name'; break;
			 case 'univer': $table='university_test_name'; break;
			 case 'org': $table='org_test_name'; break;
			 case 'school': $table='school_test_name'; break;
		 }
		 return $table;
	 }
	 
	 //метод возвращает результат прохождения теста
	 public static function getResult($exec, $results)
	 {
		$type=$exec['type'];	//тип теста
		$id=$exec['id'];		//идентификатор теста
		$count=$exec['count']; //количество вопросов
		 
		 $db=self::db();
		 //передача типа теста методу и возврат имени таблицы с вопросами теста
		 $table=self::getTestsTableByType($type);
		 
		 $select=$db->prepare("SELECT `id`, `answer` FROM $table WHERE test_id='$id'");
		 $select->execute();
		 $rows=$select->fetchAll(PDO::FETCH_ASSOC);
		 
		 //установка начального количества баллов
		 $res=0;
		 
		 foreach($rows as $row)
		 {
			if($results[$row['id']]==$row['answer'])
			{
				$res++;
			}
		 }

		 //вывод процента правильных ответов
		 $res=100*($res/$count);
		 
		 return $res;
	 }
	 
	//метод возвращает массив с организационными тестами из указанного диапазона!!!!!!!!!!
	public static function getOrgTests($first, $last)
	{
		if ($first==0 || $first ==1) { $first=0; } else { --$first; }
	    ++$last;
		$db=self::db();
		$select=$db->prepare("SELECT `id`, `user_id`, `country_id`, `city_id`, `theme_id`, `org_id`, `name`, `description`, `time_min`, `rating`, `date`, `quantity`, `results`, `count`, `submits` FROM org_test_name WHERE id > $first AND id < $last ORDER BY id DESC");
		$select->execute();
		if($select->rowCount()==0)
		{return false;}
		//проверка на корректность исполнения запроса
		if($select->errorCode()==00000)
		{
			return $select->fetchAll(PDO::FETCH_ASSOC);
		}
		else{return 'Возникла ошибка при выборке данных!<br>Код ошибки: '.$select->errorCode();}
		
	}
	
	//метод записывает результаты прохождения теста пользователем если он есть в списке тестируемых, возвращает строковой результат
	public static function setOrgResult($results, $fio, $address, $tel)
	{
		$db=self::db();
		$fio=$db->quote($fio);
		$address=$db->quote($address);
		$tel=$db->quote($tel);
		$org_id=$db->quote($results['org_id']);
		$test_id=$db->quote($results['test_id']);
		$result=$db->quote($results['result']);
		$time_min=$db->quote($results['time_min']);
		$date=$db->quote($results['date']);
		
		//проверка на наличие пользователя в базе данных
		$check=self::checkListEmployer($fio, $address, $tel, $org_id);
		
		if($check==false)
			return 'Такого сотрудника нет в базе данных!';
		//если сотрудник есть в списке проверяем прошёл ли он уже тест
		if(((int)$check)!=0)
		{
			$employee_id=(int)$check;
			$test=self::checkResultEmployer($employee_id, $test_id, $org_id);
			
			if($test==true)
			   return 'Вы уже проходили этот тест!';
			elseif($test==false)
			{
				$id=self::getTableId('org_results');
				$insert=$db->prepare("INSERT INTO org_results(`id`, `test_id`, `employee_id`, `org_id`, `result`, `date`, `time_min`) VALUES ('$id', $test_id, '$employee_id', $org_id, $result, $date, $time_min)");
				$insert->execute();
				
				if($insert->errorCode()==00000)
					return 'Ваш результат успешно занесен в базу данных!';
				else return 'Возникла ошибка при занесении результата в базу данных!';
			}
			else
				return 'Ошибка при выборке результатов прохождения теста!';
			
		}
		else return 'Ошибка при выборке сотрудника за базы данных!';
	}
	
	//метод проверяет наличие сотрудника в базе данных, если пользователья нет в базе возвращает false иначе id сотрудника
	private static function checkListEmployer($fio, $address, $tel, $org_id)
	{
		$db=self::db();
		
		$select_id=$db->prepare("SELECT id FROM org_employers WHERE fio=$fio AND address=$address AND org_id=$org_id AND tel=$tel");
		$select_id->execute();
		
		if($select_id->errorCode()==00000)
		{
			if($select_id->rowCount()!=0)
			{
				$row=$select_id->fetch(PDO::FETCH_ASSOC);
				return $row['id'];
			}
			else return false;
			
		}
		else return 'error';

	}
	
	//метод проверяет наличие сотрудника в списке прошедших тест, если пользователь прошел тест возвращаем истину иначе ложь
	private static function checkResultEmployer($employee_id, $test_id, $org_id)
	{
		$db=self::db();
		
		$select_id=$db->prepare("SELECT id FROM org_results WHERE test_id=$test_id AND employee_id='$employee_id' AND org_id=$org_id");
		$select_id->execute();
		
		if($select_id->errorCode()==00000)
		{
			if($select_id->rowCount()!=0)
				 return true;
			else return false;
			
		}
		else return 'error';

	}
	
	//метод возвращает университетские тесты
	public static function getUniverTests($first,$last)
	{
		if ($first==0 || $first ==1) { $first=0; } else { --$first; }
	    ++$last;
		$db=self::db();
		$select=$db->prepare("SELECT `id`, `user_id`, `country_id`, `city_id`, `university_id`, `faculty_id`, `specialty_id`, `course_id`, `group_id`, `lesson_id`, `name`, `description`, `time_min`, `rating`, `date`, `quantity`, `results`, `count`, `submits` FROM university_test_name WHERE id > $first AND id < $last ORDER BY id DESC");
		$select->execute();
		if($select->rowCount()==0)
			return false;
		//проверка на корректность исполнения запроса
		if($select->errorCode()==00000)
		{
			return $select->fetchAll(PDO::FETCH_ASSOC);
		}
		else{return 'Возникла ошибка при выборке данных!<br>Код ошибки: '.$select->errorCode();}
	}
	
	//метод добавляет рузельтат прохождения теста студентом при корректности введеных параметоров!!!!!!!!!!!!!
	public static function setUniverResult($results, $fio, $address, $tel)
	{
		$db=self::db();
		$fio=$db->quote($fio);
		$address=$db->quote($address);
		$tel=$db->quote($tel);
		$univer_id=$db->quote($results['univer_id']);
		$test_id=$db->quote($results['test_id']);
		$result=$db->quote($results['result']);
		$time_min=$db->quote($results['time_min']);
		$date=$db->quote($results['date']);
		
		//проверка на наличие пользователя в базе данных
		$check=self::checkListStudents($fio, $address, $tel, $univer_id);
		
		if($check==false)
			return 'Такого студента нет в базе данных!';

		//если студент есть в списке проверяем прошёл ли он уже тест
		if(is_array($check))
		{
			$student_id=$check['id'];
			$test=self::checkResultStudent($student_id, $test_id, $univer_id);
			
			if($test==true)
			   return 'Вы уже проходили этот тест!';
			elseif($test==false)
			{
				$id=self::getTableId('university_results');
				$insert=$db->prepare("INSERT INTO university_results(`id`, `test_id`, `student_id`, `univer_id`, `specialty_id`, `course_id`, `group_id`, `faculty_id`, `result`, `date`, `time_min`) VALUES ('$id', $test_id, '$check[id]', '$check[university_id]', '$check[specialty_id]', '$check[course_id]', '$check[group_id]', '$check[faculty_id]', $result, $date, $time_min)");
				$insert->execute();
				
				if($insert->errorCode()==00000)
					return 'Ваш результат успешно занесен в базу данных!';
				else return 'Возникла ошибка при занесении результата в базу данных!';
			}
			else
				return 'Ошибка при выборке результатов прохождения теста!';
			
		}
		else return 'Ошибка при выборке сотрудника за базы данных!';
	}
	
	//метод проверяет наличие студента в базе данных конкретного ВУЗа
 	private static function checkListStudents($fio, $address, $tel, $univer_id)
	{
		$db=self::db();
		
		$select_id=$db->prepare("SELECT id, university_id, group_id, course_id, specialty_id, faculty_id FROM students WHERE fio=$fio AND address=$address AND university_id=$univer_id AND tel=$tel LIMIT 1");
		$select_id->execute();
		
		if($select_id->errorCode()==00000)
		{
			if($select_id->rowCount()!=0)
				return $select_id->fetch(PDO::FETCH_ASSOC);
			else return false;
			
		}
		else return 'error';
	}
	
	//метод проверяет проходил ли студент данный тест
	private static function checkResultStudent($student_id, $test_id, $univer_id)
	{
		$db=self::db();
		
		$select_id=$db->prepare("SELECT id FROM university_results WHERE test_id=$test_id AND student_id='$student_id' AND univer_id=$univer_id");
		$select_id->execute();
		
		if($select_id->errorCode()==00000)
		{
			if($select_id->rowCount()!=0)
				 return true;
			else return false;
			
		}
		else return 'error';
	}
	
	//метод возвращает массив со школьными тестами по заданным критериям
	public static function getSchoolTests($first, $last)
	{
		if ($first==0 || $first ==1) { $first=0; } else { --$first; }
	    ++$last;
		$db=self::db();
		$select=$db->prepare("SELECT `id`, `user_id`, `country_id`, `city_id`, `school_id`, `class_id`, `lesson_id`, `name`, `description`, `time_min`, `rating`, `date`, `quantity`, `results`, `count`, `submits` FROM school_test_name WHERE id > $first AND id < $last ORDER BY id DESC");
		$select->execute();
		if($select->rowCount()==0)
		{return false;}
		//проверка на корректность исполнения запроса
		if($select->errorCode()==00000)
		{
			return $select->fetchAll(PDO::FETCH_ASSOC);
		}
		else{return 'Возникла ошибка при выборке данных!<br>Код ошибки: '.$select->errorCode();}
		
	}
	
	//метод сохраняет результат прохождения теста школьником
	public static function setSchoolResult($results, $fio, $address, $tel)
	{
		$db=self::db();
		$fio=$db->quote($fio);
		$address=$db->quote($address);
		$tel=$db->quote($tel);
		$school_id=$db->quote($results['school_id']);
		$class_id=$db->quote($results['class_id']);
		$lesson=$db->quote($results['lesson']);
		$test_id=$db->quote($results['test_id']);
		$result=$db->quote($results['result']);
		$time_min=$db->quote($results['time_min']);
		$date=$db->quote($results['date']);
		
		//проверка на наличие пользователя в базе данных
		$check=self::checkListPupil($fio, $address, $tel, $school_id);
		
		if($check==false)
			return 'Такого ученика нет в базе данных!';
		//если школьник есть в списке проверяем прошёл ли он уже тест
		if(((int)$check)!=0)
		{
			$pupil_id=(int)$check;
			$test=self::checkResultPupil($pupil_id, $test_id, $school_id);
			
			if($test==false)
			   return 'Вы уже проходили этот тест!';
			elseif($test==true)
			{
				$id=self::getTableId('school_results');
				$insert=$db->prepare("INSERT INTO school_results(`id`, `test_id`, `pupil_id`, `school_id`, `class_id`, `lesson_id`, `result`, `date`, `time_min`) VALUES ('$id', $test_id, '$pupil_id', $school_id, $class_id, $lesson, $result, $date, $time_min)");
				$insert->execute();
				if($insert->errorCode()==00000)
					return 'Ваш результат успешно занесен в базу данных!';
				else return 'Возникла ошибка при занесении результата в базу данных!';
			}
			else
				return 'Ошибка при выборке результатов прохождения теста!';
			
		}
		else return 'Ошибка при выборке сотрудника за базы данных!';
	}
	
	//метод проверяет наличие ученика в базе данных
	public static function checkListPupil($fio, $address, $tel, $school_id)
	{
		$db=self::db();
		
		$select=$db->prepare("SELECT id FROM pupils WHERE fio=$fio AND address=$address AND tel=$tel AND school_id=$school_id");
		$select->execute();
		$rows=$select->fetch(PDO::FETCH_NUM);
		
		if($select->rowCount()==0)
			return false;
		else return $rows[0];
	}
	
	//метод прверяет проходил ли пользователь данный тест
	public static function checkResultPupil($pupil_id, $test_id, $school_id)
    {
		$db=self::db();
		
		$select=$db->prepare("SELECT id FROM school_results WHERE pupil_id=$pupil_id AND test_id=$test_id AND school_id=$school_id");
		$select->execute();
		
		if($select->rowCount()==0)
			return true;
		else return false;
		
	}
	
	//метод добавляет результат оценки теста его участником
	public static function setTestVote($test_id, $test_type, $mark)
	{
		$db=self::db();
		$result=$db->quote($result);
		
		//обновление рейтинга пользовательского теста
		if($test_type=='user')
		{
			$_SESSION['votes']['utest'.$test_id]=true;
			$test_id=$db->quote($test_id);
			$select=$db->prepare("SELECT rating, submits FROM user_test_name WHERE id=$test_id");
			$select->execute();
			
			if($select->errorCode()!=00000)
				return false;
			
			$rows=$select->fetch(PDO::FETCH_ASSOC);
			$rows['submits']=(int)$rows['submits']+1;
			$rows['rating']=((int)$rows['rating']+(int)$mark)/(int)$rows['submits'];
		
			$update="UPDATE user_test_name SET submits='$rows[submits]', rating='$rows[rating]' WHERE id=$test_id";
		}
		//обновление рейтинга организационного теста
		elseif($test_type=='org')
		{
			$_SESSION['votes']['otest'.$test_id]=true;
			$test_id=$db->quote($test_id);
			$select=$db->prepare("SELECT rating, submits FROM org_test_name WHERE id=$test_id");
			$select->execute();
			
			if($select->errorCode()!=00000)
				return false;
			
			$rows=$select->fetch(PDO::FETCH_ASSOC);
			$rows['submits']=(int)$rows['submits']+1;
			$rows['rating']=((int)$rows['rating']+(int)$mark)/(int)$rows['submits'];
			
			$update="UPDATE org_test_name SET submits='$rows[submits]', rating='$rows[rating]' WHERE id=$test_id";
		}
		//обновление рейтинга университетского теста
		elseif($test_type=='univer')
		{
			$_SESSION['votes']['untest'.$test_id]=true;
			$test_id=$db->quote($test_id);
			$select=$db->prepare("SELECT rating, submits FROM university_test_name WHERE id=$test_id");
			$select->execute();
			
			if($select->errorCode()!=00000)
				return false;
			
			$rows=$select->fetch(PDO::FETCH_ASSOC);
			$rows['submits']=(int)$rows['submits']+1;
			$rows['rating']=((int)$rows['rating']+(int)$mark)/(int)$rows['submits'];
		
			$update="UPDATE university_test_name SET submits='$rows[submits]', rating='$rows[rating]' WHERE id=$test_id";
		}
		//обновление рейтинга школьного теста
		elseif($test_type=='school')
		{
			$_SESSION['votes']['stest'.$test_id]=true;
			$test_id=$db->quote($test_id);
			$select=$db->prepare("SELECT rating, submits FROM school_test_name WHERE id=$test_id");
			$select->execute();
			
			if($select->errorCode()!=00000)
				return false;
			
			$rows=$select->fetch(PDO::FETCH_ASSOC);
			$rows['submits']=(int)$rows['submits']+1;
			$rows['rating']=((int)$rows['rating']+(int)$mark)/(int)$rows['submits'];
		
			$update="UPDATE school_test_name SET submits='$rows[submits]', rating='$rows[rating]' WHERE id=$test_id";
		}
		
		$set=$db->prepare($update);
		$set->execute();
		
		
		
		if($set->errorCode()==00000)
			return true;
		else return false;
	}
	
	//метод заносит средний результат прохождения теста и задает количество прошедших тест
	static public function addCountAndResult($test_id, $type, $result)
	{
		$db=self::db();
		
		if($type=='user')
		{
			$_SESSION['exectest']['user'.$test_id]=true;
			$test_id=$db->quote($test_id);
			$select=$db->prepare("SELECT results, count FROM user_test_name WHERE id=$test_id");
			$select->execute();
			
			if($select->errorCode()!=00000)
				return false;
			
			$rows=$select->fetch(PDO::FETCH_ASSOC);
			$rows['count']=(int)$rows['count']+1;
			$rows['results']=((int)$rows['results']+(int)$result)/$rows['count'];
			
			$update=$db->prepare("UPDATE user_test_name SET results=$rows[results], count=$rows[count] WHERE id=$test_id;");	
		}	
		elseif($type=='org')
		{
			$_SESSION['exectest']['org'.$test_id]=true;
			$test_id=$db->quote($test_id);
			$select=$db->prepare("SELECT results, count FROM org_test_name WHERE id=$test_id");
			$select->execute();
			
			if($select->errorCode()!=00000)
				return false;
			
			$rows=$select->fetch(PDO::FETCH_ASSOC);
			$rows['count']=(int)$rows['count']+1;
			$rows['results']=((int)$rows['results']+(int)$result)/$rows['count'];
			
			$update=$db->prepare("UPDATE org_test_name SET results=$rows[results], count=$rows[count] WHERE id=$test_id;");	
		}
		elseif($type=='univer')
		{
			$_SESSION['exectest']['univer'.$test_id]=true;
			$test_id=$db->quote($test_id);
			$select=$db->prepare("SELECT results, count FROM university_test_name WHERE id=$test_id");
			$select->execute();
			
			if($select->errorCode()!=00000)
				return false;
			
			$rows=$select->fetch(PDO::FETCH_ASSOC);
			$rows['count']=(int)$rows['count']+1;
			$rows['results']=((int)$rows['results']+(int)$result)/$rows['count'];
			
			$update=$db->prepare("UPDATE university_test_name SET results=$rows[results], count=$rows[count] WHERE id=$test_id;");	
		}
		elseif($type=='school')
		{
			$_SESSION['exectest']['school'.$test_id]=true;
			$test_id=$db->quote($test_id);
			$select=$db->prepare("SELECT results, count FROM school_test_name WHERE id=$test_id");
			$select->execute();
			
			if($select->errorCode()!=00000)
				return false;
			
			$rows=$select->fetch(PDO::FETCH_ASSOC);
			$rows['count']=(int)$rows['count']+1;
			$rows['results']=((int)$rows['results']+(int)$result)/$rows['count'];
			
			$update=$db->prepare("UPDATE school_test_name SET results=$rows[results], count=$rows[count] WHERE id=$test_id;");	
		}
		
		$update->execute();		
	}
	
}
?>