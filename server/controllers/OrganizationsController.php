<? 
//контроллер вызываемый по запросу /organizations
class OrganizationsController implements IController
{
	function indexAction()
	{
		$fc=FrontController::get();
		//создание экземпляра модели "вида"
		$view=new View();
		$view->orgtests=Organizations::get()->getAllOrgTests();
		$view->keywords='Дистанционное тестирование, тестирование школьников, тестирование студентов, тестирование сотрудников, онлайн, тестирование, пройти тестирование, создать тест, о ИС, описание';
		$view->description='Информационная система дистанционного тестирования знаний, для студентов, пользователей, школьников и сотрудников предприятий. Вы можете легко создать или пройти тест...';
		$view->title='Организации и тесты';
		$result=$view->render('../views/tests.php');
		$fc->setBody($result);
		
	}
	
	//метод выводит организацию пользователя!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	function myAction()
	{
		$fc=FrontController::get();
		$view=new View();
		
		//если зашел зарегистрированный пользователь
		if($_SESSION['loginfo']['type_id']==6)
		{
			//если пользователь хочет сохранить внесенные изменения
			if(isset($_POST['save']))
			{
				if(!empty($_POST['name']) and !empty($_POST['about']) and !empty($_POST['image']) and !empty($_POST['site']) and !empty($_POST['email']) and !empty($_POST['tel']) and !empty($_POST['city']) and !empty($_POST['address']) and !empty($_POST['org_id']))
				{
					$view->content=Organizations::get()->editOrganization($_SESSION['loginfo']['id'], $_POST['org_id'], $_POST['name'], $_POST['about'], $_POST['image'], $_POST['site'], $_POST['email'], $_POST['tel'], $_POST['city'], $_POST['address']);	
					($view->content)? $view->content='Информация о организации успешно обновлена!' : $view->content='Возникла ошибка при обновдении организации!';
				}
				
				$view->content.='<br><span id="add_test_error"><a href="/organizations/my"><< Назад к моим организациям</a></span>';
				$view->title='Изменение организации...';
				$result=$view->render('../views/default.php');
				
			}
			//если пользователь хочет удалить оргинизацию
			elseif(isset($_POST['delete']))
			{
				if(!empty($_POST['org_id']))
				{
					$view->content=Organizations::get()->deleteOrganization($_POST['org_id'], $_SESSION['loginfo']['id']);
					($view->content)? $view->content='Организация, её сотрудники и созданные для неё тесты успешно удалены!' : $view->content='Ошибка при удалении организации!';	
				}
				else
					$view->content='Необходимых данных для удаления организации не найдено!';
				
				$view->content.='<br><span id="add_test_error"><a href="/organizations/my"><< Назад к моим организациям</a></span>';
				$view->title='Удаленте организации...';
				$result=$view->render('../views/default.php');
			}
			//выводим данны при отсутствии нажатий на кнопки формы
			else
			{
				//получение массива с данными о организациях пользователя
				$view->content=Organizations::get()->myOrganiztions($_SESSION['loginfo']['id']);
				(is_array($view->content)) ? $view->content : $view->content='Возникла ошибка при выбоке Ваших тестов!';
				$view->cities=Organizations::get()->getCities();
				$view->title='Мои организации';
				$result=$view->render('../views/organization.php');
				
			}
			
			$view->keywords='Организация, моя, моя организация';
			$view->description='Организация пользователя';
			$fc->setBody($result);
		}
		//если зашел гость перенаправляем его с этой страницы на заглавную
		else
			header('Location: ../');
		
	}
	
	//метод добавления организации пользователя!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	function addAction()
	{
		//если зашел пользователь с типом регистрации начальник
		if($_SESSION['loginfo']['type_id']==6)
		{
			$fc=FrontController::get();
			$view=new View();
			//при нажатии на кнопку "Добавить организацию"
			if(isset($_POST['add']))
			{
				if(!empty($_POST['name']) and !empty($_POST['about']) and !empty($_POST['image']) and !empty($_POST['site']) and !empty($_POST['email']) and !empty($_POST['tel']) and !empty($_POST['city']) and !empty($_POST['address']) and ($_POST['user_id']==$_SESSION['loginfo']['id']))
				{
					$view->content=Organizations::get()->addOrganization($_POST['user_id'], $_POST['name'], $_POST['about'], $_POST['image'], $_POST['site'], $_POST['email'], $_POST['tel'], $_POST['city'], $_POST['address']);
					
					($view->content) ? $view->content='Организация успешно добавлена!<br><span id="add_test_error"><a href="/organizations/my"><< Вернутся к моим организациям</a></span>': $view->content='Возникла ошибка при добавлении новой организации!<br><span id="add_test_error"><a href="/organizations/add"><< Назад к форме создания организации</a></span>';
			
				}
				//если значения нулевые и некорректный идентификатор пользователя
				else
				{
			$view->content='Некорректно заполнены поля ввода! Они не должны содержать пустых значений!<br><span id="add_test_error"><a href="/organizations/add"><< Назад к форме создания организации</a></span>';
			$view->title='Новая организация';
				}
				
				$view->keywords='добавление, организация, моя организация';
				$view->description='Добавление организации пользователя';
				$result=$view->render('../views/default.php');
			}
			else
			{
			//вывод формы для добавления организации
			$view->user_id=$_SESSION['loginfo']['id'];
			$view->cities=Organizations::get()->getCities();
			$view->keywords='добавление, организация, моя организация';
			$view->description='Добавление организации пользователя';
			$view->title='Новая организация';
			$result=$view->render('../views/organization.php');
			}
			$fc->setBody($result);
		}
		//если зашел гость перенаправляем его с этой страницы на заглавную
		else
			header('Location: ../');
		
	}
	
	//метод добавления редактирования и просмотра результатов сотрудников
	function workersAction()
	{
		//если зашел начальник
		if($_SESSION['loginfo']['type_id']==6)
		{
		 $fc=FrontController::get();
		 $view=new View();
		 //если пользователь хочет удалить сотрудника
		 if(isset($_POST['delete']))
		 {
			 if(!empty($_POST['worker_id']))
			 {
			 $view->content=Organizations::get()->deleteWorker($_POST['worker_id']);
			($view->content) ? $view->content='Сотрудник успешно удалён!': $view->content='Возникла ошибка при удалении сотрудника!';
			 }
			 else 
			 	$view->content.='Необходимых данных для удаления сотрудника не найдено!';
				
			$view->content.='<br><span id="add_test_error"><a href="/organizations/workers"><< Назад к списку сотрудников</a></span>';
			$view->title='Удаление сотрудника...';
			$result=$view->render('../views/default.php');
		 }
		 //если пользователь хочет изменить данные о сотруднике
		 elseif(isset($_POST['save']))
		 {
			if(!empty($_POST['org_id']) and !empty($_POST['fio']) and !empty($_POST['address']) and !empty($_POST['tel']) and !empty($_POST['email']) and !empty($_POST['date']) and !empty($_POST['worker_id']))
			{
				
				$view->content=Organizations::get()->editWorker($_POST['worker_id'], $_POST['org_id'], $_POST['fio'], $_POST['address'], $_POST['tel'], $_POST['email'], $_POST['date']);
				($view->content)? $view->content='Информация о сотруднике успешно обновлена!': $view->content='При обновлении информации о сотруднике возникла ошибка!';
			}
			else
			 $view->content.='Необходимые данные для редактирования сотрудника не найдены!';
				
			$view->content.='<br><span id="add_test_error"><a href="/organizations/workers"><< Назад к списку сотрудников</a></span>';
			$view->title='Редактирование сотрудника...';
			$result=$view->render('../views/default.php');
		 }
		 else
		 {
			//получение списка сотрудников добавленных пользователем
			$view->workers=Organizations::get()->getWorkers($_SESSION['loginfo']['id']);
			(is_array($view->workers))? $view->content='Полный список сотрудников Ваших организаций:': $view->content=$view->workers.'<br><span id="add_test_error"><a href="/organizations/addworker"><< Добавить сотрудника</a></span>';
			$view->organizations=Organizations::get()->myOrganiztionsNames($_SESSION['loginfo']['id']);
			(is_array($view->organizations))? $view->organizations: $view->content='Ошибка при выборке организаций!';
			$view->title='Все сотрудники';
			$result=$view->render('../views/workers.php');
		 }
		 $view->keywords='добавление, сотрудники, мои сотрудники';
		 $view->description='Добавление организации пользователя';
		 $fc->setBody($result);
		}
		//если зашел гость перенаправляем его с этой страницы на заглавную
		else
			header('Location: ../');
			
	}
	
	//метод добавляет нового сотрудника организации
	function addworkerAction()
	{
		//если зашел начальник
		if($_SESSION['loginfo']['type_id']==6)
		{
		 $fc=FrontController::get();
		 $view=new View();
		 //добавление нового сотрудника
		 if(isset($_POST['add']))
		 {
			 if(!empty($_POST['fio']) and !empty($_POST['address']) and !empty($_POST['tel']) and !empty($_POST['email']) and !empty($_POST['orgs']))
			 {
				 $view->content=Organizations::get()->addWorker($_SESSION['loginfo']['id'], $_POST['fio'], $_POST['address'], $_POST['tel'], $_POST['email'], $_POST['orgs']);
			 	($view->content)? $view->content='Новый сотрудник успешно добавлен в базу данных!': $view->content='Данный сотрудник уже существует в базе данных!';
			 }
			 else
			 	 $view->content='Все поля должны содержать корректные данные и не быть пустыми!';
				 
				 $view->content.='<br><span id="add_test_error"><a href="/organizations/workers"><< К списку сотрудников</a></span>';				 $result=$view->render('../views/default.php');
		 }
		 else
		 {//действие при добавлении организации
			 $view->addworker='Заполните все нижеследующие поля формы и нажмите на кнопку "Добавить"';
			 $view->organizations=Organizations::get()->myOrganiztionsNames($_SESSION['loginfo']['id']);
			 (is_array($view->organizations))? $view->organizations: $view->content='Ошибка при выборке организаций!';
			 $view->title='Добавление сотрудника';
			 $view->keywords='добавление, сотрудники, мои сотрудники';
			 $view->description='Добавление сотрудника организации';
			 $result=$view->render('../views/workers.php');
			 
		 }
		 $fc->setBody($result);
		}
		//если зашел гость перенаправляем его с этой страницы на заглавную
		else
			header('Location: ../');
	}
	
	//метод выводит сотрудников определенной организации
	function orgworkersAction()
	{
		//если зашел начальник
		if($_SESSION['loginfo']['type_id']==6)
		{
		 $fc=FrontController::get();
		 $view=new View();
		 $url=$fc->getParams();
		//вывод списка сотрудников организации 
		if(is_int((int)$url['id'])) 
		{ 
			$view->workers=Organizations::get()->getOrgWorkers((int)$url['id']);
			$view->organizations=Organizations::get()->myOrganiztionsNames($_SESSION['loginfo']['id']);
			(!is_array($view->workers) or !is_array($view->organizations))? $view->content=$view->workers : $view->content;
			
			$view->title='Сотрудники организации'; 
			$view->keywords='добавление, сотрудники, мои сотрудники';
			$view->description='Добавление сотрудника организации';
			$result=$view->render('../views/workers.php'); 
			$fc->setBody($result);
			
		}
		else
			header('Location: /organizations/my');
		
		
		}
		//если зашел гость перенаправляем его с этой страницы на заглавную
		else
			header('Location: ../');
	}
	
	//метод возвращает результаты прохождения тестов в виде списка!!!!!!!!!!!!!!!!!!!!!
	function resultsAction()
	{
		if(is_array($_SESSION['loginfo']))
		{
		$fc=FrontController::get();
		//создание экземпляра модели "вида"
		$view=new View();
		//получение данных в виде ассоциативных массивов из базы данных
		$view->content=Organizations::get()->getOrgTestResults($_SESSION['loginfo']['id']);
		$view->test_name=Organizations::get()->getOrgTestNameById($_SESSION['loginfo']['id']);
		$view->org_name=Organizations::get()->getOrgNameByUserId($_SESSION['loginfo']['id']);
		$view->worker_fio=Organizations::get()->getWorkerFioById($_SESSION['loginfo']['id']);
		
		$view->keywords='результаты, прохождение теста, результаты прохождения тестов';
		$view->description='Результаты прохождения созданных мной тестов';
		$view->title='Результаты тестов';
		$result=$view->render('../views/results.php');
		
		$fc->setBody($result);
		
		}
		else header('Location: ../');
		
	}
	
	//метод выводит организацию пользователя
	function workerAction()
	{
		if($_SESSION['loginfo']['type_id']==5)
		{
		$fc=FrontController::get();
		//создание экземпляра модели "вида"
		$view=new View();
		
		//получение данных о наличии сотрудника в списках организаций
		$view->worker_org=Organizations::get()->checkWorkerInOrg($_SESSION['loginfo']);
		
		$view->keywords='организация, организация сотрудника, информация о организации';
		$view->description='Информация о организации сотрудника';
		$view->title='Моя организация';
		$result=$view->render('../views/organization.php');
		
		$fc->setBody($result);
		
		}
		else header('Location: ../');
	}
	
	//метод выводит список тестов организации
	function orgtestsAction()
	{
		if($_SESSION['loginfo']['type_id']==5)
		{
		$fc=FrontController::get();
		//создание экземпляра модели "вида"
		$view=new View();
		
		//получение данных в виде ассоциативных массивов из базы данных
		$view->orgtests=Organizations::get()->getOrgTests($_SESSION['loginfo']);
		
		$view->keywords='организация, организация сотрудника, тесты организации';
		$view->description='Информация о тестах организации';
		$view->title='Тесты организации';
		$result=$view->render('../views/organization.php');
		
		$fc->setBody($result);
		
		}
		else header('Location: ../');
		
	}
	
	//метод выводит результаты прохождения тестов!!!!!!!!!!!!!!
	function myresultsAction()
	{
		//если адрес страницы введен сотрудником
		if($_SESSION['loginfo']['type_id']==5)
		{
		$fc=FrontController::get();
		//создание экземпляра модели "вида"
		$view=new View();
		
		//получение данных в виде ассоциативных массивов из базы данных
		$view->workerresults=Organizations::get()->getWorkerResults($_SESSION['loginfo']);
		
		$view->keywords='организация, организация сотрудника, тесты организации, мои результаты';
		$view->description='Информация о прохождении теста';
		$view->title='Мои результаты';
		$result=$view->render('../views/organization.php');
		
		$fc->setBody($result);
		
		}
		else header('Location: ../');
		
	}
}

?>