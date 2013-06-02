<?
//модель авторизации пользователя
class Check
{
	private $result;
	
	function __construct($name,$password)
	{

		//подключение класса для соединения с базой данных	
		require_once('DBconnection.php');
		$db=DBconnection::getDB();
		//шифровка пароля для проверки
		$password=crypt($password,'w16_5N-');
		//проверка наличия пользователя в базе
		$check=$db->query("SELECT id FROM users WHERE login='$name'");
		;
		if($check->rowCount()==1)
		 { 
		 	 //выборка текущего пользователя с паролем из базы
			 $row=$check->fetch(PDO::FETCH_NUM);
			 $user=$db->query("SELECT * FROM users WHERE id='$row[0]' AND password='$password'");
			 $assoc=$user->fetch(PDO::FETCH_ASSOC);
			 
			 //если пароль корректный возвращается сериализованный массив
			 if(!empty($assoc['password']))
			 {
				 $this->result=$assoc;
			 }
			 else
			 {$this->result='Некорректный пароль!';}
		 }
	    else{$this->result='Пользователя '.$name.' нет в базе!';}

		
	}
	//в зависимости от результата проверки возвращает либо текст ошибки, либо массив
	public function getResult()
	{
		return $this->result;
	}
	
	
	
}

?>