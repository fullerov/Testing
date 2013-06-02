<?
//класс по шаблону Singletone реализующий пользовательскую бизнес-логику
class Users
{
	//поле содержащее экземпляр данного класса
	private static $user;
	
	private function __construct(){}
	private function __clone(){}
	private function __wakeup(){}
	private function __sleep(){}

	
	public static function get()
	{
		if(self::$user instanceof self)
			return self::$user;
		else
			return self::$user=new Users();
	}
	
	private function db()
	{
		require_once('DBconnection.php');
		return DBconnection::getDB();
	}
	
	//метод возвращает идентификатор последний таблицы c производит префиксный инкримент
	private function getTableId($table)
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
	
	//метод возвращает массив с данными о пользователях и созданных ими тестов
	public function getAllUsersAndTests()
	{
		$db=$this->db();
		
		$select_users=$db->prepare("SELECT id, login, (SELECT name FROM countries WHERE id=country_id) AS country, (SELECT name FROM cities WHERE id=city_id)AS city, (SELECT name FROM types WHERE id=type_id) AS type, fio, email, about, image FROM users");
		$select_users->execute();
		$select_tests=$db->prepare("SELECT id, user_id, (SELECT name FROM user_themes WHERE id=theme_id)AS theme, name, description, time_min, rating, date, quantity, results, count, submits FROM user_test_name");
		$select_tests->execute();
		
		if($select_users->errorCode()==00000 and $select_tests->errorCode()==00000)
		{
			$res['users']=$select_users->fetchAll(PDO::FETCH_ASSOC);
			$res['tests']=$select_tests->fetchAll(PDO::FETCH_ASSOC);
			return $res;
		}
		else return false;
	}
	
	//метод возвращает массив с данными о типах пользователей системы
	public function getUserTypesList()
	{
		$db=$this->db();
		
		$types=$db->prepare("SELECT id, name FROM types ORDER BY id ASC");
		$types->execute();
		
		if($types->errorCode()==00000)
		{
			return $types->fetchAll(PDO::FETCH_ASSOC);	
		}
		else return false;		
	}
	
	//метод сохраняет измененное название типа пользователя в базу данных
	public function saveTypeName($type_id, $name)
	{
		$db=$this->db();
		$type_id=$db->quote($type_id);
		$name=$db->quote($name);
		
		$update=$db->prepare("UPDATE types SET name=$name WHERE id=$type_id");
		$update->execute();
		
		if($update->errorCode()==00000)
			return true;
		else return false;
	}
	
	//метод возвращает массив с данными о всех пользователях системы
	public function getUsersList()
	{
		$db=$this->db();
		
		$select=$db->prepare("SELECT id, login, (SELECT name FROM countries WHERE id=country_id) AS country, (SELECT name FROM cities WHERE id=city_id)AS city, (SELECT name FROM types WHERE id=type_id) AS type, type_id, email, date, (SELECT count(articles.id) FROM articles WHERE user_id=users.id)AS articles, (SELECT count(user_test_name.id) FROM user_test_name WHERE user_id=users.id)AS usertests, (SELECT count(university_test_name.id) FROM university_test_name WHERE user_id=users.id)AS univertests, (SELECT count(school_test_name.id) FROM school_test_name WHERE user_id=users.id)AS schooltests, (SELECT count(org_test_name.id) FROM org_test_name WHERE user_id=users.id)AS orgtests FROM users ORDER BY id ASC");	
		$select->execute();
		
		
		if($select->errorCode()==00000)
			return $select->fetchAll(PDO::FETCH_ASSOC);
		else return false;		
	}
	
	//удаление пользователя и всех созданных им данных
	public function deleteUser($user_id, $type_id)
	{
		if($type_id==1 or $type_id==2 or $type_id==3 or $type_id==5)
		{
		$db=$this->db();
		$user_id=$db->quote($user_id);
		//удаляем комментарии к статьям
		$select_art=$db->prepare("SELECT id FROM articles WHERE user_id=$user_id");
		$select_art->execute();
		
		if($select_art->errorCode()!=00000)
			return false;
		$cmmnts="DELETE FROM comments_for_articles WHERE ";
	
		$arts=$select_art->fetchAll(PDO::FETCH_ASSOC);
		$count=count($arts);
		$num=1;
		foreach($arts as $art)
		{
			if($count==$num)
				$cmmnts.="post_id='".$art['id']."';";
			else
				$cmmnts.="post_id='".$art['id']."' OR ";
			$num++;
		}
		
		$comments=$db->prepare($cmmnts);
		
		//удаляем статьи
		$articles=$db->prepare("DELETE FROM articles WHERE user_id=$user_id");
		
		//удаляем пользовательские тесты
		$select_utests=$db->prepare("SELECT id, theme_id FROM user_test_name WHERE user_id=$user_id");
		$select_utests->execute();
		
		if($select_utests->errorCode()!=00000)
			return false;
		
		$ustqstn="DELETE FROM user_tests WHERE ";
		$utests=$select_utests->fetchAll(PDO::FETCH_ASSOC);
		$count=count($utests);
		$num=1;
		foreach($utests as $tests)
		{
			if($count==$num)
				$ustqstn.="test_id='".$tests['id']."';";
			else
				$ustqstn.="test_id='".$tests['id']."' OR ";
			$num++;
		}
		
		$uquestions=$db->prepare($ustqstn);
		$utests=$db->prepare("DELETE FROM user_test_name WHERE user_id=$user_id");
		
		$userdel=$db->prepare("DELETE FROM users WHERE id=$user_id");
		
		$comments->execute();
		$articles->execute();
		$uquestions->execute();
		$utests->execute();
		$userdel->execute();
		if($comments->errorCode()==00000 or $articles->errorCode()==00000 or $uquestions->errorCode()==00000 or $utests->errorCode()==00000 or $userdel->errorCode()==00000)
				return true;
		else 	return false;
		
		}
		//удаление организационных тестов
		elseif($type_id==6)
		{
			return false;
		}
		//удаление ученических тестов
		elseif($type_id==4)
		{
			return false;
		}
		
		
	}
}
?>