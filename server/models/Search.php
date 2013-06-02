<?
//модель по шаблону Singletone для поиска информации в системе
class Search
{
	//поле содержащее экземпляр данного класса
	private static $search;
	
	private function __construct(){}
	private function __clone(){}
	private function __wakeup(){}
	private function __sleep(){}

	
	public static function get()
	{
		if(self::$search instanceof self)
			return self::$search;
		else
			return self::$search=new Search();
	}
	
	private function db()
	{
		require_once('DBconnection.php');
		return DBconnection::getDB();
	}
	
	//метод возвращает результат поиска тестов по названию и описанию в виде ассоциативного массива
	public function searchTestsByNames($text)
	{
		$db=$this->db();
		$text=$db->quote('%'.$text.'%');

		$select_user=$db->prepare("SELECT id, name, (SELECT fio FROM users WHERE id=user_id) AS fio, description, (SELECT name FROM countries WHERE id=country_id) AS country_id, (SELECT name FROM cities WHERE id=city_id) AS city_id, time_min, rating, date, quantity, results, count, submits FROM user_test_name WHERE name LIKE $text OR description LIKE $text ORDER BY id DESC");
		$select_org=$db->prepare("SELECT id, name, (SELECT fio FROM users WHERE id=user_id) AS fio, description, (SELECT name FROM countries WHERE id=country_id) AS country_id, (SELECT name FROM cities WHERE id=city_id) AS city_id, time_min, rating, date, quantity, results, count, submits FROM org_test_name WHERE name LIKE $text OR description LIKE $text ORDER BY id DESC");
		$select_school=$db->prepare("SELECT id, name, (SELECT fio FROM users WHERE id=user_id) AS fio, description, (SELECT name FROM countries WHERE id=country_id) AS country_id, (SELECT name FROM cities WHERE id=city_id) AS city_id, time_min, rating, date, quantity, results, count, submits FROM school_test_name WHERE name LIKE $text OR description LIKE $text ORDER BY id DESC");
		$select_univer=$db->prepare("SELECT id, name, (SELECT fio FROM users WHERE id=user_id) AS fio, description, (SELECT name FROM countries WHERE id=country_id) AS country_id, (SELECT name FROM cities WHERE id=city_id) AS city_id, time_min, rating, date, quantity, results, count, submits FROM university_test_name WHERE name LIKE $text OR description LIKE $text ORDER BY id DESC");
		
		$select_org->execute();
		$select_school->execute();
		$select_univer->execute();
		$select_user->execute();
		
		if($select_org->errorCode()==00000 and $select_school->errorCode()==00000 and $select_univer->errorCode()==00000 and $select_user->errorCode()==00000)
		{
			$res['user']=$select_user->fetchAll(PDO::FETCH_ASSOC);
			$res['org']=$select_org->fetchAll(PDO::FETCH_ASSOC);
			$res['univer']=$select_univer->fetchAll(PDO::FETCH_ASSOC);
			$res['school']=$select_school->fetchAll(PDO::FETCH_ASSOC);
			return $res;
		}
		else return false;
		
	}
	
	//метод возвращает результат поиска тестов по дате в виде ассоциативного массива
	public function searchTestsByDate($date)
	{
		$db=$this->db();
		$date=$db->quote('%'.$date.'%');

		$select_user=$db->prepare("SELECT id, name, (SELECT fio FROM users WHERE id=user_id) AS fio, description, (SELECT name FROM countries WHERE id=country_id) AS country_id, (SELECT name FROM cities WHERE id=city_id) AS city_id, time_min, rating, date, quantity, results, count, submits FROM user_test_name WHERE date LIKE $date ORDER BY id DESC");
		$select_org=$db->prepare("SELECT id, name, (SELECT fio FROM users WHERE id=user_id) AS fio, description, (SELECT name FROM countries WHERE id=country_id) AS country_id, (SELECT name FROM cities WHERE id=city_id) AS city_id, time_min, rating, date, quantity, results, count, submits FROM org_test_name WHERE date LIKE $date ORDER BY id DESC");
		$select_school=$db->prepare("SELECT id, name, (SELECT fio FROM users WHERE id=user_id) AS fio, description, (SELECT name FROM countries WHERE id=country_id) AS country_id, (SELECT name FROM cities WHERE id=city_id) AS city_id, time_min, rating, date, quantity, results, count, submits FROM school_test_name WHERE date LIKE $date ORDER BY id DESC");
		$select_univer=$db->prepare("SELECT id, name, (SELECT fio FROM users WHERE id=user_id) AS fio, description, (SELECT name FROM countries WHERE id=country_id) AS country_id, (SELECT name FROM cities WHERE id=city_id) AS city_id, time_min, rating, date, quantity, results, count, submits FROM university_test_name WHERE date LIKE $date ORDER BY id DESC");
		
		$select_org->execute();
		$select_school->execute();
		$select_univer->execute();
		$select_user->execute();
		
		if($select_org->errorCode()==00000 and $select_school->errorCode()==00000 and $select_univer->errorCode()==00000 and $select_user->errorCode()==00000)
		{
			$res['user']=$select_user->fetchAll(PDO::FETCH_ASSOC);
			$res['org']=$select_org->fetchAll(PDO::FETCH_ASSOC);
			$res['univer']=$select_univer->fetchAll(PDO::FETCH_ASSOC);
			$res['school']=$select_school->fetchAll(PDO::FETCH_ASSOC);
			return $res;
		}
		else return false;
		
	}
	
	//метод возвращает результат поиска статей в виде ассоциативного массива
	public function searchArticles($text)
	{
		$db=$this->db();
		$text=$db->quote('%'.$text.'%');

		$select=$db->prepare("SELECT id, (SELECT fio FROM users WHERE id=user_id) AS user, title, meta_description, date, img, rating, votes, count FROM articles WHERE title LIKE $text OR meta_description LIKE $text OR meta_key LIKE $text ORDER BY id DESC");
		$select->execute();

		if($select->errorCode()==00000)
			return $select->fetchAll(PDO::FETCH_ASSOC);
		else return false;
		
	}
	
	//метод выводит все пользовательские тесты по данной тематике
	public function getUserTests($theme_id)
	{
		$db=$this->db();
		$theme_id=$db->quote($theme_id);
		
		$select_theme=$db->prepare("SELECT name, description FROM user_themes WHERE id=$theme_id");
		
		$select=$db->prepare("SELECT id, name, (SELECT fio FROM users WHERE id=user_id) AS fio, description, (SELECT name FROM countries WHERE id=country_id) AS country_id, (SELECT name FROM cities WHERE id=city_id) AS city_id, time_min, rating, date, quantity, results, count, submits FROM user_test_name WHERE theme_id=$theme_id ORDER BY id DESC");
		$select->execute();
		$select_theme->execute();
		
		if($select->errorCode()==00000 and $select_theme->errorCode()==00000)
		{	
			$res['tests']=$select->fetchAll(PDO::FETCH_ASSOC);
			$res['theme']=$select_theme->fetch(PDO::FETCH_ASSOC);
			return $res;
		}
		else return false;
		
	}
	
	//метод выводит все организационные тесты по данной тематике
	public function getOrgTests($theme_id)
	{
		$db=$this->db();
		$theme_id=$db->quote($theme_id);
		
		$select_theme=$db->prepare("SELECT themes, description FROM org_themes WHERE id=$theme_id");
		
		$select=$db->prepare("SELECT id, name, (SELECT fio FROM users WHERE id=user_id) AS fio, description, (SELECT name FROM countries WHERE id=country_id) AS country_id, (SELECT name FROM cities WHERE id=city_id) AS city_id, time_min, rating, date, quantity, results, count, submits FROM org_test_name WHERE theme_id=$theme_id ORDER BY id DESC");
		$select->execute();
		$select_theme->execute();
		
		if($select->errorCode()==00000 and $select_theme->errorCode()==00000)
		{	
			$res['tests']=$select->fetchAll(PDO::FETCH_ASSOC);
			$res['theme']=$select_theme->fetch(PDO::FETCH_ASSOC);
			return $res;
		}
		else return false;
		
	}
	
	//метод возвращает массив с категориями пользовательских тестов
	public function getUserThemes()
	{
		$db=$this->db();	
		$select=$db->prepare("SELECT id, name FROM user_themes");
		$select->execute();
		
		if($select->errorCode()==00000)
			return $select->fetchAll(PDO::FETCH_ASSOC);
		else return false;
		
	}
	
	//метод возвращает массив с категориями организационных тестов
	public function getOrgThemes()
	{
		$db=$this->db();	
		$select=$db->prepare("SELECT id, themes FROM org_themes");
		$select->execute();
		
		if($select->errorCode()==00000)
			return $select->fetchAll(PDO::FETCH_ASSOC);
		else return false;
		
	}
	
	//метод возвращает количество созданных тестов, статей, пользователей, университетов, школ, организаций 
	public function getCountStat()
	{
		$db=$this->db();
		
		$select=$db->prepare('SELECT (SELECT count(id) FROM articles) AS articles, (SELECT count(id) FROM users) AS users, (SELECT count(id) FROM organizations) AS orgs, (SELECT count(id) FROM universities) AS univers, (SELECT count(id) FROM schools) AS schools, (SELECT count(id) FROM user_test_name)AS usertests, (SELECT count(id) FROM university_test_name)AS univertests, (SELECT count(id) FROM school_test_name)AS schooltests, (SELECT count(id) FROM org_test_name) AS orgtests, (SELECT count(id) FROM countries)AS countries, (SELECT count(id) FROM cities)AS cities, (SELECT count(id) FROM comments_for_articles)AS comments, (SELECT count(id) FROM org_employers) AS employers, (SELECT count(id) FROM pupils)AS pupils, (SELECT count(id) FROM students) AS students');	
		$select->execute();
		
		if($select->errorCode()==00000)
			return $select->fetchAll(PDO::FETCH_ASSOC);
		else return false;
		
	}
	
	//метод возвращает средний результат прохождения тестов
	public function getResultStat()
	{
		$db=$this->db();
		
		$select=$db->prepare('SELECT (SELECT avg(results) FROM university_test_name) univerresults, (SELECT avg(results) FROM user_test_name)AS userresults, (SELECT avg(results) FROM school_test_name)AS schoolresults, (SELECT avg(results) FROM org_test_name)AS orgresults, (SELECT avg(rating) FROM articles)AS artrating');	
		$select->execute();
		
		if($select->errorCode()==00000)
			return $select->fetchAll(PDO::FETCH_ASSOC);
		else return false;
	}
}
?>