<?
//модель содержащая методы для работы с университетами
class Universities
{
	//поле содержащее экземпляр данного класса
	private static $univer;
	
	private function __construct(){}
	private function __clone(){}
	private function __wakeup(){}
	private function __sleep(){}

	public static function get()
	{
		if(self::$univer instanceof self)
			return self::$univer;
		else
			return self::$univer=new Universities();
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
	
	//метод добавляет новый университет!!!!!!!!!!!!!!!!!!!!
	public function addUniversity($user_id, $name, $about, $image, $site, $email, $tel, $city, $address)
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
		
		$check=self::checkUniver($name, $site, $email, $city);
		$id=self::getTableId('universities');
		
		if($check==true)
		{
			$insert=$db->prepare("INSERT INTO universities(`id`,`city_id`,`user_id`,`name`,`address`,`tel`,`email`,`site`,`about`,`image`) VALUES ('$id', $city, $user_id, $name, $address, $tel, $email, $site, $about, $image)");
			$insert->execute();
			if($insert->errorCode()==00000)
				return true;
			else return false;
		}
		else return false;	
	}
	
	//метод проверки на наличия университета в базе данных, если университета нет в БД возвращается true
	private function checkUniver($name, $site, $email, $city)
	{
		$db=$this->db();
		$select=$db->prepare("SELECT id FROM universities WHERE name=$name AND site=$site AND email=$email AND city_id=$city");
		$select->execute();
		if($select->errorCode()==00000)
		{
			if($select->rowCount()==0)
				return true;
			else return false;
		}
		else return false;
	}
	
	//метод возвращает университеты созданные пользователем в виде ассоциативного массива
	public function getMyUniver($user_id)
	{
		$db=$this->db();
		$user_id=$db->quote($user_id);
		
		$select=$db->prepare("SELECT id, city_id, user_id, name, address, tel, email, site, about, image FROM universities WHERE user_id=$user_id");
		$select->execute();
		
		if($select->errorCode()==00000)
			 return $select->fetchAll(PDO::FETCH_ASSOC);
		else return false;
	}
	
	//метод возвращает строку со списком городов
	public function getCities()
	{
		$db=self::db();
		$query=$db->query('SELECT id, name FROM cities');
		if($query->rowCount()!=0)
		    return $query->fetchAll(PDO::FETCH_ASSOC);
		else return false;
	}
	
	//метод для редактирования университета пользователя
	public function editMyUniver($univer_id, $user_id, $name, $image, $city, $address, $about, $site, $email, $tel)
	{
		$db=$this->db();
		$univer_id=$db->quote($univer_id);
		$user_id=$db->quote($user_id);
		$name=$db->quote($name);
		$image=$db->quote($image);
		$city=$db->quote($city);
		$address=$db->quote($address);
		$about=$db->quote($about);
		$site=$db->quote($site);
		$email=$db->quote($email);
		$tel=$db->quote($tel);
		
		$update=$db->prepare("UPDATE universities SET name=$name, address=$address, tel=$tel, email=$email, site=$site, about=$about, image=$image, city_id=$city WHERE user_id=$user_id AND id=$univer_id");
		$update->execute();
		
		if($update->errorCode()==00000)
			return true;
		else return false;
		
	}
	
	//метод для удаления университета из базы данныхя
	public function deleteMyUniver($univer_id, $user_id)
	{
		$db=$this->db();
		$univer_id=$db->quote($univer_id);
		$user_id=$db->quote($user_id);
		
		$sel_fac=$db->prepare("SELECT id FROM faculties WHERE univer_id=$univer_id");
		$sel_fac->execute();
		$sel_row=$sel_fac->fetchAll(PDO::FETCH_ASSOC);
		
		foreach($sel_row as $fac)
		{
			$del_spec.="DELETE FROM univer_specialty WHERE faculty_id='$fac[id]';";
			$del_courses.="DELETE FROM university_courses WHERE faculty_id='$fac[id]';";
			$del_results.="DELETE FROM university_results WHERE faculty_id='$fac[id]';";
			
		}
		
		$sel_testname=$db->prepare("SELECT id FROM university_test_name WHERE university_id=$univer_id");
		$sel_testname->execute();
		
		if($sel_testname->errorCode()!=00000)
			return false;
		$rows=$sel_testname->fetchAll(PDO::FETCH_ASSOC);
		$count=count($rows);
		$num=1;
		
		foreach($rows as $row)
		{
			$delete_tests.="DELETE FROM university_tests WHERE test_id='".$row['id']."';";
		}
		
		
		$del_tsts=$db->prepare($delete_tests);
		$del_testname=$db->prepare("DELETE FROM university_test_name WHERE university_id=$univer_id");
		$del_sp=$db->prepare($del_spec);
		$del_cr=$db->prepare($del_courses);
		$del_rs=$db->prepare($del_results);
		$del_fc=$db->prepare("DELETE FROM faculties WHERE univer_id=$univer_id");
		$delete=$db->prepare("DELETE FROM universities WHERE id=$univer_id AND user_id=$user_id");
		
		$del_tsts->execute();
		$del_testname->execute();
		$del_rs->execute();
		$del_sp->execute();
		$del_cr->execute();
		$del_fc->execute();
		$delete->execute();
		
		if($delete->errorCode()==00000 and $del_cr->errorCode()==00000 and $del_fc->errorCode()==00000 and $del_sp->errorCode()==00000 and $del_rs->errorCode()==00000 and $del_testname->errorCode()==00000 and $del_tsts->errorCode()==00000)
			 return true;
		else return false;
			
	}
	
	//метод возвращает ассоциативный массив с данными о студентах добавленных пользователем
	public function getMyStudents($user_id)
	{
		$db=$this->db();
		$univers=self::getMyUniver($user_id);
		
		$query="SELECT id, city_id, university_id, group_id, course_id, specialty_id, fio, date, address, email, tel FROM students WHERE ";
		
		$cnt=count($univers);
		$num=1;
		
		foreach($univers as $univer)
		{
			if($cnt==$num)
				$query.="university_id='".$univer['id']."';";
			else
				$query.="university_id='".$univer['id']."' OR ";
			$num++;
		}
		
		$students=$db->prepare($query);
		$students->execute();
		
		if($students->errorCode()==00000)
		{
			if($students->rowCount()==0)
			   return false;
			else
			   return $students->fetchAll(PDO::FETCH_ASSOC);	
		}
		else return false;	
	}
	
	//метод возвращает строку со всеми университетами города передаваемого в параметре
	public function getUniverByCity($city_id)
	{
		$db=$this->db();
		$city_id=$db->quote($city_id);
		
		$select=$db->prepare("SELECT name, id FROM universities WHERE city_id=$city_id");
		$select->execute();
		
		if($select->errorCode()==00000)
		{
			if($select->rowCount()==0)
				return '<option value="0">В данном городе нет ВУЗ`ов!</option>';
			else
			{
				foreach($select->fetchAll(PDO::FETCH_ASSOC) as $univer)
				{
					$option.='<option value="'.$univer['id'].'">'.$univer['name'].'</option>';	
				}
				
				return $option;
			}	
		}
		else return false;		
	}
	
	//метод возвращает строку со списком всех факультетов университета по передаваемому параметру идентификатору университета
	public function getFacultyByUniver($univer_id)
	{
		$db=$this->db();
		$univer_id=$db->quote($univer_id);
		
		$select=$db->prepare("SELECT name, id FROM faculties WHERE univer_id=$univer_id");
		$select->execute();
		
		if($select->errorCode()==00000)
		{
			if($select->rowCount()==0)
				return '<option value="0">В данном ВУЗ`е нет факультетов!</option>';
			else
			{
				foreach($select->fetchAll(PDO::FETCH_ASSOC) as $univer)
				{
					$option.='<option value="'.$univer['id'].'">'.$univer['name'].'</option>';	
				}
				
				return $option;
			}	
		}
		else return false;		
	}
	
	//метод возвращает строку со списком всех курсов факультета по передаваемому параметру идентификатору факультета
	public function getCoursesByFaculty($faculty_id)
	{
		$db=$this->db();
		$faculty_id=$db->quote($faculty_id);
		
		$select=$db->prepare("SELECT course, id FROM university_courses WHERE faculty_id=$faculty_id");
		$select->execute();
		
		if($select->errorCode()==00000)
		{
				if($select->rowCount()==0)
					return '<option value="0">На данном факультете нет курсов!</option>';
				else
				{
					foreach($select->fetchAll(PDO::FETCH_ASSOC) as $univer)
					{
						$option.='<option value="'.$univer['id'].'">'.$univer['course'].'</option>';	
					}
				
					return $option;
				}	

		}
		else return false;		
	}
	
	//метод возвращает строку со списком всех специальностей по передаваемому параметру идентификатору факультета
	public function getSpecByFaculty($faculty_id)
	{
		$db=$this->db();
		$faculty_id=$db->quote($faculty_id);
		
		$select=$db->prepare("SELECT name, id FROM univer_specialty WHERE faculty_id=$faculty_id");
		$select->execute();
		
		if($select->errorCode()==00000)
		{
			if($select->rowCount()==0)
				return '<option value="0">На данном факультете нет специальностей!</option>';
			else
			{
				foreach($select->fetchAll(PDO::FETCH_ASSOC) as $univer)
				{
					$option.='<option value="'.$univer['id'].'">'.$univer['name'].'</option>';	
				}
				
				return $option;
			}	
		}
		else return false;		
	}
	
	//метод возвращает строки со спискомм груп по передаваемым параметрам специальности и курсе
	public function getGroupsByParams($specialty_id, $course_id)
	{
		$db=$this->db();
		$specialty_id=$db->quote($specialty_id);
		$course_id=$db->quote($course_id);
		
		$select=$db->prepare("SELECT name, id FROM university_groups WHERE specialty_id=$specialty_id");
		$select->execute();
		
		if($select->errorCode()==00000)
		{
			if($select->rowCount()==0)
				return '<option value="0">Групп ещё нет!</option>';
			else
			{
				foreach($select->fetchAll(PDO::FETCH_ASSOC) as $univer)
				{
					$option.='<option value="'.$univer['id'].'">'.$univer['name'].'</option>';	
				}
				
				return $option;
			}	
		}
		else return false;		
	}
	
	//метод добавляет нового студента в базу
	public function addNewStudent($fio, $email, $tel, $address, $date, $city, $univer, $faculty, $course, $specialty, $group)
	{
		$db=$this->db();
		$fio=$db->quote($fio);
		$email=$db->quote($email);
		$tel=$db->quote($tel);
		$address=$db->quote($address);
		$date=$db->quote($date);
		$city=$db->quote($city);
		$univer=$db->quote($univer);
		$faculty=$db->quote($faculty);
		$course=$db->quote($course);
		$specialty=$db->quote($specialty);
		$group=$db->quote($group);
		
		$id=self::getTableId('students');
		
		$check=self::checkStudent($fio,$city,$group,$email);
		//если студент уже есть в базе данных возвращаем ложь
		if($check==false)
			return false;
		else
		{
			$insert=$db->prepare("INSERT INTO students(`id`,`city_id`,`university_id`,`group_id`,`course_id`,`specialty_id`,`fio`,`date`,`address`,`email`,`tel`,`faculty_id`) VALUES('$id',$city,$univer,$group,$course,$specialty,$fio,$date,$address,$email,$tel,$faculty)");
			$insert->execute();
			
			if($insert->errorCode()==00000)
				return true;
			else return false;
		}
	}
	
	//метод проверки на наличия студента в базе данных, если студента нет в БД возвращается true
	private function checkStudent($fio, $city, $group, $email)
	{
		$db=$this->db();
		$select=$db->prepare("SELECT id FROM students WHERE fio=$fio AND city_id=$city AND email=$email AND group_id=$group");
		$select->execute();
		if($select->errorCode()==00000)
		{
			if($select->rowCount()==0)
				return true;
			else return false;
		}
		else return false;
	}
	
	//метод для обновления всей информации о студенте
	public function updateStudentAll($student_id, $fio, $email, $tel, $address, $date, $city, $univer, $faculty, $course, $specialty, $group)
	{
		$db=$this->db();
		$fio=$db->quote($fio);
		$email=$db->quote($email);
		$tel=$db->quote($tel);
		$address=$db->quote($address);
		$date=$db->quote($date);
		$city=$db->quote($city);
		$univer=$db->quote($univer);
		$faculty=$db->quote($faculty);
		$course=$db->quote($course);
		$specialty=$db->quote($specialty);
		$group=$db->quote($group);
		$student_id=$db->quote($student_id);
		
		$update=$db->prepare("UPDATE students SET city_id=$city, university_id=$univer, group_id=$group, course_id=$course, specialty_id=$specialty, faculty_id=$faculty, fio=$fio, date=$date, address=$address, email=$email, tel=$tel WHERE id=$student_id");
		$update->execute();
		
		if($update->errorCode()==00000)
			return true;
		else return false;
		
	}
	
	//метод для частичной обновлении информации о студенте
	public function updateStudentPartly($student_id, $fio, $email, $tel, $address, $date)
	{
		$db=$this->db();
		$fio=$db->quote($fio);
		$email=$db->quote($email);
		$tel=$db->quote($tel);
		$address=$db->quote($address);
		$date=$db->quote($date);
		$student_id=$db->quote($student_id);
		
		$update=$db->prepare("UPDATE students SET fio=$fio, date=$date, address=$address, email=$email, tel=$tel WHERE id=$student_id");
		$update->execute();
		
		if($update->errorCode()==00000)
			return true;
		else return false;
	}
	
	//метод удаляет студента из базы в том случае если студент числится в университетах добавленных пользователем
	public function deleteStudent($student_id, $teacher_id)
	{
		$db=$this->db();
		$student_id=$db->quote($student_id);
		$teacher_id=$db->quote($teacher_id);
		
		$select_univers=$db->prepare("SELECT id FROM universities WHERE user_id=$teacher_id");
		$select_univers->execute();
		
		if($select_univers->errorCode()==00000)
		{
			$count_un=$select_univers->rowCount();
			if($count_un==0)
				return false;
			else
			{
				$query="DELETE FROM students WHERE id=$student_id AND ";
				
				$num=1;
				foreach($select_univers as $univer)
				{
					if($num!=$count_un)
						$query.="university_id='".$univer['id']."' OR";
					else
						$query.="university_id='".$univer['id']."';";
					$num++;
				}	
				
				$delete=$db->prepare($query);
				$delete->execute();
				
				if($delete->errorCode()==00000)
					return true;
				else return false;
			}	
		}
		else return false;
	}
	
	//метод возвращает ассоциативный массив с данными о факультетах
	public function getUniverFaculties($univer_id)
	{
		$db=$this->db();
		$univer_id=$db->quote($univer_id);
		
		$select=$db->prepare("SELECT id, name FROM faculties WHERE univer_id=$univer_id");
		$select->execute();
		
		if($select->errorCode()==00000)
			{
				return $select->fetchAll(PDO::FETCH_ASSOC);
			}
		else return false;	
		
		
	}
	
	//метод добавляет новый факультет в базу данных!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	public function addNewFaculty($univer_id, $name, $spec, $course)
	{
		$db=$this->db();
		$univer_id=$db->quote($univer_id);
		$name=$db->quote($name);
		$course=(int)$course;
		
		$check=self::checkFaculty($univer_id,$name);
		if($check==false)
			return false;
			
		$fac_id=self::getTableId('faculties');
		$fac_query="INSERT INTO faculties(`id`,`univer_id`,`name`) VALUES ('$fac_id',$univer_id, $name);";
		//разбиваем строку со специальностями в массив
		$spcs=explode(',',trim($spec));
		$spcs_count=count($spcs);
		
		$spec_query.="INSERT INTO univer_specialty(`id`, `faculty_id`, `name`) VALUES (NULL,'$fac_id', '$spcs[0]');";

		
		
		//формирование строки запроса на вставку курсов
		for($i=1;$i<=$course;)
		{
			$course_query.="INSERT INTO university_courses(`id`, `faculty_id`, `course`) VALUES (NULL, '$fac_id', '$i');";
			$i++;	
		}
	
		$insert_fac=$db->prepare($fac_query);
		$insert_fac->execute();
		$insert_spec=$db->prepare($spec_query);
		$insert_spec->execute();
		$insert_course=$db->prepare($course_query);	
		$insert_course->execute();
		
		if($insert_fac->errorCode()==00000 and $insert_course->errorCode()==00000 and $insert_spec->errorCode()==00000)
			return true;
		else return false;
		
	}
	
	//метод проверяет наличие аналогичного факультета в базе данных, возвращает истину если факультета нет в базе данных
	public function checkFaculty($univer_id, $name)
	{
		$db=$this->db();
		$check=$db->prepare("SELECT id FROM faculties WHERE univer_id=$univer_id AND name=$name");
		$check->execute();
		
		if($check->errorCode()==00000)
		{
			if($check->rowCount()==0)
				return true;
			else return false;	
		}
		else return false;
	}
	
	//метод выводит все курсы университета
	public function getCoursesByUniver($univer_id)
	{
		$db=$this->db();
		$univer_id=$db->quote($univer_id);
		$fac_query=$db->prepare("SELECT id FROM faculties WHERE univer_id=$univer_id");
		$fac_query->execute();
		
		$course_query="SELECT id, faculty_id, course FROM university_courses WHERE ";
		
		if($fac_query->errorCode()==00000)
		{
			$fac_count=$fac_query->rowCount();
			if($fac_count==0)
				return false;
				
			$fac_rows=$fac_query->fetchAll(PDO::FETCH_ASSOC);
			
			$num=1;
			foreach($fac_rows as $fac)
			{
				if($fac_count!=$num)
					$course_query.="faculty_id='".$fac['id']."' OR ";
				else
					$course_query.="faculty_id='".$fac['id']."';";
					
				$num++;
			}
			
			$courses=$db->prepare($course_query);
			$courses->execute();

			if($courses->errorCode()==00000)
				return $courses->fetchAll(PDO::FETCH_ASSOC);
			
			
		}
		else return false;
	}
	
	//метод возвращает ассоциативный массив со специальностями университета
	public function getSpecsByUniver($univer_id)
	{
		$db=$this->db();
		$univer_id=$db->quote($univer_id);
		
		$select_fac=$db->prepare("SELECT id FROM faculties WHERE univer_id=$univer_id");
		$select_fac->execute();
		
		if($select_fac->errorCode()==00000)
		{
			if($select_fac->rowCount()==0)
				return false;
			else
			{
				$select_spec="SELECT id, faculty_id, name FROM univer_specialty WHERE ";
				$fac_rows=$select_fac->fetchAll(PDO::FETCH_ASSOC);
				$fac_count=$select_fac->rowCount();
				$num=1;
				foreach($fac_rows as $fac)
				{
					if($fac_count==$num)
						$select_spec.="faculty_id='".$fac['id']."';";
					else
						$select_spec.="faculty_id='".$fac['id']."' OR ";
					
					$num++;
				}
				
				$specialities=$db->prepare($select_spec);
				$specialities->execute();
				
				if($specialities->errorCode()==00000)
					return $specialities->fetchAll(PDO::FETCH_ASSOC);
				else return false;
			}
			
		}
		else return false;	
	}
	//метод удаляет все подразделения факультета студентов и результаты прохождения ими тестов!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	public function deleteFaculty($fac_id)
	{
		$db=$this->db();
		$fac_id=$db->quote($fac_id);
		
		$delete_fac=$db->prepare("DELETE FROM faculties WHERE id=$fac_id");
		$delete_spec=$db->prepare("DELETE FROM univer_specialty WHERE faculty_id=$fac_id");
		$delete_course=$db->prepare("DELETE FROM university_courses WHERE faculty_id=$fac_id");
		
		$delete_spec->execute();
		$delete_course->execute();
		$delete_fac->execute();
		
		if($delete_course->errorCode()==00000 and $delete_fac->errorCode()==00000 and $delete_spec->errorCode()==00000)
			 return true;
		else return false;
	}
	
	//метод сохраняет измененные данные о факультете университета!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	public function editFaculty($fac_id, $name, $courses, $specs)
	{
		$db=$this->db();
		$fac_id=$db->quote($fac_id);
		$name=$db->quote($name);
		$courses=(int)$courses;
		
		$update_fac=$db->prepare("UPDATE faculties SET name=$name WHERE id=$fac_id");
		
		//получаем идентификаторы специальностей
		$spec_ids=$db->prepare("SELECT id FROM univer_specialty WHERE faculty_id=$fac_id");
		$spec_ids->execute();
		
		if($spec_ids->errorCode()==00000)
		{
			$spec_rows=$spec_ids->fetchAll(PDO::FETCH_ASSOC);
			$spec_names=explode(',',trim($specs));		
			$spec_count=count($spec_names);
			$old_count=count($spec_rows);

			foreach($spec_rows as $srow)
					$newrow[]=$srow['id'];
			
			
			
				
			//если количество специальностей осталось неизменным
			if($spec_count==$old_count)
			//формируем строку запроса на обновление названий специальностей
			{
				$i=0;
				foreach($spec_names as $row)
					{
						$spec_query.="UPDATE univer_specialty SET name='".$row."' WHERE faculty_id=$fac_id AND id=".$newrow[$i].";";						$i++;
					}
			}
			//если добавили еще специальностей
			elseif($spec_count>$old_count)
			{
				$cnt=$spec_count-$old_count;
				$i=1;
				while($i<=$cnt)
				{
					$nme=$spec_names[--$spec_count];
					$spec_query.="INSERT INTO univer_specialty(`id`,`faculty_id`,`name`) VALUES (NULL,$fac_id,'$nme');";
					$i++;
				}
			}
			//если удалении специальности
			elseif($spec_count<$old_count)
			{
				$cnt=$old_count-$spec_count;
				$i=1;
				
				
				while($i<=$cnt)
				{	$j=$i-1;
					$spec_query.="DELETE FROM univer_specialty WHERE id='".$newrow[$j]."' AND faculty_id=$fac_id;";
					$i++;
				}
				
			}
			
			$update_spec=$db->prepare($spec_query);		
			
			$course_count=$db->prepare("SELECT count(id) FROM university_courses WHERE faculty_id=$fac_id LIMIT 1");
			$course_count->execute();
			
			if($course_count->errorCode()!=00000)
				return false;
			$crss=$course_count->fetch(PDO::FETCH_NUM);
			$crss[0];
				
				//если курсов в базе данных меньше пришедшего значения, то добавляем новые
				if($crss[0]<$courses)
				{
					$cnt=$courses-$crss[0];
					for($i=1;$i<=$cnt;$i++)
					{	
						$courses_query.="INSERT INTO university_courses(`id`, `faculty_id`, `course`) VALUES (NULL, $fac_id, '".++$crss[0]."');";	
					}
					
					$update_crss=$db->prepare($courses_query);
					$update_crss->execute();
				}
				//если курсов в базе данных меньше пришедшего значения, то удаляем существующие
				elseif($crss[0]>$courses)
				{
					$courses_query.="DELETE FROM university_courses WHERE course>$courses";
					$update_crss=$db->prepare($courses_query);
					$update_crss->execute();
				}
			
			$update_fac->execute();
			$update_spec->execute();
			
			if($update_spec->errorCode()==00000  and $update_fac->errorCode()==00000)
				return true;
			else return false;
			
		}
		else return false;
	}
	
	//метод выводит все студенческие группы факультета
	public function getGroupsByFac($fac_id)
	{
		$db=$this->db();
		$fac_id=$db->quote($fac_id);
		
		$select_spec=$db->prepare("SELECT id FROM univer_specialty WHERE faculty_id=$fac_id");	
		$select_spec->execute();
		
		if($select_spec->errorCode()==00000)
		{
			$spec_row=$select_spec->fetchAll(PDO::FETCH_ASSOC);
			$sel_groups="SELECT id, specialty_id, course_id, name, year FROM university_groups WHERE ";
			
			$count=count($spec_row);
			$i=1;
			foreach($spec_row as $row)
			{
				if($i!=$count)
					$sel_groups.="specialty_id='".$row['id']."' OR ";
				else 
					$sel_groups.="specialty_id='".$row['id']."';";
				$i++;
			}	

			$groups=$db->prepare($sel_groups);
			$groups->execute();
			
			if($groups->errorCode()==00000)
				return $groups->fetchAll(PDO::FETCH_ASSOC);
			else return false;
			
		}
		else return false;
	}
	
	//метод возвращает информацию о курсах и специальностях факультета!!!!!!!!!!!
	public function getSpecAndCorses($fac_id)
	{
		$db=$this->db();
		$fac_id=$db->quote($fac_id);
		
		$sel_course=$db->prepare("SELECT id, course FROM university_courses WHERE faculty_id=$fac_id ORDER BY course");
		$sel_spec=$db->prepare("SELECT id, name FROM univer_specialty WHERE faculty_id=$fac_id");
		
		$sel_course->execute();
		$sel_spec->execute();
		
		if($sel_spec->errorCode()==00000 and $sel_course->errorCode()==00000)
		{
			$spec_rows=$sel_spec->fetchAll(PDO::FETCH_ASSOC);
			
			foreach($spec_rows as $spec)
				$res_spec.='<option value="'.$spec['id'].'">'.$spec['name'].'</option>';	
			
			
			$course_rows=$sel_course->fetchAll(PDO::FETCH_ASSOC);
			
			foreach($course_rows as $course)
				$res_course.='<option value="'.$course['id'].'">'.$course['course'].'</option>';
			
			return '<label for="spec">Выберите специальность:</label><br><select name="spec">'.$res_spec.'</select><br>
					<label for="course">Выберите курс:</label><br><select name="course">'.$res_course.'</select>';
			
			
		}
		else return false;
		
		
	}
	
	//метод добавляет новую студенческую группу в базу данных
	public function addNewGroup($name, $year, $spec, $course)
	{
		$db=$this->db();
		$name=$db->quote($name);
		$year=$db->quote($year);
		$spec=$db->quote($spec);
		$course=$db->quote($course);
		
		$check=self::checkNewGroup($name,$year,$spec,$course);
		if($check)
		{
		$insert=$db->prepare("INSERT INTO university_groups (`id`,`specialty_id`, `course_id`, `name`, `year`) VALUES (NULL, $spec,$course,$name,$year)");
		$insert->execute();
		
		if($insert->errorCode()==00000)
			return true;
		else return false;
		}
		else return false;
		
		
	}
	
	//метод проверяет наличие аналогичной группы в базе данных
	private function checkNewGroup($name, $year, $spec, $course)
	{
		$db=$this->db();
		
		$check=$db->prepare("SELECT id FROM university_groups WHERE specialty_id=$spec AND course_id=$course AND name=$name AND year=$year");
		$check->execute();
		
		if($check->errorCode()==00000)
		{
			if($check->rowCount()==0)
				return true;
			else return false;
					
		}
		else return false;
	}
	
	//метод возвращает все специальности факультета по передаваемому параметру идентификатору массива
	public function getSpecByFacArr($fac_id)
	{
		$db=$this->db();
		$fac_id=$db->quote($fac_id);
		
		$select=$db->prepare("SELECT id, name FROM univer_specialty WHERE faculty_id=$fac_id");
		$select->execute();
		
		if($select->errorCode()==00000)
			 return $select->fetchAll(PDO::FETCH_ASSOC);
		else return false;
	}
	
	//метод возвращает все курсы по передаваемому параметру идентификатору факультета
	public function getCoursesByFacArr($fac_id)
	{
		$db=$this->db();
		$fac_id=$db->quote($fac_id);
		
		$select=$db->prepare("SELECT id, course FROM university_courses WHERE faculty_id=$fac_id");
		$select->execute();
		
		if($select->errorCode()==00000)
			 return $select->fetchAll(PDO::FETCH_ASSOC);
		else return false;
	}
	
	//метод удаляет группу пользователя и всех её студентов с результатами тестирования
	public function deleteGroup($group_id)
	{
		$db=$this->db();
		$group_id=$db->quote($group_id);
		
		$del_stud=$db->prepare("DELETE FROM students WHERE group_id=$group_id");
		$del_group=$db->prepare("DELETE FROM university_groups WHERE id=$group_id");
		$del_res=$db->prepare("DELETE FROM university_results WHERE group_id=$group_id");
		$del_les=$db->prepare("DELETE FROM university_lessons WHERE group_id=$group_id");
		$del_testname=$db->prepare("DELETE FROM university_test_name WHERE group_id=$group_id");
		
		$sel_tnid=$db->prepare("SELECT id FROM university_test_name WHERE group_id=$group_id");
		$sel_tnid->execute();
		
		if($sel_tnid->errorCode()==00000)
		{
			$rows=$sel_tnid->fetchAll(PDO::FETCH_ASSOC);
			$count=count($rows);

			if($count!=0)
			{
				$d_tests="DELETE FROM university_tests WHERE ";
				$num=1;
				foreach($rows as $row)
				{
					if($count!=$num)
						$d_tests.="test_id='".$row['id']."' OR ";
					else
						$d_tests.="test_id='".$row['id']."';";
					$num++;
				}
		
			$del_tests=$db->prepare($d_tests);
			$del_tests->execute();
			if($del_tests->errorCode()!=00000)
				return false;
			}

			$del_res->execute();
			$del_testname->execute();
			$del_les->execute();
			$del_stud->execute();
			$del_group->execute();
			
			
			if($del_res->errorCode()==00000 and $del_testname->errorCode()==00000 and $del_les->errorCode()==00000 and $del_group->errorCode()==00000 and $del_stud->errorCode()==00000)
			return true;
				
		}
		else return false;
	}
	
	//метод редактирует данные о студенческой группе и возвращает результат в соответствии с успешностью редактирования
	public function editGroup($group_id, $name, $year, $course, $spec)
	{
		$db=$this->db();
		$group_id=$db->quote($group_id);
		$name=$db->quote($name);
		$year=$db->quote($year);
		$course=$db->quote($course);
		$spec=$db->quote($spec);
		
		$update=$db->prepare("UPDATE university_groups SET specialty_id=$spec, course_id=$course, name=$name, year=$year WHERE id=$group_id");
		$update->execute();
		
		if($update->errorCode()==00000)
			return true;
		else return false;
		
	}
	
	//метод возвращает предметы по передаваемому иднетификатору группы
	public function getGroupLessons($group_id)
	{
		$db=$this->db();
		$group_id=$db->quote($group_id);
		
		$select=$db->prepare("SELECT id, name FROM university_lessons WHERE group_id=$group_id");
		$select->execute();
		
		if($select->errorCode()==00000)
			 return $select->fetchAll(PDO::FETCH_ASSOC);
		else return false;
		
	}
	
	//метод добавляет новый предмет к студенческой группе
	public function addNewLesson($group_id, $name)
	{
		$db=$this->db();
		$group_id=$db->quote($group_id);
		$name=$db->quote($name);
		
		$check=$this->checkLesson($group_id,$name);
		
		if($check)
		{
			$insert=$db->prepare("INSERT INTO university_lessons(`id`,`group_id`,`name`) VALUES (NULL, $group_id, $name)");
			$insert->execute();
			
			if($insert->errorCode()==00000)
				return true;
			else return false;
		}
		else return false;
	}
	
	//метод проверяет наличие соответствующего предмета в базе данных
	private function checkLesson($group_id, $name)
	{
		$db=$this->db();
		
		$select=$db->prepare("SELECT id FROM university_lessons WHERE group_id=$group_id AND name=$name");
		$select->execute();
		
		if($select->errorCode()==00000)
		{
			if($select->rowCount()==0)
				return true;
			else
				return false;
		}
		else return false;	
	}
	
	//метод удаляет предмет из базы данных и тесты, результаты прохождения тестов для данного предмета
	public function deleteLesson($lesson_id)
	{
		$db=$this->db();
		$lesson_id=$db->quote($lesson_id);
		
		$sel_testname=$db->prepare("SELECT id FROM university_test_name WHERE lesson_id=$lesson_id");
		$sel_testname->execute();
		
		if($sel_testname->errorCode()!=00000)
			return false;
		$rows=$sel_testname->fetchAll(PDO::FETCH_ASSOC);
		$count=count($rows);
		if($count!=0)
		{
			$del_tests="DELETE FROM university_tests WHERE ";	
			$del_results="DELETE FROM university_results WHERE ";
		}
		
		$num=1;
		
		foreach($rows as $row)
		{
			if($count!=$num)
				{
					$del_tests.="test_id='".$row['id']."' OR ";
					$del_results.="test_id='".$row['id']."' OR ";
				}
			else
				{
					$del_tests.="test_id='".$row['id']."';";
					$del_results.="test_id='".$row['id']."';";
				}
			
			$num++;
		}

		if(isset($del_results))
		  { 
		  	$del_rslts=$db->prepare($del_results);
			$del_rslts->execute(); 
			if($del_rslts->errorCode()!==00000)
				return false;
		  }
		if(isset($del_tests))
		  { 
		    $del_tsts=$db->prepare($del_tests);
			$del_tsts->execute(); 
			if($del_tsts->errorCode()!=00000)
				return false;
			
		  }
		
		$del_testname=$db->prepare("DELETE FROM university_test_name WHERE lesson_id=$lesson_id");	
		$del_lesson=$db->prepare("DELETE FROM university_lessons WHERE id=$lesson_id");
		
		
		$del_testname->execute();
		$del_lesson->execute();
		
		if($del_testname->errorCode()==00000 and $del_lesson->errorCode()==00000)
			return true;
		else return false;
	}
	
	//метод редактирует предмет из базы данных
	public function editLesson($lesson_id, $name)
	{
		$db=$this->db();
		$lesson_id=$db->quote($lesson_id);
		$name=$db->quote($name);
		$update=$db->prepare("UPDATE university_lessons SET name=$name WHERE id=$lesson_id");
		$update->execute();
		
		if($update->errorCode()==00000)
			return true;
		else return false;
	}
	
	//метод возвращает список предметов для данной группы
	public function getLessonsByGroup($group_id)
	{
		$db=$this->db();
		$group_id=$db->quote($group_id);
		
		$select=$db->prepare("SELECT id, name FROM university_lessons WHERE group_id=$group_id");
		$select->execute();
		
		if($select->errorCode()!=00000)
			return false;
		
		$rows=$select->fetchAll(PDO::FETCH_ASSOC);
		$result='<option value="0">Выберите предмет \/</option>';
		
		if(count($rows)==0)
			return $result.'<option value="0">Для данной группы не добавлено предметов!</option>';
	
		foreach($rows as $row)
			$result.='<option value="'.$row['id'].'">'.$row['name'].'</option>';
		
		return $result;
		
	}
	
	//метод возвращает имя ВУЗа по его идентификатору
	public function getUniverName($univer_id)
	{
		$db=$this->db();
		$univer_id=$db->quote($univer_id);
		
		$select=$db->prepare("SELECT name FROM universities WHERE id=$univer_id LIMIT 1");
		$select->execute();
		
		if($select->errorCode()!=00000)
			return false;
		
		$rows=$select->fetch(PDO::FETCH_NUM);
		return $rows[0];

	}
	
	//метод возвращает название факультета по его идентификатору
	public function getFacultyName($fac_id)
	{
		$db=$this->db();
		$fac_id=$db->quote($fac_id);
		
		$select=$db->prepare("SELECT name FROM faculties WHERE id=$fac_id LIMIT 1");
		$select->execute();
		
		if($select->errorCode()!=00000)
			return false;
		
		$rows=$select->fetch(PDO::FETCH_NUM);
		return $rows[0];
	}
	
	//метод возвращает название специальности по её идентификатору
	public function getSpecName($spec_id)
	{
		$db=$this->db();
		$spec_id=$db->quote($spec_id);
		
		$select=$db->prepare("SELECT name FROM univer_specialty WHERE id=$spec_id LIMIT 1");
		$select->execute();
		
		if($select->errorCode()!=00000)
			return false;
		
		$rows=$select->fetch(PDO::FETCH_NUM);
		return $rows[0];
	}
	
	//метод возвращает номер курса по его идентификатору
	public function getCourseName($course_id)
	{
		$db=$this->db();
		$course_id=$db->quote($course_id);
		
		$select=$db->prepare("SELECT course FROM university_courses WHERE id=$course_id LIMIT 1");
		$select->execute();
		
		if($select->errorCode()!=00000)
			return false;
		
		$rows=$select->fetch(PDO::FETCH_NUM);
		return $rows[0];
	}
	
	//метод возвращает название группы по её идентификатору
	public function getGroupName($group_id)
	{
		$db=$this->db();
		$group_id=$db->quote($group_id);
		
		$select=$db->prepare("SELECT name FROM university_groups WHERE id=$group_id LIMIT 1");
		$select->execute();
		
		if($select->errorCode()!=00000)
			return false;
		
		$rows=$select->fetch(PDO::FETCH_NUM);
		return $rows[0];
	}
	
	//метод возвращает название предмета по его идентификатору
	public function getLessonName($lesson_id)
	{
		$db=$this->db();
		$lesson_id=$db->quote($lesson_id);
		
		$select=$db->prepare("SELECT name FROM university_lessons WHERE id=$lesson_id LIMIT 1");
		$select->execute();
		
		if($select->errorCode()!=00000)
			return false;
		
		$rows=$select->fetch(PDO::FETCH_NUM);
		return $rows[0];
	}
	
	//метод возвращает ссылку на университет по передаваемому параметру идентификатору теста
	public function getUniverNameLink($univer_id)
	{
		$db=$this->db();
		
		$select=$db->prepare("SELECT name FROM universities WHERE id='$univer_id' LIMIT 1");
		$select->execute();
		
		if($select->errorCode()!=00000)
			return false;
		
		$name=$select->fetch(PDO::FETCH_NUM);
		
		return '<a href="/universities/getuniver/id/'.$univer_id.'">'.$name[0].'</a>';
	}
	
	//метод возвращает массив с университетами пользователя
	public function getUniverIdName($user_id)
	{
		$db=$this->db();
		$user_id=$db->quote($user_id);
		
		$select=$db->prepare("SELECT id, name FROM universities WHERE user_id=$user_id");
		$select->execute();
		
		if($select->errorCode()!=00000)
			return false;
		else
		return $select->fetchAll(PDO::FETCH_ASSOC);
		
	}
	
	//метод обновляет информацию о университетском тесте!!!!!!!!!!!!!!!!!!!!!
	public function editUniverTestname($user_id, $test_id, $test_name, $test_description, $test_time, $univer, $faculty, $course, $specialty, $group, $lessons)
	{
		$db=$this->db();
		$user_id=$db->quote($user_id);
		$test_name=$db->quote($test_name);
		$test_description=$db->quote($test_description);
		$test_time=$db->quote($test_time);
		$univer=$db->quote($univer);
		$faculty=$db->quote($faculty);
		$course=$db->quote($course);
		$specialty=$db->quote($specialty);
		$group=$db->quote($group);
		$lessons=$db->quote($lessons);
		$test_id=$db->quote($test_id);
		
		$update=$db->prepare("UPDATE university_test_name SET university_id=$univer, faculty_id=$faculty, specialty_id=$specialty, course_id=$course, group_id=$group, lesson_id=$lessons, name=$test_name, description=$test_description, time_min=$test_time WHERE user_id=$user_id AND id=$test_id");
		$update->execute();
		
		if($update->errorCode()!=00000)
			return false;
		else return true;
		
	}
	
	//метод частично обновляет информацию о университетском тесте
	public function editUniverTestnameParticle($user_id, $test_id, $test_name, $test_description, $test_time)
	{
		$db=$this->db();
		$user_id=$db->quote($user_id);
		$test_name=$db->quote($test_name);
		$test_description=$db->quote($test_description);
		$test_time=$db->quote($test_time);
		$test_id=$db->quote($test_id);
		
		$update=$db->prepare("UPDATE university_test_name SET name=$test_name, description=$test_description, time_min=$test_time WHERE user_id=$user_id AND id=$test_id");
		$update->execute();
		
		if($update->errorCode()!=00000)
			return false;
		else return true;
		
	}
	
	//метод удаляет тест, вопросы теста и результаты его прохождения
	public function deleteUniverTest($test_id)
	{
		$db=$this->db();
		$test_id=$db->quote($test_id);
		
		$del_tests=$db->prepare("DELETE FROM university_tests WHERE test_id=$test_id");
		$del_results=$db->prepare("DELETE FROM university_results WHERE test_id=$test_id");
		$del_testname=$db->prepare("DELETE FROM university_test_name WHERE id=$test_id");
		
		$del_results->execute();
		$del_tests->execute();
		$del_testname->execute();
		
		if($del_results->errorCode()==00000 and $del_tests->errorCode()==00000 and $del_testname->errorCode()==00000)
			return true;
		else return false;
	}
	
	//метод возвращает результаты прохождения тестов студентов которые создал пользователь
	public function getTestResult($user_id)
	{
		$db=$this->db();
		
		$results="SELECT id, 
		(SELECT name FROM university_test_name WHERE id=test_id) AS test_id, 
		(SELECT fio FROM students WHERE id=student_id) AS student_id, 
		(SELECT name FROM universities WHERE id=univer_id) AS univer_id, 
		(SELECT name FROM univer_specialty WHERE id=specialty_id) AS specialty_id,
		(SELECT course FROM university_courses WHERE id=course_id) AS course_id,
		(SELECT name FROM university_groups WHERE id=group_id) AS group_id,
		(SELECT name FROM faculties WHERE id=faculty_id) AS faculty_id, 
		result, date, time_min FROM university_results WHERE ";
		
		$sel_univer=self::getMyUniver($user_id);
		
		if(!is_array($sel_univer))
			return false;
		
		$count=count($sel_univer);
		$num=1;
		foreach($sel_univer as $univer)
		{
			if($num!=$count)
				$results.="univer_id='".$univer['id']."' OR ";
			else
				$results.="univer_id='".$univer['id']."';";
			$num++;
		}
		
		$results=$db->prepare($results);
		$results->execute();
		
		if($results->errorCode()!=00000)
			return false;
		else
		    return $results->fetchAll(PDO::FETCH_ASSOC);
		 
	}
	
	
	//метод возвращает университет зарегистрированного пользователя с рангом "студент"
	public function getUniverByStudent($loginfo)
	{
		$db=$this->db();
		$fio=$db->quote($loginfo['fio']);
		$email=$db->quote($loginfo['email']);
		
		$sel_univer=$db->prepare("SELECT id, city_id, user_id, name, address, tel, email, site, about, image FROM universities WHERE id=(SELECT university_id FROM students WHERE fio=$fio AND email=$email LIMIT 1)");
		$sel_univer->execute();
		
		if($sel_univer->errorCode()!=00000)
			return false;
		else 
		{
			$univer=$sel_univer->fetch(PDO::FETCH_ASSOC);
			$univer['city_id']=self::getCityName($univer['city_id']);
			return $univer;
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
	
	//метод возвращает тесты университета по идентификатору студента
	public function getUniverTests($loginfo)
	{
		$db=$this->db();
		$fio=$db->quote($loginfo['fio']);
		$email=$db->quote($loginfo['email']);
		
		$sel_testnames=$db->prepare("SELECT id, name FROM university_test_name WHERE university_id=(SELECT university_id FROM students WHERE fio=$fio AND email=$email LIMIT 1)");
		$sel_testnames->execute();
		
		if($sel_testnames->errorCode()!=00000)
			return false;
		else
			return $sel_testnames->fetchAll(PDO::FETCH_ASSOC);
	}
	
	//метод возвращает группу студента по его анкетным данным
	public function getStudentGroup($loginfo)
	{
		$db=$this->db();
		$fio=$db->quote($loginfo['fio']);
		$email=$db->quote($loginfo['email']);
		
		$sel_group=$db->prepare("SELECT id, specialty_id, course_id, name, year FROM university_groups WHERE id=(SELECT group_id FROM students WHERE fio=$fio AND email=$email LIMIT 1) LIMIT 1");
		$sel_group->execute();
		
		if($sel_group->errorCode()!=00000)
			return false;
		$group=$sel_group->fetch(PDO::FETCH_ASSOC);
		
		$sel_stud=self::getGroupCountStudents($group['id']);
		$sel_spec=self::getSpecName($group['specialty_id']);
		$sel_course=self::getCourseName($group['course_id']);
		
		
		if($sel_stud==false or $sel_spec==false or $sel_course==false)
			return false;
	
		$group['students']=$sel_stud;
		$group['specialty_id']=$sel_spec;
		$group['course_id']=$sel_course;
		
		return $group;
	}
	
	
	//метод возвращает количество студентов в группе
	private function getGroupCountStudents($group_id)
	{
		$db=$this->db();
		$group_id=$db->quote($group_id);
		
		$select=$db->prepare("SELECT count(id) FROM students WHERE group_id=$group_id");
		$select->execute();
		
		if($select->errorCode()!=00000)
			return false;
		$rows=$select->fetch(PDO::FETCH_NUM);
		return $rows[0];
		
	}
	
	//метод возвращает все тесты для студенческой группы пользователя
	public function getGroupTestsByStudent($loginfo)
	{
		$db=$this->db();
		$fio=$db->quote($loginfo['fio']);
		$email=$db->quote($loginfo['email']);
		
		$group=self::getStudentGroup($loginfo);
		
		if($group==false)
			return false;
		
		$sel_testname=$db->prepare("SELECT id, name, description, time_min FROM university_test_name WHERE group_id=$group[id]");	
		$sel_testname->execute();
		
		if($sel_testname->errorCode()!=00000)
			return false;
		else
			return $sel_testname->fetchAll(PDO::FETCH_ASSOC);
		
	}
	
	//метод возвращает список предметов для группы студента
	public function getStudentLessons($loginfo)
	{
		$db=$this->db();
		$group=self::getStudentGroup($loginfo);
		
		if($group==false)
			return false;
			
		$sel_lessons=$db->prepare("SELECT id, group_id, name FROM university_lessons WHERE group_id=$group[id]");
		$sel_lessons->execute();
		
		if($sel_lessons->errorCode()!=00000)
			return false;
		else
			return $sel_lessons->fetchAll(PDO::FETCH_ASSOC);
			
	}
	
	//метод возвращает список созданных тестов для предметов изучаемых студентом
	public function getLessonTestsByStudent($loginfo)
	{
		$db=$this->db();
		$lessons=self::getStudentLessons($loginfo);
		
		if($lessons==false)
			return false;
		
		$query="SELECT id, name, description, time_min, quantity, lesson_id FROM university_test_name WHERE ";
		
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
	
	//метод возвращает результаты прохождения тестов студентом по передаваемому параметру массиву с данными о пользователе
	public function getStudentResults($loginfo)
	{
		$db=$this->db();
		$fio=$db->quote($loginfo['fio']);
		$email=$db->quote($loginfo['email']);
		
		$results=$db->prepare("SELECT id,
(SELECT name FROM university_test_name WHERE id=test_id) AS test, 
(SELECT fio FROM students WHERE id=student_id) AS student, 
(SELECT name FROM universities WHERE id=univer_id) AS univer, 
(SELECT name FROM faculties WHERE id=faculty_id) AS faculty, 
(SELECT name FROM univer_specialty WHERE id=specialty_id) AS spec, 
(SELECT course FROM university_courses WHERE id=course_id) AS course, 
(SELECT name FROM university_groups WHERE id=group_id) AS group_name, result, date, time_min FROM university_results WHERE student_id=(SELECT id FROM students WHERE fio=$fio AND email=$email LIMIT 1)");
		$results->execute();
		
		if($results->errorCode()!=00000)
			return false;
		else return $results->fetchAll(PDO::FETCH_ASSOC);
		
	}
	
	//метод возвращает все университеты и их тесты
	public function getAllUniverTests()
	{
		$db=$this->db();
		
		$select_universities=$db->prepare("SELECT id, (SELECT name FROM cities WHERE id=city_id) AS city, (SELECT (SELECT name FROM countries WHERE id=country_id)AS country_id FROM cities WHERE id=city_id) AS country, (SELECT fio FROM users WHERE id=user_id) AS user, name, address, tel, email, site, about, image FROM universities");
		$select_tests=$db->prepare("SELECT id, university_id, (SELECT name FROM faculties WHERE id=faculty_id) AS faculty, (SELECT name FROM univer_specialty WHERE id=specialty_id) AS spec, (SELECT course FROM university_courses WHERE id=course_id)AS course, (SELECT name FROM university_groups WHERE id=group_id) AS groups, (SELECT name FROM university_lessons WHERE id=lesson_id)AS lesson, name, description, time_min, rating, date, quantity, results, count, submits FROM university_test_name");
		
		$select_universities->execute();
		$select_tests->execute();
		
		if($select_tests->errorCode()==00000 and $select_universities->errorCode()==00000)
		{
			$res['univers']=$select_universities->fetchAll(PDO::FETCH_ASSOC);
			$res['tests']=$select_tests->fetchAll(PDO::FETCH_ASSOC);
			return $res;
		}
		else return false;
		
	}
	
}

?>