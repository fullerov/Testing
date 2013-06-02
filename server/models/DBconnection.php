<?
//класс DBСonnection для соединения с базой данных
//подключение файла с конигурацией
require_once('config.inc.php');
class DBconnection
{
	private static $HOST=HOST;
	private static $USER=USERNAME;
	private static $PASSWORD=PASSWORD;
	private static $DB_NAME=DBNAME;
	
	private static $db=NULL;
	
	private function __construct(){}
	private function __clone(){}
	private function __wakeup(){}
	private function __sleep(){}

	
	function __destruct()
	{
		unset(self::$db);
	}

	static function getDB()
	{
		if(self::$db==NULL)
			 self::$db=new PDO('mysql:host='.self::$HOST.';dbname='.self::$DB_NAME.'',self::$USER,self::$PASSWORD);
			 
		 return self::$db;
	}

}

?>