<? 
//контроллер вызываемый по запросу /create
class CreateController implements IController
{
	//действие вызываемое изначально
	function indexAction()
	{//если пользователь вернулся на текущую страницу не создав тест, удаляем переменную в сессии
		if(isset($_SESSION['test']))
		{unset($_SESSION['test']);}
		
		$fc=FrontController::get();
		//создание экземпляра модели "вида"
		$view=new View();
		$view->keywords='Дистанционное тестирование, тестирование школьников, тестирование студентов, тестирование сотрудников, онлайн, тестирование, пройти тестирование, создать тест, о ИС, описание';
		$view->description='Информационная система дистанционного тестирования знаний, для студентов, пользователей, школьников и сотрудников предприятий. Вы можете легко создать или пройти тест...';
		$view->title='Создать тест';
		
		//если пользователь не зарегистрирован выводим предупреждение, иначе передаём данные виду с формой для создания тестов
		if(is_array($_SESSION['loginfo']) and !empty($_SESSION['loginfo']))
		{
			$result=$view->render('../views/create.php');
		}
		else
		{ 
			$view->content='<span id="create_guest">Только <a title="Зарегистрироватся" href="/registration">зарегистрировавшиеся</a> пользователи могут создавать тесты!</span>';
			$result=$view->render('../views/default.php');
		}
		$fc->setBody($result);
	}
	
	//действие вызываемое при создании пользовательского теста, вывод формы для добавления вопросов
	function usertestAction()
	{
		$fc=FrontController::get();
		$view=new View();
		$view->title='Добавление вопросов';

		//проверка существование отправленных формой переменных
		if(isset($_POST['test_name']) and isset($_POST['user_id']) and isset($_POST['country_id']) and isset($_POST['city_id']) and isset($_POST['test_description']) and isset($_POST['test_question']) and isset($_POST['test_min']) and isset($_POST['test_theme'])  and isset($_POST['test_new_theme']) and isset($_POST['test_var']))
		{
			//если параметры не пусты создаем сессию с пришедшими данными
			if(!empty($_POST['test_name']) and !empty($_POST['user_id']) and !empty($_POST['country_id']) and !empty($_POST['city_id']) and !empty($_POST['test_description']) and !empty($_POST['test_min']) and !empty($_POST['test_theme']) and !empty($_POST['test_var']) and !empty($_POST['test_description']))
			{
				
				//если пользователь не нашел нужной темы, то добавляем тему пользователя
				if(!empty($_POST['test_new_theme']))
				{
					$new_theme_id=Create::addNewTheme(htmlspecialchars(trim($_POST['test_new_theme'])), htmlspecialchars(trim($_POST['new_theme_descr'])), 'user');
					if((int)$new_theme_id==0)
						echo 'Ошибка при добавлении новой темы!';
					else
						$_SESSION['test']['test_theme']=$new_theme_id;
				}
				else
				{$_SESSION['test']['test_theme']=$_POST['test_theme'];}
				
				//добавляем данные с формы к ассоциативному массиву сессии
				$_SESSION['test']['test_name']=htmlspecialchars(trim($_POST['test_name']));
				$_SESSION['test']['user_id']=$_POST['user_id'];
				$_SESSION['test']['country_id']=$_POST['country_id'];
				$_SESSION['test']['city_id']=$_POST['city_id'];
				$_SESSION['test']['test_description']=htmlspecialchars(trim($_POST['test_description']));
				$_SESSION['test']['test_var']=$_POST['test_var'];
				
				//если время указано менее 1, устанавливаем его в 1 
				(((int)$_POST['test_min'])==0)? $_POST['test_min']=1:$_POST['test_min'];
				$_SESSION['test']['test_min']=$_POST['test_min'];
				
				//если вопросов указано менее 1, устанавливаем его в 1 
				(((int)$_POST['test_question'])==0)? $_POST['test_question']=1:$_POST['test_question'];
				$_SESSION['test']['test_question']=$_POST['test_question'];
				
				$view->content=true;
				$view->title='Добавление вопросов';
				$result=$view->render('../views/create.php');
				
			}
			else{
				$view->title='Ошибка при создании теста';
				$view->content='<span id="add_test_error">Количество вопросов, ответов и время на выполнение должны быть равными единице как минимум!<br><br>Вы должны заполнить поля формы корректно!<br>И без пустых значений!<br><br><a href="/create"><< Вернутся и заполнить поля ещё раз</a></span>';
				$result=$view->render('../views/default.php');
				}
			
			
		}//если пользователь зашел по прямой ссылке без необходимых данных, то перенаправляем его на главную страницу
		else{header('Location: ../');}
		
		$fc->setBody($result);
	}
	
	//метод вызываемый при добавлении теста
	function addAction()
	{
		$fc=FrontController::get();
		$view=new View();
		
		//если зашел не пользователь
		if(!is_array($_SESSION['loginfo'])){return header('Location: ../create');}
		else
		{
		//если пользователь нажал на кнопку "создать тест"
		if(isset($_POST['quantity']) and isset($_POST['add_test'])and isset($_SESSION['test']))
		{
		//проверка корректности значения скрытого поля с количеством вопросов
		if((int)($_POST['quantity'])>=1 and is_array($_SESSION['test']))
		{		
			//заносим пришедшие данные в сессию и отправляем её к модели для записи в БД
			$qnt=$_POST['quantity'];
			$answ=$_SESSION['test']['test_var'];

			for($i=1;$i<=$qnt;$i++)
			{
				
			if(!empty($_POST['question'.$i]) and !empty($_POST['answer'.$i]))
			{
			$_SESSION['test']['question'.$i]=htmlspecialchars(trim($_POST['question'.$i]));
			$_SESSION['test']['answer'.$i]=htmlspecialchars(trim($_POST['answer'.$i]));
			}
			else{
				$view->title='Пустые значения недопустимы!';
				$view->content='<span id="add_test_error">Поля не должны содержать пустые значения! Пожалуйста попробуйте создать тест еще раз!<br><br><a href="/create"><< Вернутся к созданию теста</a></span>';
				$result=$view->render('../views/default.php');
				$fc->setBody($result); return;
				}
			
				for($j=1;$j<=$answ;$j++)
				{
						if(!empty($_POST['q'.$i.'var'.$j]))
							 $_SESSION['test']['q'.$i.'var'.$j]=htmlspecialchars(trim($_POST['q'.$i.'var'.$j]));
						else
							$answ=0;
							
				}
			}
			//добавление вопросов к организационному тесту
			if(!empty($_SESSION['test']['test_org']))
				$result=Create::addOrgTest($_SESSION['test'],$qnt,$answ);
			//добавление вопросов к университетскому тесту
			elseif(!empty($_SESSION['test']['univer']))
				$result=Create::addUniverTest($_SESSION['test'],$qnt,$answ);
			//добавление школьного теста
			elseif(!empty($_SESSION['test']['school']))
				$result=Create::addSchoolTest($_SESSION['test'],$qnt,$answ);
			//добавление вопросов к пользовательскому тесту
			else
				$result=Create::addUserTest($_SESSION['test'],$qnt,$answ);
			
			//проверка корректности добавления теста и вывод соответствующих сообщений
			if(!is_string($result))
			{
				$view->content='Ваш тест успешно добавлен!<br><br><span id="back_link"><a href="/execute"><< Вернутся к тестам</a></span>';
				unset($_SESSION['test']);

			}
			else
			{
				$view->content='<span id="add_test_error">При добавлении теста возникли ошибки!<br>Код ошибки: '.$result.'<br><br><a href="../"><< Вернутся на главную</a></span>';
				unset($_SESSION['test']);
			}
			
			}
			
			
		}//если пользователь зашел без необходимых параметров выводим предупреждение
		else{$view->content='<span id="add_test_error">Поля не должны содержать пустые значения! Пожалуйста попробуйте создать тест еще раз!<br><br><a href="../"><< Вернутся на главную</a></span>';}
		
		$view->keywords='Дистанционное тестирование, тестирование школьников, тестирование студентов, тестирование сотрудников, онлайн, тестирование, пройти тестирование, создать тест, добавить тест, пользовательский тест';
		$view->description='Информационная система дистанционного тестирования знаний, для студентов, пользователей, школьников и сотрудников предприятий. Вы можете легко создать тест...';
		$view->title='Добавление теста...';
		$result=$view->render('../views/default.php');
		$fc->setBody($result);
		}
	}
	
	//метод для создания теста организации
	function orgtestAction()
	{
		//если зашел начальник
		if($_SESSION['loginfo']['type_id']==6)
		{
			$fc=FrontController::get();
			$view=new View();
			
			if(isset($_POST['submit']))
			{
				if(!empty($_POST['test_name']) and !empty($_POST['user_id']) and !empty($_POST['country_id']) and !empty($_POST['city_id']) and !empty($_POST['test_description']) and !empty($_POST['test_questions']) and !empty($_POST['test_var']) and !empty($_POST['test_min']) and !empty($_POST['test_org']) and !empty($_POST['test_theme'])) 
				{	
				//если пользователь заполнил поля добавления новой темы
					if(!empty($_POST['test_new_theme']))
					{
						$new_theme_id=Create::addNewOrgTheme(htmlspecialchars(trim($_POST['test_new_theme'])), htmlspecialchars(trim($_POST['new_theme_descr'])));
						if((int)$new_theme_id==0)
							echo 'Ошибка при добавлении новой темы!';
						else
							$_SESSION['test']['test_theme']=$new_theme_id;
					}
					else
					{$_SESSION['test']['test_theme']=$_POST['test_theme'];}
				
				//добавляем данные с формы к ассоциативному массиву сессии
				$_SESSION['test']['test_name']=htmlspecialchars(trim($_POST['test_name']));
				$_SESSION['test']['user_id']=$_POST['user_id'];
				$_SESSION['test']['country_id']=$_POST['country_id'];
				$_SESSION['test']['city_id']=$_POST['city_id'];
				$_SESSION['test']['test_description']=htmlspecialchars(trim($_POST['test_description']));
				$_SESSION['test']['test_var']=$_POST['test_var'];
				$_SESSION['test']['test_org']=$_POST['test_org'];
				
				//если время указано менее 1, устанавливаем его в 1 
				(((int)$_POST['test_min'])==0)? $_POST['test_min']=1:$_POST['test_min'];
				$_SESSION['test']['test_min']=$_POST['test_min'];
				
				//если вопросов указано менее 1, устанавливаем его в 1 
				(((int)$_POST['test_questions'])==0)? $_POST['test_questions']=1:$_POST['test_questions'];
				$_SESSION['test']['test_questions']=$_POST['test_questions'];
				
				$view->title='Добавление вопросов';
				$result=$view->render('../views/create.php');
				
				}
				else 
				{
					$view->content='<span id="add_test_error">Количество вопросов, ответов и время на выполнение должны быть равными единице как минимум!<br><br>Вы должны заполнить поля формы корректно!<br>И без пустых значений!<br><br><a href="/create"><< Вернутся и заполнить поля ещё раз</a></span>';
					$result=$view->render('../views/default.php');
				}
			
		
			$view->title='Добавление организационного теста...';

			$fc->setBody($result);
			}
			
		}
		//перенаправляем неккорркктного пользователя на главную
		if($_SESSION['loginfo']['type_id']!=6)
			header('Location: ../');
		
	}
	
	//метод для создание вопросов к студенческому тесту
	function studtestAction()
	{
		//если зашел учитель
		if($_SESSION['loginfo']['type_id']==4)
		{
			$fc=FrontController::get();
			$view=new View();
		    $view->title='Добавление студенческого теста...';
			
			//пользователь нажимает кнопку создать тест
			if(isset($_POST['submit']))
			{
				if(!empty($_POST['user_id']) and !empty($_POST['country_id']) and !empty($_POST['name']) and !empty($_POST['about']) and !empty($_POST['test_questions']) and !empty($_POST['test_var']) and !empty($_POST['test_min']) and !empty($_POST['city']) and !empty($_POST['univer']) and !empty($_POST['faculty']) and !empty($_POST['course']) and !empty($_POST['group']) and !empty($_POST['specialty']) and !empty($_POST['lessons']))
				{
					
				//добавляем данные с формы к ассоциативному массиву сессии
				$_SESSION['test']['test_name']=htmlspecialchars(trim($_POST['name']));
				$_SESSION['test']['user_id']=$_POST['user_id'];
				$_SESSION['test']['country_id']=$_POST['country_id'];
				$_SESSION['test']['city_id']=$_POST['city'];
				$_SESSION['test']['test_description']=htmlspecialchars(trim($_POST['about']));
				$_SESSION['test']['test_var']=$_POST['test_var'];
				$_SESSION['test']['univer']=$_POST['univer'];
				$_SESSION['test']['faculty']=$_POST['faculty'];
				$_SESSION['test']['course']=$_POST['course'];
				$_SESSION['test']['group']=$_POST['group'];
				$_SESSION['test']['specialty']=$_POST['specialty'];
				$_SESSION['test']['lessons']=$_POST['lessons'];
					
					
				//если время указано менее 1, устанавливаем его в 1 
				(((int)$_POST['test_min'])==0)? $_POST['test_min']=1:$_POST['test_min'];
				$_SESSION['test']['test_min']=$_POST['test_min'];
				
				//если вопросов указано менее 1, устанавливаем его в 1 
				(((int)$_POST['test_questions'])==0)? $_POST['test_questions']=1:$_POST['test_questions'];
				$_SESSION['test']['test_questions']=$_POST['test_questions'];
					
					$view->title='Добавление вопросов';
					$result=$view->render('../views/create.php');
				}
				else
				{
					$view->content='<span id="add_test_error">Количество вопросов, ответов и время на выполнение должны быть равными единице как минимум!<br><br>Вы должны заполнить поля формы корректно!<br>И без пустых значений!<br><br><a href="/create"><< Вернутся и заполнить поля ещё раз</a></span>';
					$result=$view->render('../views/default.php');
				}
			
			}
			
			$fc->setBody($result);
		}
		//перенаправляем неккорркктного пользователя на главную страницу
		if($_SESSION['loginfo']['type_id']!=4)
			header('Location: ../');
	}
	
	//метод для создание вопросов к школьному тесту
	function pupiltestAction()
	{
		//если зашел учитель
		if($_SESSION['loginfo']['type_id']==4)
		{
			$fc=FrontController::get();
			$view=new View();
		    $view->title='Добавление школьного теста...';
			
			//пользователь нажимает кнопку создать тест
			if(isset($_POST['submit']))
			{
				if(!empty($_POST['user_id']) and !empty($_POST['country_id']) and !empty($_POST['name']) and !empty($_POST['about']) and !empty($_POST['test_questions']) and !empty($_POST['test_var']) and !empty($_POST['test_min']) and !empty($_POST['city']) and !empty($_POST['school']) and !empty($_POST['class']) and !empty($_POST['lesson']))
				{
					
				//добавляем данные с формы к ассоциативному массиву сессии
				$_SESSION['test']['test_name']=htmlspecialchars(trim($_POST['name']));
				$_SESSION['test']['user_id']=$_POST['user_id'];
				$_SESSION['test']['country_id']=$_POST['country_id'];
				$_SESSION['test']['city_id']=$_POST['city'];
				$_SESSION['test']['test_description']=htmlspecialchars(trim($_POST['about']));
				$_SESSION['test']['test_var']=$_POST['test_var'];
				$_SESSION['test']['school']=$_POST['school'];
				$_SESSION['test']['class']=$_POST['class'];
				$_SESSION['test']['lesson']=$_POST['lesson'];
					
					
				//если время указано менее 1, устанавливаем его в 1 
				(((int)$_POST['test_min'])==0)? $_POST['test_min']=1:$_POST['test_min'];
				$_SESSION['test']['test_min']=$_POST['test_min'];
				
				//если вопросов указано менее 1, устанавливаем его в 1 
				(((int)$_POST['test_questions'])==0)? $_POST['test_questions']=1:$_POST['test_questions'];
				$_SESSION['test']['test_questions']=$_POST['test_questions'];
					
					
					$view->title='Добавление вопросов';
					$result=$view->render('../views/create.php');
				}
				else
				{
					$view->content='<span id="add_test_error">Количество вопросов, ответов и время на выполнение должны быть равными единице как минимум!<br><br>Вы должны заполнить поля формы корректно!<br>И без пустых значений!<br><br><a href="/create"><< Вернутся и заполнить поля ещё раз</a></span>';
					$result=$view->render('../views/default.php');
				}
			
			}
			
			$fc->setBody($result);
		}
		//перенаправляем неккорркктного пользователя на главную страницу
		if($_SESSION['loginfo']['type_id']!=4)
			header('Location: ../');
	}
	
}

?>