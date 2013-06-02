<?
//контроллер вызываемый по запросу /authorization
class AuthorizationController implements IController
{
	function indexAction()
	{
		$fc=FrontController::get();
		
		//проверка и корректировка введеных пользователем данных
			if(isset($_POST['login']) and isset($_POST['password']))
			{ 
				if(!empty($_POST['login']) and !empty($_POST['password']))
				{
					$login=htmlspecialchars(stripslashes(trim($_POST['login'])));
					$password=htmlspecialchars(stripslashes(trim($_POST['password'])));
					//создание экземпляра модели Check "проверка пользователя из базы данных"
					$check=new Check($login,$password);
					$user=$check->getResult();
					if(is_array($user))
					{//перенаправляем пользователя на страницу с его профилем
						$_SESSION['loginfo']=$check->getResult(); 
						header('Location: authorization/profile');
					}
					else{$content='<b>'.$check->getResult().'</b>';}
				}
				else{$content='<b>Пустые значения недопустимы!</b>';}
				
		$view=new View();
		$view->content=$content;
		$view->keywords='Дистанционное тестирование, тестирование школьников, тестирование студентов, тестирование сотрудников, онлайн, тестирование, пройти тестирование, создать тест, о ИС, описание';
		$view->description='Информационная система дистанционного тестирования знаний, для студентов, пользователей, школьников и сотрудников предприятий. Вы можете легко создать или пройти тест, результаты будут';
		$view->title='Ошибка авторизации';
		$result=$view->render('../views/default.php');
		$fc->setBody($result);
		       
			}
		else
		{
			header('Location: ../');
		}	
	}
	
	//метод для выхода из системы
	function exitAction()
	{
		//уничтожение сессии и перенаправление на главную страницу
		session_destroy();
		header('Location: ../');
	}
	
	//метод выводит профиль пользователя
	function profileAction()
	{
		//если в переменной сессии массив, то выводим данные о пользователе
		if(is_array($_SESSION['loginfo']))
		{
		$fc=FrontController::get();
		$view=new View();
		$view->countries=Registration::getCountries();
		$view->content=$_SESSION['loginfo'];
		$view->title='Пользователь: '.$_SESSION['loginfo']['login'];
		
		//сохранение изменений
		if(isset($_POST['edit']))
		{
			$view->title='Редактирование профиля';
			
			if(!empty($_POST['fio']) and !empty($_POST['date']) and !empty($_POST['email']) and !empty($_POST['tel']) and !empty($_POST['about']) and !empty($_POST['address']) and !empty($_POST['login']))
			{
				//сохранение изображения
				if(!empty($_FILES['pic']['tmp_name']))
				{
					$save_img=Registration::saveImage($_FILES['pic']);
					($save_img)? $image=$save_img: $image='/css/img/no_avatar.jpg';
				}
				else
					$image=$_SESSION['loginfo']['image'];
				
				//сохранение изменения страны и города
				if(!empty($_POST['country']) and !empty($_POST['city']))
				{
					$view->content=Registration::saveProfile($_POST['login'], $_POST['fio'], $_POST['date'], $_POST['email'], $_POST['tel'], $_POST['about'], $_POST['address'], $_POST['country'], $_POST['city'], $image);
					($view->content) ? $view->content='Профиль успешно обновлён!': $view->content='Возникла ошибка при обновлении профиля!';
					$view->content.='<br><span id="add_test_error"><a href="/authorization/profile"><< Вернутся к моему профилю</a></span>';
				}
				else
				{
					$view->content=Registration::saveProfileParticle($_POST['login'], $_POST['fio'], $_POST['date'], $_POST['email'], $_POST['tel'], $_POST['about'], $_POST['address'], $image);
					($view->content) ? $view->content='Профиль успешно обновлён!': $view->content='Возникла ошибка при обновлении профиля!';
					$view->content.='<br><span id="add_test_error"><a href="/authorization/profile"><< Вернутся к моему профилю</a></span>';
				}
				

			}
			else
			{
				$view->content='Поля не должны содержать пустые значения!<br><span id="add_test_error"><a href="/authorization/profile"><< Вернутся к моему профилю</a></span>';
			}
			
			$result=$view->render('../views/default.php');
		}
		else
			$result=$view->render('../views/profile.php');
		
		$fc->setBody($result);
		}
		else{header('Location: ../');}
	}
	

	
	//метод выводит все тесты пользователя
	function mytestsAction()
	{
		//если в переменной сессии массив, то выводим данные о пользователе
		if(is_array($_SESSION['loginfo']))
		{
		$fc=FrontController::get();
		$view=new View();
		$view->keywords='Дистанционное тестирование, тестирование школьников, тестирование студентов, тестирование сотрудников, онлайн, тестирование, пройти тестирование, создать тест, о ИС, описание';
		$view->description='Информационная система дистанционного тестирования знаний, для студентов, пользователей, школьников и сотрудников предприятий. Вы можете легко создать или пройти тест, результаты будут';
		$view->title='Тесты: '.$_SESSION['loginfo']['login'];
		
		//выводим тесты пользователя
		$view->usertests=Edit::getMyUserTests($_SESSION['loginfo']);
		$view->orgtests=Edit::getMyOrgTests($_SESSION['loginfo']);
		$view->schooltests=Edit::getMySchoolTests($_SESSION['loginfo']);
		$view->univertests=Edit::getMyUniverTests($_SESSION['loginfo']);
		
		//отправляем данные в представление
		$result=$view->render('../views/edit.php');

		}//если зашел не пользователь перенаправляем
		else header('Location:../');
		$fc->setBody($result);
	}
		
	//метод измененяет/удаленяет тест пользователя
	function edittestAction()
	{
		//если в переменной сессии массив, то выводим данные о пользователе
		if(is_array($_SESSION['loginfo']))
		{
		$fc=FrontController::get();
		$view=new View();
		$view->keywords='Дистанционное тестирование, тестирование школьников, тестирование студентов, тестирование сотрудников, онлайн, тестирование, пройти тестирование, создать тест, о ИС, описание';
		$view->description='Информационная система дистанционного тестирования знаний, для студентов, пользователей, школьников и сотрудников предприятий. Вы можете легко создать или пройти тест, результаты будут';
		$params=$fc->getParams();
		
		//если пользователь добавляет новый вопрос к тесту
		if($params['addquestion']=='new' and isset($_POST['question']) and isset($_POST['answer']) and isset($_POST['add_question']) and isset($_POST['count']))
		{
			if(empty($_POST['question']) or empty($_POST['answer']))
			{$empty=true;}
			
			if((int)$_POST['count']>0)
		{
				for($i=1;$i<=$_POST['count'];$i++)
			{
				if(!empty($_POST['var'.$i]))
					$vars[]=trim($_POST['var'.$i]);
				else $empty=true;
			}
			
			//если все данные корректны то отправляем их модели на добавление
			if(!isset($empty))
			{
				$add=Edit::addQuestion($_SESSION['test_id'], $_POST['question'], $_POST['answer'], $vars, 'user');
				
				($add)? $view->content='Вопрос успешно добавлен!' : $view->content='Аналогичный вопрос уже существует в базе данных! '; 
				
				$view->title='Добавление нового вопроса';
				$view->content.='<br><span id="add_test_error"><a href="/authorization/mytests"><< Вернутся назад к моим тестам</a></span>';
			}
			else
			{
				$view->title='Путстые значения!';
				$view->content='Пустые знанчения не допустимы! ';
				$view->content.='<br><span id="add_test_error"><a href="/authorization/mytests"><< Вернутся назад к моим тестам</a></span>';
			}
		}
		elseif((int)$_POST['count']==0)
			{
				$add=Edit::addQuestionParticle($_SESSION['test_id'], $_POST['question'], $_POST['answer'], 'user');
				
				($add)? $view->content='Вопрос успешно добавлен!' : $view->content='Аналогичный вопрос уже существует в базе данных! '; 
				
				$view->title='Добавление нового вопроса';
				$view->content.='<br><span id="add_test_error"><a href="/authorization/mytests"><< Вернутся назад к моим тестам</a></span>';
				
			}
			
			
			$result=$view->render('../views/default.php');
		}
		else
		{
			
		$view->title='Изменение теста';
				
			//проверка пришедших данных на корректность
			if(isset($_POST['test_id']) and !empty($_POST['test_name']) and !empty($_POST['test_description']) and !empty($_POST['test_time']) and isset($_POST['test_type']))
			{
				//если пользователь хочет изменить тест
				if(isset($_POST['edit']))
				{
					$edit=Edit::saveTest($_POST['test_id'], $_POST['test_name'], $_POST['test_description'], $_POST['test_time'], $_POST['test_theme'], $_SESSION['loginfo']['type_id']);
					($edit) ? $view->content='Тест №'.$_POST['test_id'].' успешно изменен!' : 
							  $view->content='При изменении теста №'.$_POST['test_id'].' возникли ошибки!';
					
					$view->content.='<br><span id="add_test_error"><a href="/authorization/mytests"><< Вернутся назад к моим тестам</a></span>';		$view->title='Изменение теста';
					$result=$view->render('../views/default.php');
				}
				//если пользователь хочет удалить тест
				if(isset($_POST['del']))
				{
					$delete=Edit::deleteTest($_POST['test_id'],$_SESSION['loginfo']['type_id']);
					($delete) ? $view->content='Тест №'.$_POST['test_id'].' успешно удалён!' :  
								$view->content='При удалении теста №'.$_POST['test_id'].' возникли ошибки!'; 
								
					$view->content.='<br><span id="add_test_error"><a href="/authorization/mytests"><< Вернутся назад к моим тестам</a></span>';		$view->title='Удаление теста';
					$result=$view->render('../views/default.php');
				}
				//если пользователь хочет изменять/удалять вопросы теста
				if(isset($_POST['tests']))
				{
					
					$get=Edit::getQuestions($_POST['test_id'], $_POST['test_type']);
					if(is_array($get))
					{
					$view->content=$get;
					$view->type=$_POST['test_type'];
					$view->test_id=$_POST['test_id'];
					$_SESSION['test_type']=$_POST['test_type'];
					$_SESSION['test_id']=$_POST['test_id'];
					
					$view->title='Вопросы теста: '.$_POST['test_name'];
					$result=$view->render('../views/edit.php');
					}
					else{
							$view->content='Возникли ошибки при выборке вопросов теста!<br><span id="add_test_error"><a href="/authorization/mytests"><< Вернутся назад к моим тестам</a></span>';
						    $view->title='Ошибка при выборке вопросов';
							$result=$view->render('../views/default.php');
						}
				}
			
			}
			else{
				$view->content='Поля не должны содержать пустых значений!<br><span id="add_test_error"><a href="/authorization/mytests"><< Вернутся назад к моим тестам</a></span>';
				$result=$view->render('../views/default.php');
			 	}
		}
		
		$fc->setBody($result);
		}//если зашел не пользователь перенаправляем
		else{header('Location: ../');}
		
	}
	
	//метод измененяет/удаленяет/добавляет вопросы к тесту
	function editquestionAction()
	{
		//если в переменной сессии массив, то выводим данные о пользователе
		if(is_array($_SESSION['loginfo']))
		{
		$fc=FrontController::get();
		$view=new View();
		$view->keywords='Редактирование вопросов теста';
		$view->description='Информационная система дистанционного тестирования знаний, для студентов, пользователей, школьников и сотрудников предприятий. Вы можете легко создать или пройти тест';
		$view->title='Изменение вопросов теста';
		
		//проверка пришедших данных
		if(!empty($_POST['question_id']) and !empty($_POST['question']) and !empty($_POST['answer']) and !empty($_POST['test_type']) and !empty($_POST['test_id']))
		{
			//изменение вопроса пользователем
			if(isset($_POST['question_save']))
			{
				if((int)$_POST['answer_count']>0)
				{//запись ответов на вопрос в массив
				for($i=1;$i<=$_POST['answer_count'];$i++)
					{	
						if(!empty($_POST['var'.$i]))
						$vars[]=$_POST['var'.$i];
						else{$var=false;}
					}
				//если ответы содержат значения и не пусты
				if(is_array($vars) and !isset($var))
				{
					//отправление данных на выполнение модели
					$save=Edit::saveQuestion($_POST['question_id'], $_POST['question'], $_POST['answer'], $vars, $_POST['test_type']);
					//проверка результата изменения вопроса
					($save) ? $view->content='Вопрос успешно изменен!' : $view->content='При редактировании вопроса возникли ошибки'; 
					$view->title='Редактирование вопроса';
					$view->content.='<br><span id="add_test_error"><a href="/authorization/mytests"><< Вернутся назад к моим тестам</a></span>';
				}
				}
				//если вариантов ответа нет
				elseif((int)$_POST['answer_count']==0)
				{
					//отправление данных на выполнение модели
					$save=Edit::saveQuestionParticle($_POST['question_id'], $_POST['question'], $_POST['answer'], $_POST['test_type']);
					//проверка результата изменения вопроса
					($save) ? $view->content='Вопрос успешно изменен!' : $view->content='При редактировании вопроса возникли ошибки'; 
					$view->title='Редактирование вопроса';
					$view->content.='<br><span id="add_test_error"><a href="/authorization/mytests"><< Вернутся назад к моим тестам</a></span>';
				}
				//если один из вариантов ответа на вопрос пуст 
				else
				{
					$view->content='Ответ на вопрос не должен содержать пустое значение!';
					$view->content.='<br><span id="add_test_error"><a href="/authorization/mytests"><< Вернутся назад к моим тестам</a></span>';
				}
				
			}//удаление вопроса пользователем
			elseif(isset($_POST['question_delete']))
			{
				
				$delete=Edit::deleteQuestion($_POST['question_id'], $_POST['test_type'], $_POST['test_id']);
				($delete) ? $view->content='Вопрос успешно удалён!' : $view->content='При удалении вопроса возникли ошибки'; 
				
				$view->content.='<br><span id="add_test_error"><a href="/authorization/mytests"><< Вернутся назад к моим тестам</a></span>';
				
			}//если не была нажата соответствующая кнопка
			else{header('Location: ../');}
		
		}
		else
		{//при некорректности пришедших данных
			$view->content='Вопросы и варианты ответов не должны создержать пустых значений!<br><span id="add_test_error"><a href="/authorization/mytests"><< Вернутся назад к моим тестам</a></span>';
			$view->title='Ошибка при изменении вопросов';
		}
		$result=$view->render('../views/default.php');
		$fc->setBody($result);
	}//если зашел не пользователь перенаправляем
		else{header('Location: ../');}
	}
	
	//метод измененяет/удаленяет организационный тест 
	function editorgtestAction()
	{
		//если в переменной сессии массив, то выводим данные о пользователе
		if(is_array($_SESSION['loginfo']))
		{
		$fc=FrontController::get();
		$view=new View();
		
		$params=$fc->getParams();
		
		//если пользователь добавляет новый вопрос к тесту
		if($params['addquestion']=='new' and isset($_POST['question']) and isset($_POST['answer']) and isset($_POST['add_question']) and isset($_POST['count']))
		{
			if(empty($_POST['question']) or empty($_POST['answer']))
			{$empty=true;}
			
			if((int)$_POST['count']>0)
		{
				for($i=1;$i<=$_POST['count'];$i++)
			{
				if(!empty($_POST['var'.$i]))
					$vars[]=trim($_POST['var'.$i]);
				else $empty=true;
			}
			
			//если все данные корректны то отправляем их модели на добавление
			if(!isset($empty))
			{
				$add=Edit::addQuestion($_SESSION['test_id'], $_POST['question'], $_POST['answer'], $vars, 'univer');
				
				($add)? $view->content='Вопрос успешно добавлен!' : $view->content='Аналогичный вопрос уже существует в базе данных! '; 
				
				$view->title='Добавление нового вопроса';
				$view->content.='<br><span id="add_test_error"><a href="/authorization/mytests"><< Вернутся назад к моим тестам</a></span>';
			}
			else
			{
				$view->title='Путстые значения!';
				$view->content='Пустые знанчения не допустимы! ';
				$view->content.='<br><span id="add_test_error"><a href="/authorization/mytests"><< Вернутся назад к моим тестам</a></span>';
			}
		}
		elseif((int)$_POST['count']==0)
			{
				$add=Edit::addQuestionParticle($_SESSION['test_id'], $_POST['question'], $_POST['answer'], 'univer');
				
				($add)? $view->content='Вопрос успешно добавлен!' : $view->content='Аналогичный вопрос уже существует в базе данных! '; 
				
				$view->title='Добавление нового вопроса';
				$view->content.='<br><span id="add_test_error"><a href="/authorization/mytests"><< Вернутся назад к моим тестам</a></span>';
				
			}
			
			$result=$view->render('../views/default.php');
		}
		else
		{
			
		$view->title='Изменение теста';
				
			//проверка пришедших данных на корректность
			if(isset($_POST['test_id']) and !empty($_POST['test_name']) and !empty($_POST['test_description']) and !empty($_POST['test_time']) and isset($_POST['test_type']) and !empty($_POST['test_org']))
			{
				//если пользователь хочет изменить тест!!!!!!!!!!!
				if(isset($_POST['edit']))
				{
					$edit=Edit::saveOrgTest($_POST['test_id'], $_POST['test_org'], $_POST['test_name'], $_POST['test_description'], $_POST['test_time'], $_POST['test_theme'], $_SESSION['loginfo']['type_id']);
					($edit) ? $view->content='Тест №'.$_POST['test_id'].' успешно изменен!' : 
							  $view->content='При изменении теста №'.$_POST['test_id'].' возникли ошибки!';
					
					$view->content.='<br><span id="add_test_error"><a href="/authorization/mytests"><< Вернутся назад к моим тестам</a></span>';		$view->title='Изменение теста';
					$result=$view->render('../views/default.php');
				}
				//если пользователь хочет удалить тест
				if(isset($_POST['del']))
				{
					$delete=Edit::deleteOrgTest($_POST['test_id']);
					($delete) ? $view->content='Тест №'.$_POST['test_id'].' успешно удалён!' :  
								$view->content='При удалении теста №'.$_POST['test_id'].' возникли ошибки!'; 
								
					$view->content.='<br><span id="add_test_error"><a href="/authorization/mytests"><< Вернутся назад к моим тестам</a></span>';		$view->title='Удаление теста';
					$result=$view->render('../views/default.php');
				}
				//если пользователь хочет изменять/удалять вопросы теста
				if(isset($_POST['tests']))
				{
					
					$get=Edit::getQuestions($_POST['test_id'], $_POST['test_type']);
					if(is_array($get))
					{
					$view->content=$get;
					$view->type=$_POST['test_type'];
					$view->test_id=$_POST['test_id'];
					$_SESSION['test_type']=$_POST['test_type'];
					$_SESSION['test_id']=$_POST['test_id'];
					
					$view->title='Вопросы теста: '.$_POST['test_name'];
					$result=$view->render('../views/edit.php');
					}
					else{
							$view->content='Возникли ошибки при выборке вопросов теста!<br><span id="add_test_error"><a href="/authorization/mytests"><< Вернутся назад к моим тестам</a></span>';
						    $view->title='Ошибка при выборке вопросов';
							$result=$view->render('../views/default.php');
						}
				}
			
			}
			else{
				$view->content='Поля не должны содержать пустых значений!<br><span id="add_test_error"><a href="/authorization/mytests"><< Вернутся назад к моим тестам</a></span>';
				$result=$view->render('../views/default.php');
			 	}
		}
		
		$fc->setBody($result);
		}//если зашел не пользователь перенаправляем
		else{header('Location: ../');}
		
	}
	
	//редактирование университетских тестов
	function editunivertestAction()
	{
		if($_SESSION['loginfo']['type_id']!=4)
			header('Location: ../');
		
		$fc=FrontController::get();
		$view=new View();
		
		
		$params=$fc->getParams();
		
		//если пользователь добавляет новый вопрос к тесту
		if($params['addquestion']=='new' and isset($_POST['question']) and isset($_POST['answer']) and isset($_POST['add_question']) and isset($_POST['count']))
		{
			if(empty($_POST['question']) or empty($_POST['answer']))
			{$empty=true;}
			
			
		if((int)$_POST['count']>0)
		{
				for($i=1;$i<=(int)$_POST['count'];$i++)
			{
				if(!empty($_POST['var'.$i]))
					$vars[]=trim($_POST['var'.$i]);
				else $empty=true;
			}
			
			//если все данные корректны то отправляем их модели на добавление
			if(!isset($empty))
			{
				$add=Edit::addQuestion($_SESSION['test_id'], $_POST['question'], $_POST['answer'], $vars, 'univer');
				
				($add)? $view->content='Вопрос успешно добавлен!' : $view->content='Аналогичный вопрос уже существует в базе данных! '; 
				
				$view->title='Добавление нового вопроса';
				$view->content.='<br><span id="add_test_error"><a href="/authorization/mytests"><< Вернутся назад к моим тестам</a></span>';
			}
			else
			{
				$view->title='Путстые значения!';
				$view->content='Пустые знанчения не допустимы! ';
				$view->content.='<br><span id="add_test_error"><a href="/authorization/mytests"><< Вернутся назад к моим тестам</a></span>';
			}
		}
		elseif((int)$_POST['count']==0)
			{
				$add=Edit::addQuestionParticle($_SESSION['test_id'], $_POST['question'], $_POST['answer'], 'univer');
				
				($add)? $view->content='Вопрос успешно добавлен!' : $view->content='Аналогичный вопрос уже существует в базе данных! '; 
				
				$view->title='Добавление нового вопроса';
				$view->content.='<br><span id="add_test_error"><a href="/authorization/mytests"><< Вернутся назад к моим тестам</a></span>';
				
			}
			
			$result=$view->render('../views/default.php');
		}
		//редактирование теста
		if(isset($_POST['edit']))
		{
			//полное редактирование информации о тесте!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			if(!empty($_POST['test_name']) and !empty($_POST['test_description']) and !empty($_POST['test_time']) and !empty($_POST['univer']) and !empty($_POST['faculty']) and !empty($_POST['course']) and !empty($_POST['specialty']) and !empty($_POST['group']) and !empty($_POST['lessons']) and !empty($_POST['test_id']))
			{
				$view->content=Universities::get()->editUniverTestname($_SESSION['loginfo']['id'], $_POST['test_id'], $_POST['test_name'], $_POST['test_description'], $_POST['test_time'], $_POST['univer'], $_POST['faculty'], $_POST['course'], $_POST['specialty'], $_POST['group'], $_POST['lessons']);
				($view->content) ? $view->content='Информация о университетском тесте обновлена успешно!': $view->content='Возникла ошибка при редактировании информации о тесте!';
				$view->content.='<br><span id="add_test_error"><a href="/authorization/mytests"><< Вернутся назад к моим тестам</a></span>';	
				$result=$view->render('../views/default.php');
			}
			//частичное редактирование информаци о тесте
			elseif(!empty($_POST['test_name']) and !empty($_POST['test_id']) and !empty($_POST['test_description']) and !empty($_POST['test_time']))
			{
				$view->content=Universities::get()->editUniverTestnameParticle($_SESSION['loginfo']['id'], $_POST['test_id'], $_POST['test_name'], $_POST['test_description'], $_POST['test_time']);
				($view->content)? $view->content='Информация о университетском тесте обновлена успешно!': $view->content='Возникла ошибка при редактировании информации о тесте!';
				$view->content.='<br><span id="add_test_error"><a href="/authorization/mytests"><< Вернутся назад к моим тестам</a></span>';	
			}
			
			$result=$view->render('../views/default.php');
		}
		//удаление теста
		elseif(isset($_POST['delete']))
		{
			if(!empty($_POST['test_id']))
			{
				$view->content=Universities::get()->deleteUniverTest($_POST['test_id']);
				($view->content)? $view->content='Тест, вопросы теста, и результаты его прохождения успешно удалены!': $view->content='Ошибка при удалении теста!';
			}
			else $view->content='Возникла ошибка при удалении теста!';
		
		$view->content.='<br><span id="add_test_error"><a href="/authorization/mytests"><< Вернутся назад к моим тестам</a></span>';	
		$result=$view->render('../views/default.php');
		}
		//редактирование вопросов теста
		elseif(isset($_POST['tests']))
		{
			if(!empty($_POST['test_id']))
			{
				$view->content=$get=Edit::getQuestions($_POST['test_id'], 'univer');
				if(is_array($get))
					{
					$view->content=$get;
					$view->type='univer';
					$view->test_id=$_POST['test_id'];
					$_SESSION['test_type']='univer';
					$_SESSION['test_id']=$_POST['test_id'];
					
					$view->title='Вопросы теста: '.$_POST['test_name'];
					$result=$view->render('../views/edit.php');
					}
					else{
							$view->content='Возникли ошибки при выборке вопросов теста!<br><span id="add_test_error"><a href="/authorization/mytests"><< Вернутся назад к моим тестам</a></span>';
						    $view->title='Ошибка при выборке вопросов';
							$result=$view->render('../views/default.php');
						}
			}
			else 
				{
					$view->content='Необходимые данные для вывода вопросов теста не найдены!';
					$view->content.='<br><span id="add_test_error"><a href="/authorization/mytests"><< Вернутся назад к моим тестам</a></span>';		$result=$view->render('../views/default.php');
					
				}
			
		}
	
		$view->title='Редактирование университетского теста...';
		$fc->setBody($result);	
	}
	
	//вывод результатов тестирования учеников
	function resultsAction()
	{
		//если зашёл admin
		if($_SESSION['loginfo']['type_id']!=4)
			header('Location: ../');
		
		$fc=FrontController::get();
		$view=new View();
		
		$view->title='Результаты прохождения всех тестов';
		
		$view->students=Universities::get()->getTestResult($_SESSION['loginfo']['id']);
		$view->pupils=Schools::get()->getTestResult($_SESSION['loginfo']['id']);
		
		$result=$view->render('../views/results.php');
		$fc->setBody($result);	
	}
	
	//редактирование стран и городов
	function countriesAction()
	{
		//если зашёл admin
		if($_SESSION['loginfo']['login']==='admin')	
		{
			$fc=FrontController::get();
			$view=new View();
	
			$view->title='Страны';
			
			//редактирование названия страны
			if(isset($_POST['edit']))
			{
				if(!empty($_POST['country_id']) and !empty($_POST['name']))
				{
					$view->content=Registration::saveCountryName($_POST['country_id'], $_POST['name']);
					($view->content)? $view->content='Название страны успешно обновлено!':$view->content='Ошибка при сохранении нового названия страны!';
					$view->content.='<br><span id="add_test_error"><a href="/authorization/countries"><< Вернутся назад к списку стран</a></span>';	
					
				}
				else 
					$view->content='Необходимых данных для редактирования названия не найдено! Поле на должно содержать пустые значения!<br><span id="add_test_error"><a href="/authorization/countries"><< Вернутся назад к списку стран</a></span>';	
					
				$result=$view->render('../views/default.php');
			}
			//добавление страны
			elseif(isset($_POST['add']))
			{
				if(!empty($_POST['name']))
				{
					$view->content=Registration::addCountry($_POST['name']);
					($view->content)? $view->content='Страна успешно добавлена!':$view->content='Ошибка при добавлении страны!';
					$view->content.='<br><span id="add_test_error"><a href="/authorization/countries"><< Вернутся назад к списку стран</a></span>';	
					
				}
				else 
					$view->content='Необходимых данных для добавления страны не найдено! Поле на должно содержать пустые значения!<br><span id="add_test_error"><a href="/authorization/countries"><< Вернутся назад к списку стран</a></span>';	
				
				$result=$view->render('../views/default.php');
			}
			else
			{
				$view->countries=Registration::getCountriesArray();
				$result=$view->render('../views/edit.php');
			}
			$fc->setBody($result);	
		}
		else
			header('Location: ../');
	}
	
	//редактирование стран и городов
	function citiesAction()
	{
		//если зашёл admin
		if($_SESSION['loginfo']['login']==='admin')	
		{
			$fc=FrontController::get();
			$view=new View();
		
			if(isset($_POST['cities']))
			{
				if(!empty($_POST['country_id']) and !empty($_POST['name']))	
				{
					
					$view->title=$_POST['name'].': список городов';
					$view->cities=Registration::getCitiesArray($_POST['country_id']);
					$view->country_id=$_POST['country_id'];
					$result=$view->render('../views/edit.php');
				}
				else
				{
					$view->title='Необходимые данные не найдены';
					$view->content='Необходимые данные для воборки городов не найдены!<a href="/authorization/countries"><< Вернутся назад к странам</a></span>';
					$result=$view->render('../views/default.php');
				}
			}
			//редактирование названия города
			elseif(isset($_POST['edit']))	
			{
				if(!empty($_POST['city_id']) and !empty($_POST['name']))
				{
					$view->content=Registration::saveCityName($_POST['city_id'], $_POST['name']);
					($view->content)? $view->content='Название города успешно обновлено!':$view->content='Ошибка при сохранении нового названия города!';
					$view->content.='<br><span id="add_test_error"><a href="/authorization/countries"><< Вернутся назад к списку стран</a></span>';	
					
				}
				else 
					$view->content='Необходимых данных для редактирования названия не найдено! Поле на должно содержать пустые значения!<br><span id="add_test_error"><a href="/authorization/countries"><< Вернутся назад к списку стран</a></span>';	
					
					$result=$view->render('../views/default.php');
			}
			//добавление города
			elseif(isset($_POST['add']))
			{
				if(!empty($_POST['name']) and !empty($_POST['country_id']))
				{
					$view->content=Registration::addCity($_POST['country_id'], $_POST['name']);
					($view->content)? $view->content='Город успешно добавлен!':$view->content='Ошибка при добавлении города!';
					$view->content.='<br><span id="add_test_error"><a href="/authorization/countries"><< Вернутся назад к списку стран</a></span>';	
					
				}
				else 
					$view->content='Необходимых данных для добавления города не найдено! Поле на должно содержать пустые значения!<br><span id="add_test_error"><a href="/authorization/countries"><< Вернутся назад к списку стран</a></span>';	
				
				$result=$view->render('../views/default.php');
			}
			
		
			$fc->setBody($result);	
		}
		else
			header('Location: ../');
	}
	
	//редактирование школьных тестов
	function editschooltestAction()
	{
		if($_SESSION['loginfo']['type_id']!=4)
			header('Location: ../');
		
		$fc=FrontController::get();
		$view=new View();
		$params=$fc->getParams();
		
		//если пользователь добавляет новый вопрос к тесту
		if($params['addquestion']=='new' and isset($_POST['question']) and isset($_POST['answer']) and isset($_POST['add_question']) and isset($_POST['count']))
		{
			if(empty($_POST['question']) or empty($_POST['answer']))
			{$empty=true;}
			
			if((int)$_POST['count']>0)
		{
				for($i=1;$i<=$_POST['count'];$i++)
			{
				if(!empty($_POST['var'.$i]))
					$vars[]=trim($_POST['var'.$i]);
				else $empty=true;
			}
			
			//если все данные корректны то отправляем их модели на добавление
			if(!isset($empty))
			{
				$add=Edit::addQuestion($_SESSION['test_id'], $_POST['question'], $_POST['answer'], $vars, 'school');
				
				($add)? $view->content='Вопрос успешно добавлен!' : $view->content='Аналогичный вопрос уже существует в базе данных! '; 
				
				$view->title='Добавление нового вопроса';
				$view->content.='<br><span id="add_test_error"><a href="/authorization/mytests"><< Вернутся назад к моим тестам</a></span>';
			}
			else
			{
				$view->title='Путстые значения!';
				$view->content='Пустые знанчения не допустимы! ';
				$view->content.='<br><span id="add_test_error"><a href="/authorization/mytests"><< Вернутся назад к моим тестам</a></span>';
			}
		}
		elseif((int)$_POST['count']==0)
			{
				$add=Edit::addQuestionParticle($_SESSION['test_id'], $_POST['question'], $_POST['answer'], 'school');
				
				($add)? $view->content='Вопрос успешно добавлен!' : $view->content='Аналогичный вопрос уже существует в базе данных! '; 
				
				$view->title='Добавление нового вопроса';
				$view->content.='<br><span id="add_test_error"><a href="/authorization/mytests"><< Вернутся назад к моим тестам</a></span>';
				
			}
			
			$result=$view->render('../views/default.php');
		}
		//редактирование теста
		if(isset($_POST['edit']))
		{
			//полное редактирование информации о тесте!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			if(!empty($_POST['test_name']) and !empty($_POST['test_description']) and !empty($_POST['test_time']) and !empty($_POST['school']) and !empty($_POST['class']) and !empty($_POST['lesson']) and !empty($_POST['test_id']))
			{
				$view->content=Schools::get()->editSchoolTestname($_SESSION['loginfo']['id'], $_POST['test_id'], $_POST['test_name'], $_POST['test_description'], $_POST['test_time'], $_POST['school'], $_POST['class'], $_POST['lesson']);
				($view->content) ? $view->content='Информация о школьном тесте обновлена успешно!': $view->content='Возникла ошибка при редактировании информации о тесте!';
				$view->content.='<br><span id="add_test_error"><a href="/authorization/mytests"><< Вернутся назад к моим тестам</a></span>';	
				$result=$view->render('../views/default.php');
			}
			//частичное редактирование информаци о тесте
			elseif(!empty($_POST['test_name']) and !empty($_POST['test_id']) and !empty($_POST['test_description']) and !empty($_POST['test_time']))
			{
				$view->content=Schools::get()->editSchoolTestnameParticle($_SESSION['loginfo']['id'], $_POST['test_id'], $_POST['test_name'], $_POST['test_description'], $_POST['test_time']);
				($view->content)? $view->content='Информация о школьном тесте обновлена успешно!': $view->content='Возникла ошибка при редактировании информации о тесте!';
				$view->content.='<br><span id="add_test_error"><a href="/authorization/mytests"><< Вернутся назад к моим тестам</a></span>';	
			}
			
			$result=$view->render('../views/default.php');
		}
		//удаление теста
		elseif(isset($_POST['delete']))
		{
			if(!empty($_POST['test_id']))
			{
				$view->content=Schools::get()->deleteSchoolTest($_POST['test_id']);
				($view->content)? $view->content='Тест, вопросы теста, и результаты его прохождения успешно удалены!': $view->content='Ошибка при удалении теста!';
			}
			else $view->content='Возникла ошибка при удалении теста!';
		
		$view->content.='<br><span id="add_test_error"><a href="/authorization/mytests"><< Вернутся назад к моим тестам</a></span>';	
		$result=$view->render('../views/default.php');
		}
		//редактирование вопросов теста
		elseif(isset($_POST['tests']))
		{
			if(!empty($_POST['test_id']))
			{
				$view->content=$get=Edit::getQuestions($_POST['test_id'], 'school');
				if(is_array($get))
					{
					$view->content=$get;
					$view->type='school';
					$view->test_id=$_POST['test_id'];
					$_SESSION['test_type']='school';
					$_SESSION['test_id']=$_POST['test_id'];
					
					$view->title='Вопросы теста: '.$_POST['test_name'];
					$result=$view->render('../views/edit.php');
					}
					else{
							$view->content='Возникли ошибки при выборке вопросов теста!<br><span id="add_test_error"><a href="/authorization/mytests"><< Вернутся назад к моим тестам</a></span>';
						    $view->title='Ошибка при выборке вопросов';
							$result=$view->render('../views/default.php');
						}
			}
			else 
				{
					$view->content='Необходимые данные для вывода вопросов теста не найдены!';
					$view->content.='<br><span id="add_test_error"><a href="/authorization/mytests"><< Вернутся назад к моим тестам</a></span>';		$result=$view->render('../views/default.php');
					
				}
			
		}
	
		$view->title='Редактирование школьного теста...';
		$fc->setBody($result);	
	}
}

?>