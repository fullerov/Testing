<?
//метод реализующий функциональность по выводу, редактированию, удалению и добавлению статьей - шаблон Singleton
class Articles
{
	private function __construct(){}
	private function __clone(){}
	private function __wakeup(){}
	private function __sleep(){}
	
	//поле класса хранит ссылку на экземпляр объекта
	private static $instance;
	
	//метод возвращающий экземпляр класса
	public static function get()
	{
		if(self::$instance instanceof self)
			return self::$instance;
		else
		{
			self::$instance=new Articles();
			return self::$instance;
		}
		
	}
	
	//подключение к базе данных
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
	
	//метод возвращает массив со всеми статьями
	public function getList()
	{
		$db=$this->db();
		$query=$db->prepare('SELECT id, user_id, title, date, meta_description, img, rating, votes, count FROM articles ORDER BY id DESC');
		$query->execute();
		
		if($query->rowCount()!=0)
		{
			return $query->fetchAll(PDO::FETCH_ASSOC);
		}
		else
		{
			return 'В базе данных нет ни одной статьи!';
		}
		
	}
	
	//метод возвращает статью по переданному идентификатору
	public function getArticle($id)
	{
		if(is_int($id))
		{
			$db=$this->db();
			$query=$db->prepare("SELECT id, user_id, title, meta_description, meta_key, date, text, img, rating, votes, count FROM articles WHERE id='$id'");
			$query->execute();
	
			if($query->rowCount()!=0)
			{
				return $query->fetch(PDO::FETCH_ASSOC);
			}
			else
			{
				return 'В базе данных нет такой статьи!';
			}
		}
		else{return 'Параметр должен быть целочисленный!';}
	}
	
	//метод добавляющий новую статью пользователя
	public function addArticle($user_id, $title, $description, $keywords, $text, $image)
	{
		$db=$this->db();
		$user_id=$db->quote($user_id);
		$title=$db->quote(htmlspecialchars(trim($title)));
		$description=$db->quote(htmlspecialchars(trim($description)));
		$keywords=$db->quote(htmlspecialchars(trim($keywords)));
		$text=$db->quote($text);
		$image=$db->quote(trim($image));
		$date=date("Y-m-d");
		//возвращаем наибольший идентификатор из таблицы статьей
		$id=$this->getTableId('articles');
		
		$insert=$db->prepare("INSERT INTO articles(`id`, `user_id`, `title`, `meta_description`, `meta_key`, `text`, `date`, `img`, `rating`, `votes`, `count`) VALUES ('$id', $user_id, $title, $description, $keywords, $text, '$date', $image, '0', '0', '0')");
		$insert->execute();
		
		if($insert->errorCode()==00000)
			 return $id;
		else return false;
	}
	
	//метод для удаления статьи пользователя по передаваемому индентификатору
	public function delArticle($article_id,$user_id)
	{
		$db=$this->db();
		$article_id=$db->quote($article_id);
		$user_id=$db->quote($user_id);
		
		$delete=$db->prepare("DELETE FROM articles WHERE id=$article_id AND user_id=$user_id");
		$delete_comments=$db->prepare("DELETE FROM comments_for_articles WHERE post_id=$article_id");
		$delete_comments->execute();
		$delete->execute();
		
		if($delete->errorCode()==00000 and $delete_comments->errorCode()==00000)
			 return true;
		else return false;
		
	}
	
	//метод для редактирование статьи пользователя
	public function editArticle($id, $user_id, $text, $title, $description, $keywords, $image)
	{
		$db=$this->db();
		$id=$db->quote($id);
		$user_id=$db->quote($user_id);
		$text=$db->quote($text);
		$title=$db->quote(htmlspecialchars(trim($title)));
		$description=$db->quote(htmlspecialchars(trim($description)));
		$keywords=$db->quote(htmlspecialchars(trim($keywords)));
		$image=$db->quote(trim($image));
		
		$update=$db->prepare("UPDATE articles SET text=$text, title=$title, meta_description=$description, meta_key=$keywords, img=$image WHERE user_id=$user_id AND id=$id");
		$update->execute();
		
		if($update->errorCode()==00000)
		{
			return true;
		}
		else return false;
	}
	
	//метод возвращает все статьи пользователя
	public function getMyArticles($id)
	{
		$db=$this->db();
		$id=$db->quote($id);
		//формирование строки запроса на выборку данных
		$select=$db->prepare("SELECT id, user_id, title, meta_description, meta_key, text, date, img, rating, votes, count FROM articles WHERE user_id=$id");
		$select->execute();
		
		//если ответ верный возвращаем ассоциативный массив с данными иначе ложь
		if($select->rowCount()!=0)
		{
			return $select->fetchAll(PDO::FETCH_ASSOC);
		}
		else return false;
		
	}
	
	//метод выводит все комментарии к статье
	public function getComments($article_id)
	{
		$db=$this->db();
		$article_id=$db->quote($article_id);
		
		$select=$db->prepare("SELECT id, post_id, author, text, date FROM comments_for_articles WHERE post_id=$article_id");
		$select->execute();
		
		if($select->errorCode()==00000)
		{
			return $select->fetchAll(PDO::FETCH_ASSOC);
		}
		else return false;	
	}
	
	//метод добавляет новый комментарий к статье
	public function addComment($article_id, $comment_txt, $name)
	{
		$db=$this->db();
		$article_id=$db->quote($article_id);
		$comment_txt=$db->quote(htmlspecialchars(trim($comment_txt)));
		$name=$db->quote(htmlspecialchars(trim($name)));
		$date=date("Y-m-d");
		
		$id=$this->getTableId('comments_for_articles');
		
		$add=$db->prepare("INSERT INTO comments_for_articles(`id`, `post_id`, `author`, `text`, `date`) VALUES($id, $article_id, $name, $comment_txt, '$date')");
		$add->execute();
		
		if($add->errorCode()==00000)
		{
			return true;
		}
		else return false;
	
	}
	
	//проверка наличия аналогичного комментария в базе
	public function checkComment($article_id, $comment_txt, $name)
	{
		$db=$this->db();
		$article_id=$db->quote($article_id);
		$comment_txt=$db->quote(htmlspecialchars(trim($comment_txt)));
		$name=$db->quote(htmlspecialchars(trim($name)));
		
		$select=$db->prepare("SELECT id FROM comments_for_articles WHERE post_id=$article_id AND text=$comment_txt AND author=$name");		
		$select->execute();
		
		if($select->errorCode()==00000)
		{
			return count($select->fetchAll(PDO::FETCH_NUM));
		}
		else return false;
		
		
	}
	
	//метод удаляет комментарий пользователя по передаваемым параметрам идентификатору коментария и идентификатору статьи
	public function deleteComment($comment_id, $article_id)
	{
		$db=$this->db();
		$article_id=$db->quote($article_id);
		$comment_id=$db->quote($comment_id);
		
		$delete=$db->prepare("DELETE FROM comments_for_articles WHERE id=$comment_id AND post_id=$article_id");
		$delete->execute();
		
		if($delete->errorCode()==00000)
			return true;
		else return false;
	}
	
	//метод сохраняет изменение автора статьи в комментарии пользователя
	public function editComment($comment_id, $article_id, $text)
	{
		$db=$this->db();
		$comment_id=$db->quote($comment_id);
		$article_id=$db->quote($article_id);
		$text=$db->quote(htmlspecialchars(trim($text)));
		
		$update=$db->prepare("UPDATE comments_for_articles SET text=$text WHERE id=$comment_id AND post_id=$article_id");
		$update->execute();
		
		if($update->errorCode()==00000)
			 return true;
		else return false;
	}
	
	//метод обновляет счетчик просмотров страницы
	public function addCountArticles($id)
	{
		$db=$this->db();
		$_SESSION['readart']['id'.$id]=true;
		
		$id=$db->quote($id);
		
		$select=$db->prepare("SELECT count FROM articles WHERE id=$id");
		$select->execute();

		if($select->errorCode()!=00000)
			return false;
			
		$rows=$select->fetch(PDO::FETCH_ASSOC);
		$rows['count']=(int)$rows['count']+1;
		
		$update=$db->prepare("UPDATE articles SET count='$rows[count]' WHERE id=$id");
		$update->execute();
	}
	
	//метод добавляет оценку статьи в базу данных
	public function setArticleVote($id, $mark)
	{
		$db=$this->db();
		$_SESSION['votes']['article'.$id]=true;
		
		$id=$db->quote($id);
		
		$select=$db->prepare("SELECT rating, votes FROM articles WHERE id=$id");
		$select->execute();
		
		if($select->errorCode()!=00000)
			return false;
		
		$rows=$select->fetch(PDO::FETCH_ASSOC);
		$rows['votes']=(int)$rows['votes']+1;
		$rows['rating']=((int)$rows['rating']+(int)$mark)/(int)$rows['votes'];
		
		$update=$db->prepare("UPDATE articles SET votes=$rows[votes], rating=$rows[rating] WHERE id=$id");
		$update->execute();
		
		if($update->errorCode()==00000)
			return true;
		else return false;
	}
	
	//метод возвращает количество статей
	public function getArticlesCount()
	{
		$db=$this->db();
		
		$select=$db->prepare("SELECT count(id) FROM articles");
		$select->execute();
		
		if($select->errorCode()==00000)
		{
			$res=$select->fetch(PDO::FETCH_NUM);
			return $res[0];
		}
		else return 'Ошибка при выборке!';
			
	}
}


?>