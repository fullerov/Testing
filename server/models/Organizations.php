<? //модель для работы с организациями
class Organizations
{
	private function __construct(){}
	private function __clone(){}
	private function __wakeup(){}
	private function __sleep(){}
	
	//поле содержит экземпляр данного класса
	private static $object;
	
	//соединение с базой данных
	private function db()
	{
		require_once('DBconnection.php');
		return DBconnection::getDB();
	}
	
	//метод возвращающий экземпляр класса
	public static function get()
	{
		if(self::$object instanceof self)
			return self::$object;
		else
			return self::$object=new Organizations();	
	}
	
	//метод создаёт новую организацию
	public function addOrganization($user_id, $name, $about, $image, $site, $email, $tel, $city, $address)
	{
		$db=$this->db();
		$user_id=$db->quote($user_id);
		$name=$db->quote($name);
		$about=$db->quote($about);
		$image=$db->quote($image);
		$site=$db->quote($site);
		$email=$db->quote($email);
		$tel=$db->quote($tel);
		$city=$db->quote($city);
		$address=$db->quote($address);
		
		$check=$this->checkOrganization($name, $user_id, $city);
		
		if($check==true)
		{
			$query="INSERT INTO organizations (`id`,`city_id`,`user_id`,`name`,`address`,`site`,`email`,`tel`,`about`,`image`) VALUES (NULL, $city, $user_id, $name, $address, $site, $email, $tel, $about, $image)";
			$insert=$db->prepare($query);
			$insert->execute();

			if($insert->errorCode()==00000)
			 	return true;
			else return false;
		}
		else return false;
	}
	//метод для редактирования данных о организации
	public function editOrganization($user_id, $org_id, $name, $about, $image, $site, $email, $tel, $city, $address)
	{
		$db=$this->db();
		$user_id=$db->quote($user_id);
		$org_id=$db->quote($org_id);
		$name=$db->quote($name);
		$about=$db->quote($about);
		$image=$db->quote($image);
		$site=$db->quote($site);
		$email=$db->quote($email);
		$tel=$db->quote($tel);
		$city=$db->quote($city);
		$address=$db->quote($address);
		
		$insert=$db->prepare("UPDATE `organizations` SET city_id=$city, name=$name, address=$address, site=$site, email=$email, tel=$tel, about=$about, image=$image WHERE id=$org_id AND user_id=$user_id");
		$insert->execute();
		
		if($insert->errorCode()==00000)
			 return true;
		else return false;
		
		
	}
	//метод для удаления данных о организации!!!!!!!!!!!!!!!!!!!
	public function deleteOrganization($org_id, $user_id)
	{
		$db=$this->db();
		$org_id=$db->quote($org_id);
		$user_id=$db->quote($user_id);
		
		$delete=$db->prepare("DELETE FROM organizations WHERE id=$org_id AND user_id=$user_id");
		$delete_wrkrs=$db->prepare("DELETE FROM org_employers WHERE org_id=$org_id");
		$delete_wrkrs->execute();
		$delete->execute();
		
		if($delete->errorCode()==00000 and $delete_wrkrs->errorCode()==00000)
			 return true;
		else return false;
		
	}
	//метод выводит все организации из заданного диапазона значений!!!
	public function showOrganizations($first,$last)
	{	
		$db=$this->db();
		$user_id=$db->quote($user_id);
		
		$select=$db->prepare("SELECT id, city_id, user_id, name, address, site, email, tel, about, image FROM organizations WHERE id<$first AND id>last ORDER BY id");
		$select->execute();
		
		if($select->errorCode()==00000)
			 return $select->fetchAll(PDO::FETCH_ASSOC);
		else return false;
	}
	
	//метод возвращает организации пользователя!!!
	public function myOrganiztions($user_id)
	{
		$db=$this->db();
		$user_id=$db->quote($user_id);
		
		$select=$db->prepare("SELECT id, city_id, user_id, name, address, site, email, tel, about, image FROM organizations WHERE user_id=$user_id");
		$select->execute();
		
		if($select->errorCode()==00000)
			 return $select->fetchAll(PDO::FETCH_ASSOC);
		else return false;
		
	}
	
	//метод возвращает данные о названии организации пользователя и её идентификаторе
	public function myOrganiztionsNames($user_id)
	{
		$db=$this->db();
		$user_id=$db->quote($user_id);
		
		$select=$db->prepare("SELECT id, name FROM organizations WHERE user_id=$user_id");
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
	
	//метод для проверки существования аналогичной организации
	private function checkOrganization($org_name, $user_id, $city_id)
	{
		$db=$this->db();
		
		$select=$db->prepare("SELECT city_id, user_id, name FROM organizations WHERE city_id=$city_id AND user_id=$user_id AND name=$org_name");
		$select->execute();
		
		if($select->rowCount()==0)
			 return true;
		else return false;
		
	}
	
	//метод выводит информацию о сотрудниках организации
	public function getOrgWorkers($org_id)
	{
		$db=$this->db();
		$org_id=$db->quote($org_id);
		
		$select=$db->prepare("SELECT id, org_id, fio, address, date, email, tel FROM org_employers WHERE org_id=$org_id");
		$select->execute();
		
		if($select->errorCode()==00000)
			 return $select->fetchAll(PDO::FETCH_ASSOC);
		else return false;
	}
	
	//метод выводит информацию о сотрудниках добавленых пользователем
	public function getWorkers($user_id)
	{
		$db=$this->db();
		$user_id=$db->quote($user_id);
		//выборка организаций пользователя
		$select_org=$db->prepare("SELECT id FROM organizations WHERE user_id=$user_id");
		$select_org->execute();
		
		if($select_org->errorCode()==00000)
		{ 
			$org_row=$select_org->fetchAll(PDO::FETCH_ASSOC);
			
			//формирование строки запроса на выборку сотрудников организации
			if(count($org_row)>0)
			{$query_org.='SELECT id, org_id, fio, address, date, email, tel FROM org_employers WHERE ';
			$count=1;
			foreach($org_row as $org_id)
			{
				 if(count($org_row)==$count)
				 	$query_org.='org_id='.$org_id['id'].';';
				 else 
				 	$query_org.='org_id='.$org_id['id'].' OR ';
				 $count++;
			}
			
			$select_workers=$db->prepare($query_org);
			$select_workers->execute();
			
			if($select_workers->errorCode()==00000)
			{
				//возвращаем массив с данными о сотрудниках организаций пользователя
				return $select_workers->fetchAll(PDO::FETCH_ASSOC);
			}
			else return false;
			}
			else return 'Вы ещё не добавляли сотрудников!';
		}
		else return false;
	}
	
	//метод удаляет пользователя по его идентификатору
	public function deleteWorker($worker_id)
	{
		$db=$this->db();
		$worker_id=$db->quote($worker_id);
		$worker_id=$db->prepare("DELETE FROM org_employers WHERE id=$worker_id");
		$worker_id->execute();
		
		if($worker_id->errorCode()==00000)	
			 return true;
		else return false;
	}
	
	//метод добавляет нового сотрудника в БД
	public function addWorker($user_id, $fio, $address, $tel, $email, $orgs)
	{
		$db=$this->db();
		$user_id=$db->quote($user_id);
		$fio=$db->quote($fio);
		$address=$db->quote($address);
		$tel=$db->quote($tel);
		$email=$db->quote($email);
		$orgs=$db->quote($orgs);
		$date=date("Y-m-d");
		//проверка существования аналогичного сотрудника
		$check=$this->checkWorker($orgs, $fio, $email);
		
		if($check==true)
		{
			$inset=$db->prepare("INSERT INTO org_employers(`id`, `org_id`, `fio`, `address`, `date`, `email`, `tel`) VALUES(NULL, $orgs, $fio, $address, '$date', $email, $tel)");
			$inset->execute();
		
			if($inset->errorCode()==00000)
				 return true;
			else return false;
		}
		else return false;
		
		
	}
	
	//метод для проверки существования аналогичного сотрудника
	private function checkWorker($orgs, $fio, $email)
	{
		$db=$this->db();
		
		$select=$db->prepare("SELECT id FROM org_employers WHERE org_id=$orgs AND fio=$fio AND email=$email");
		$select->execute();
		
		if($select->rowCount()==0)
			 return true;
		else return false;
		
	}
	
	//метод редактирующий данные о сотруднике
	public function editWorker($worker_id, $org_id, $fio, $address, $tel, $email, $date)
	{
		$db=$this->db();
		$worker_id=$db->quote($worker_id);
		$org_id=$db->quote($org_id);
		$fio=$db->quote($fio);
		$address=$db->quote($address);
		$tel=$db->quote($tel);
		$email=$db->quote($email);
		$date=$db->quote($date);
		
		$update=$db->prepare("UPDATE `org_employers` SET org_id=$org_id, fio=$fio, address=$address, date=$date, email=$email, tel=$tel WHERE id=$worker_id");
		$update->execute();
		
		if($update->errorCode()==00000)
			 return true;
		else return false;
		
	}

	//метод возвращает массив с темами организационных тестов
	public function getTestsTypes()
	{
		$db=$this->db();
		$select=$db->prepare('SELECT id, themes FROM org_themes');
		$select->execute();
		
		if($select->errorCode()==00000)
			return $select->fetchAll(PDO::FETCH_ASSOC);
		else return false;
	}
	

	
	//метод возвращает идентификатор организации по передаваемому параметру идентификатору теста
	public function getOrgIdByTestid($test_id)
	{
		$db=$this->db();
		$test_id=$db->quote($test_id);
		
		$sel_org=$db->prepare("SELECT org_id FROM org_test_name WHERE id=$test_id LIMIT 1");
		$sel_org->execute();
		
		if($sel_org->errorCode()==00000)
			{ 
				$id=$sel_org->fetch(PDO::FETCH_NUM);
				return $id[0];
			}
		else return false;
		
	}
	
	/*метод возвращает ассоциативный массив с данными о прохождении тестов сотрудниками созданных пользователем организация по переданному параметру идентификатору пользователя*/
	public function getOrgTestResults($user_id)
	{
		$db=$this->db();
		$user_id=$db->quote($user_id);
		
		$select_orgs=$db->prepare("SELECT id FROM organizations WHERE user_id=$user_id");
		$select_orgs->execute();
		//формирование строки запроса
		$res_query="SELECT id, test_id, employee_id, org_id, result, date, time_min FROM org_results WHERE ";
		
		if($select_orgs->errorCode()==00000)
		{
			$orgs=$select_orgs->fetchAll(PDO::FETCH_ASSOC);
			
			$i=1;
			foreach($orgs as $org)
			{
				if($i==count($orgs))
					$res_query.="org_id='".$org['id']."'";
				else
					$res_query.="org_id='".$org['id']."' OR ";
				
				$i++;
			}
			$select_res=$db->prepare($res_query);
			$select_res->execute();
			
			if($select_res->errorCode()==00000)
				return $select_res->fetchAll(PDO::FETCH_ASSOC);
			else return false;
		}
		else return false;
		
	}
	
	//метод возвращает массив с названиями тестов по идентификатору пользователя
	public function getOrgTestNameById($user_id)
	{
		$db=$this->db();
		$user_id=$db->quote($user_id);
		
		$select=$db->prepare("SELECT id, name FROM org_test_name WHERE user_id=$user_id");
		$select->execute();
		
		if($select->errorCode()==00000)
			 return $select->fetchAll(PDO::FETCH_ASSOC);
		else return false;
			
	}
	
	//метод возвращает массив с названиями организаций по идентификатору пользователя
	public function getOrgNameByUserId($user_id)
	{
		$db=$this->db();
		$user_id=$db->quote($user_id);
		
		$select=$db->prepare("SELECT id, name FROM organizations WHERE user_id=$user_id");
		$select->execute();
		
		if($select->errorCode()==00000)
			 return $select->fetchAll(PDO::FETCH_ASSOC);
		else return false;
	}
	
	//метод возвращает название организации по её идентификатору
	public function getOrgNameById($id)
	{
		$db=$this->db();
		$id=$db->quote($id);
		
		$select=$db->prepare("SELECT name FROM organizations WHERE id=$id");
		$select->execute();
		
		if($select->errorCode()==00000)
		{
			$res=$select->fetch(PDO::FETCH_ASSOC);
			return $res['name'];
		}
		else return false;
	}
	
	
	//метод возвращает массив с фамилиями сотрудников по идентификатору пользователя
	public function getWorkerFioById($user_id)
	{
		$db=$this->db();
		$user_id=$db->quote($user_id);
		$query_fio="SELECT id, fio FROM org_employers WHERE ";
		$select_org=$db->prepare("SELECT id FROM organizations WHERE user_id=$user_id");
		$select_org->execute();
		
		if($select_org->errorCode()==00000)
		{
			$ids=$select_org->fetchAll(PDO::FETCH_ASSOC);
			
			$i=1;
			foreach($ids as $id)
			{
				if($i==count($ids))
					$query_fio.="org_id='".$id['id']."'";
				else
					$query_fio.="org_id='".$id['id']."' OR ";
				$i++;
			}
			
			$select_fio=$db->prepare($query_fio);
			$select_fio->execute();
			
			if($select_fio->errorCode()==00000)
				return $select_fio->fetchAll(PDO::FETCH_ASSOC);
			else return false;
			
		}
		else return false;
		
	}
	
	//метод проверяет наличие зарегистрированного сотрудника в списках организаций, если сотрудник найден возвращается массив с данными об организации!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	public function checkWorkerInOrg($worker_session)
	{
		$db=$this->db();
		$fio=$db->quote($worker_session['fio']);
		$address=$db->quote($worker_session['address']);
		$email=$db->quote($worker_session['email']);
		$tel=$db->quote($worker_session['tel']);
		
		$select=$db->prepare("SELECT id, org_id, fio, address, date, email, tel FROM org_employers WHERE fio=$fio AND address=$address AND email=$email AND tel=$tel");
		$select->execute();
		
		if($select->errorCode()==00000)
		{
			if($select->rowCount()!=0)
			{
				$org=$select->fetch(PDO::FETCH_ASSOC);
				
				$org_id=$org['org_id'];
				$select_org=$db->prepare("SELECT id, city_id, user_id, name, address, site, email, tel, about, image FROM organizations WHERE id='$org_id'");
				$select_org->execute();
				
				if($select_org->errorCode()==00000)
					 return $select_org->fetchAll(PDO::FETCH_ASSOC);
				else return false;
				
			}
			else return false;	
		}
		else return false;
	}
	
	//метод возвращает все тесты организации в виде ассоциативного массива по передаваемому параметру сессии пользователя!!!!!!!
	public function getOrgTests($worker_session)
	{
		$db=$this->db();
		$org=$this->checkWorkerInOrg($worker_session);
		
		if(is_array($org))
		{
			foreach($org as $o)
			{
				$orgid=$o['id'];
				$query.="SELECT id, name, description, date, quantity, time_min FROM org_test_name WHERE org_id='$orgid'; ";
			}	
			
			$tests=$db->prepare($query);
			$tests->execute();
			
			if($tests->errorCode()==00000)
				 return $tests->fetchAll(PDO::FETCH_ASSOC);
			else return false;
			
		}
		else return false;
	}
	
	
		//метод возвращает ссылку на организацию по передаваемому параметру идентификатору теста
	public function getOrgLinkByTestid($test_id)
	{
		$db=$this->db();
		$test_id=$db->quote($test_id);

		$sel_org=$db->prepare("SELECT org_id FROM org_test_name WHERE id=$test_id LIMIT 1");
		$sel_org->execute();

		if($sel_org->errorCode()==00000)
		{
			$id=$sel_org->fetch(PDO::FETCH_NUM);
			$select=$db->prepare("SELECT id, name FROM organizations WHERE id='$id[0]'");
			$select->execute();
		if($select->errorCode()==00000)
		{
			$rows=$select->fetch(PDO::FETCH_ASSOC);
		return '<a href="/organizations/getorg/id/'.$rows['id'].'">'.$rows['name'].'</a>';
		}
		else return false;

		}
		else return false;

	}
	//метод возвращает результаты прохождения тестов пользователем!!!!!!!!!!!!!!!!!!!!
	public function getWorkerResults($worker_session)
	{
		$db=$this->db();
		$fio=$db->quote($worker_session['fio']);
		$tel=$db->quote($worker_session['tel']);
		$email=$db->quote($worker_session['email']);
		$address=$db->quote($worker_session['address']);
		
		$select=$db->prepare("SELECT test_id, result, date, time_min FROM org_results WHERE employee_id=(SELECT id FROM org_employers WHERE fio=$fio AND tel=$tel AND email=$email AND address=$address LIMIT 1)");
		$select->execute();
		
		if($select->errorCode()==00000)
			 return $select->fetchAll(PDO::FETCH_ASSOC);
		else return false;
		
	}
	
	//метод возвращает все организации и созданные для них тесты
	public function getAllOrgTests()
	{
		$db=$this->db();
		$select_orgs=$db->prepare("SELECT id, (SELECT name FROM cities WHERE id=city_id) AS city, (SELECT (SELECT name FROM countries WHERE id=country_id)AS country_id FROM cities WHERE id=city_id) AS country, (SELECT fio FROM users WHERE id=user_id) AS user, name, address, site, email, tel, about, image FROM organizations");
		$select_tests=$db->prepare("SELECT id, (SELECT themes FROM org_themes WHERE id=theme_id) AS theme, org_id, name, description, time_min, rating, date, quantity, results, count, submits FROM org_test_name");	
		$select_orgs->execute();
		$select_tests->execute();
		
		if($select_orgs->errorCode()==00000 and $select_tests->errorCode()==00000)
		{
			$res['orgs']=$select_orgs->fetchAll(PDO::FETCH_ASSOC);
			$res['tests']=$select_tests->fetchAll(PDO::FETCH_ASSOC);
			return $res;
		}
		else return false;
	}
	
}


?>