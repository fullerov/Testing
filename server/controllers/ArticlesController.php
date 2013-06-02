<?
//контроллер вызываемый по запросу /articles
class ArticlesController implements IController
{
	function indexAction()
	{
		//вывод контента в вид статей
		$fc=FrontController::get();
		$view=new View();
		//вывод всех статей из базы данных
		$view->content=Articles::get()->getList();
		$view->count=Articles::get()->getArticlesCount();
		$view->keywords='Дистанционное тестирование, тестирование школьников, тестирование студентов, тестирование сотрудников, онлайн, тестирование, пройти тестирование, создать тест, о ИС, описание, статьи';
		$view->description='Информационная система дистанционного тестирования знаний, для студентов, пользователей, школьников и сотрудников предприятий. Вы можете легко создать или пройти тест...';
		$view->title='Список статей';
		$result=$view->render('../views/articles.php');
		$fc->setBody($result);
	}
	
	//вывод запрашиваемой статьи из базы
	function getAction()
	{
		$fc=FrontController::get();
		$url=$fc->getParams();
		$view=new View();
		//возваращаем запрашиваемую статью из базы в виде ассоциативного массива
		$view->content=Articles::get()->getArticle((int)$url['id']);
		$view->id=(int)$url['id'];
		//выборка комментариев к статье
		$view->cmnts=Articles::get()->getComments((int)$url['id']);

		(empty($view->content['title'])) ? $view->title='Такой статьи нет': $view->title=$view->content['title'];
		(empty($view->content['meta_key'])) ? $view->keywords='' : $view->keywords=$view->content['meta_key'];
		(empty($view->content['meta_description'])) ? $view->description='' : $view->description=$view->content['meta_description'];
		
		$result=$view->render('../views/article.php');
		$fc->setBody($result);
	}
	
	//вывод статей пользователя
	function myarticlesAction()
	{	
		if(isset($_SESSION['loginfo']))
		{
		//инициализация контроллера и модели вида
		$fc=FrontController::get();
		$view=new View();
		//возваращаем статьи пользователя из базы в виде ассоциативного массива
		$view->content=Articles::get()->getMyArticles($_SESSION['loginfo']['id']);
		$view->articles=true;
		
		//проверка на наличие пришедших данных
		if(is_array($view->content))
		{
			$view->title='Все статьи: '.$_SESSION['loginfo']['login'];
			$view->keywords='Все статьи пользователя';
			$view->description='Все статьи, статьи пользователя';
			//вывод данных в представление
			$result=$view->render('../views/edit.php');
		}
		else
		{
			$view->title='У Вас нет статей';
			$view->keywords='';
			$view->description='';
			$view->content='Вы еще не создали ни одной статьи!<br><span id="add_test_error"><a href="/articles/addarticle"><< К форме создания статей</a></span>';
			//вывод данных в представление
			$result=$view->render('../views/default.php');
			
		}
		
		
		$fc->setBody($result);
		}
		else
		{header('Location: ../');}
	} 
	
	//метод для редактирования статьи пользователя
	function editarticleAction()
	{
		if(isset($_SESSION['loginfo']))
		{
		//инициализация контроллера и модели вида
		$fc=FrontController::get();
		$view=new View();
		
		//пользователь выбрал редактирование статьи
		if(isset($_POST['edit']))
		{
			//проверка существования необходимых данных
			if(isset($_POST['article_id']) and !empty($_POST['title']) and !empty($_POST['description']) and !empty($_POST['text']) and !empty($_POST['keywords']) and !empty($_POST['image']))
			{
				$view->content=Articles::get()->editArticle($_POST['article_id'], $_SESSION['loginfo']['id'], $_POST['text'], $_POST['title'], $_POST['description'], $_POST['keywords'], $_POST['image']);
				($view->content) ? $view->content='Статья успешно изменена!' : $view->content='Возникла ошибка при изменении статьи!';
			}
			else
				$view->content='Поля формы не должны содержать пустые значения!';
				
			$view->content.='<br><span id="add_test_error"><a href="/articles/myarticles"><< Вернутся назад к моим статьям</a></span>';
		}
		//пользователь выбрал удаление статьи
		elseif(isset($_POST['delete']))
		{
			//проверка существования необходимых данных
			if(!empty($_POST['article_id']))
			{
				//вызываем метод класса и передаем ему параметры для удаляния статьи пользователя
				$view->content=Articles::get()->delArticle($_POST['article_id'], $_SESSION['loginfo']['id']);
				($view->content) ? $view->content='Ваша статья успешно удалена!' : $view->content='При удалении Вашей статьи возникли ошибки!';
				 $view->content.='<br><span id="add_test_error"><a href="/articles/myarticles"><< Вернутся назад к редактированию статьи</a></span>';
			}
			else 
				 $view->content='Необходимые данные для удаления статьи не найдены<br><span id="add_test_error"><a href="/articles/myarticles"><< Вернутся назад к редактированию статьи</a></span>';
			
			
		}
		else //пользователь зашел по прямой ссылке
		{header('Location: ../');}

		$view->title='Редактирование статьи';
		$view->keywords='Результат редактирования статьи пользователя';
		$view->description='Редактирование сататьи, статьи пользователя';
		$result=$view->render('../views/default.php');
		$fc->setBody($result);
}
		else{header('Location: ../');}
	}
	
	//метод добавления новой статьи
	function addarticleAction()
	{
		if(isset($_SESSION['loginfo']))
		{
		//инициализация контроллера и модели вида
		$fc=FrontController::get();
		$view=new View();
			//действия при нажатии кнопки "Добавить"
			if(isset($_POST['add']))
			{	//проверка корректности введенных пользователем значений
				if(!empty($_POST['title']) and !empty($_POST['description']) and !empty($_POST['keywords']) and !empty($_POST['text']) and !empty($_POST['image']))
				{
				//оправление данных модели и получение ответа true или false
				$view->content=Articles::get()->addArticle($_SESSION['loginfo']['id'], $_POST['title'], $_POST['description'], $_POST['keywords'], $_POST['text'], $_POST['image']);
				//проврка пришедшего результата и вывод соответствующего сообщения
				((int)$view->content!=0) ? header('Location: /articles/get/id/'.$view->content) : $view->content='При добавлении статьи возникли ошибки!<br><span id="add_test_error"><a href="/articles/addarticle"><< Вернутся назад к созданию статьи</a></span>';		
				}
				else
				{
				$view->content='Все поля должны быть заполнены!';
				$view->content.='<br><span id="add_test_error"><a href="/articles/addarticle"><< Вернутся назад к созданию статьи</a></span>';   }
				
				$view->title='Добавление статьи...';
				$view->keywords='Добавлена статья пользователя';
				$view->description='Добавление сататьи, статьи пользователя';
				
			}
			//вывод формы для добавления теста
			else
			{
				$view->content='addnew';
				$view->title='Добавление статьи';
				$view->description='Добавление статьи пользователя';
				$view->keywords='Добавление сататьи, статьи пользователя';
			}
		
		$result=$view->render('../views/article.php');
		$fc->setBody($result);
		}
		else{header('Location: ../');}
	}
	
	//метод реализующий редактирование комментариев к статье
	function editcommentsAction()
	{
		if(isset($_SESSION['loginfo']))
		{
		//инициализация контроллера и модели вида
		$fc=FrontController::get();
		$view=new View();
		$url=$fc->getParams();
		//удаление комментариев пользователя
		if(isset($_POST['delete']))
		{
			if(!empty($_POST['article_id']) and !empty($_POST['comment_id']))
			{
				$view->content=Articles::get()->deleteComment($_POST['comment_id'], $_POST['article_id']);
				($view->content) ? $view->content='Комментарий успешно удален!' : $view->content='Ошибка при удалении комментария!';
			}
			else 
				$view->content='Необходимых данных для удаления комментария не найдено!';
				
			$view->content.='<br><span id="add_test_error"><a href="/articles/myarticles"><< Вернутся назад к моим статьям</a></span>';
			$view->title='Удаление комментария...';
			$result=$view->render('../views/default.php');
		}
		//редактирование комментария пользователя
		elseif(isset($_POST['edit']))
		{
			if(!empty($_POST['comment_txt']) and !empty($_POST['article_id']) and !empty($_POST['comment_id']))
			{
				$view->content=Articles::get()->editComment($_POST['comment_id'], $_POST['article_id'], $_POST['comment_txt']);
				($view->content) ? $view->content='Комментарий успешно изменён!' : $view->content='Ошибка при изменении комментария!';
			}
			else
				$view->content='Необходимых данных для редактирования комментария не найдено!';
			
			$view->content.='<br><span id="add_test_error"><a href="/articles/myarticles"><< Вернутся назад к моим статьям</a></span>';
			$view->title='Редактирование комментария...';
			$result=$view->render('../views/default.php');
		}
		//выборка комментариев к статье
		else
		{
			$view->comments=Articles::get()->getComments((int)$url['article']);
			$view->num=(int)$url['article'];
			$view->title='Редактирование комментариев';
			$result=$view->render('../views/edit.php');
		}
		$view->keywords='Редактирование комментариев к статье';
		$view->description='Редактирование комментариев, комментарии к статье';
		$fc->setBody($result);
		}
		else{header('Location: ../');}
	}
	
	//оценка статьи пользователем
	function voteAction()
	{
		$fc=FrontController::get();
		$view=new View();
		
		if(!empty($_POST['mark']) and !empty($_POST['id']))
		{
			if(!isset($_SESSION['votes']['article'.$_POST['id']]))
			{
				$view->content=Articles::get()->setArticleVote($_POST['id'], $_POST['mark']);
				($view->content)? $view->content='Ваш голос учтён!': $view->content='Возникла ошибка при голосовании!';
			}
			else 
				$view->content='Вы уже голосовали за данную статью!';
		}
		else
			$view->content='Необходимые данные для оценки статьи не найдены!';
		
		$view->content.='<br><h4><a href="/articles">< < К списку статей</a></h4>';
		
		$view->title='Оценка статьи...';
		$result=$view->render('../views/default.php');
		$fc->setBody($result);
			
	}
	
	
	//добавление нового комментария к статье
	function addcommentAction()
	{
		$fc=FrontController::get();
		$view=new View();
		
		if(isset($_POST['addcomment']))
		{
			if($_SESSION['captcha']==crypt($_POST['captcha'],'x)p_q1'))
			{
				if(!empty($_POST['comment_txt']) and !empty($_POST['name']))
				{
					if(!empty($_POST['article_id']))  
					{
						$check=Articles::get()->checkComment($_POST['article_id'], $_POST['comment_txt'], $_POST['name']);
						if($check=='0')
						{
							$view->content=Articles::get()->addComment($_POST['article_id'], $_POST['comment_txt'], $_POST['name']);						    
							($view->content) ? $view->content='Ваш комментарий успешно добавлен!' : $view->comments='Ошибка при вставке комментария!';		
						}
						else
							$view->content='Данный комментарий уже добавлен!';
					}
					
					else 
						$view->content='Вы пытаетесь вставить комментарий не в ту статью!';
				}
				else
					$view->content='Поля не должны содержать пустых значений!';
			}
			else
			{
				$view->content='Вы ввели символы с картинки некорректно!';
			}
			
		}
		else 
			$view->content='Необходимые данные для добавления комментария не найдены!';
		
		$view->content.='<br><h4><a href="/articles/get/id/'.$_POST['article_id'].'#comments">< < К списку комментариев статьи</a></h4>';
		$view->title='Добавление комментария...';
		$result=$view->render('../views/default.php');
		$fc->setBody($result);
	}
}

?>