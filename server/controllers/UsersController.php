<? 
//контроллер вызываемый по запросу /users
class UsersController implements IController
{
	function indexAction()
	{
		$fc=FrontController::get();
		//создание экземпляра модели "вида"
		$view=new View();
		$view->userstests=Users::get()->getAllUsersAndTests();
		$view->keywords='Дистанционное тестирование, тестирование школьников, тестирование студентов, тестирование сотрудников, онлайн, тестирование, пройти тестирование, создать тест, о ИС, описание';
		$view->description='Информационная система дистанционного тестирования знаний, для студентов, пользователей, школьников и сотрудников предприятий. Вы можете легко создать или пройти тест...';
		$view->title='Пользователи и их тесты';
		$result=$view->render('../views/tests.php');
		$fc->setBody($result);
		
	}
	
	//редактирование типов пользователей
	function typesAction()
	{
		//если пришел администратор
		if($_SESSION['loginfo']['login']==='admin')
		{
		$fc=FrontController::get();
		//создание экземпляра модели "вида"
		$view=new View();
		
		//сохранение нового названия для типа
		if(isset($_POST['save']))
		{
			if(!empty($_POST['type_id']) and !empty($_POST['name']))
			{
				$view->content=Users::get()->saveTypeName($_POST['type_id'], $_POST['name']);
				($view->content) ? $view->content='Новое название типа пользователя успешно сохранено!': $view->content='Ошибка при сохранении нового названия типа пользователя!';
				$view->content.='<br><span id="add_test_error"><a href="/users/types">< < Вернутся к типам пользователей</a></span>';
			}
			else
				$view->content.='Поле не должно содержать пустое значение!<br><span id="add_test_error"><a href="/users/types">< < Вернутся к типам пользователей</a></span>';
			$view->title='Сохранение названия типа пользователя';
			$result=$view->render('../views/default.php');
		}
		else
		{
			$view->title='Типы пользователей';
			$view->usertypes=Users::get()->getUserTypesList();
			$result=$view->render('../views/edit.php');
		}
		$fc->setBody($result);
		}
		else
			header('Location: ../');
	}
	
	//редактирование пользователей
	function editAction()
	{
		//если пришел администратор
		if($_SESSION['loginfo']['login']==='admin')
		{
		$fc=FrontController::get();
		$view=new View();
		
		//удаление пользователей
		if(isset($_POST['delete']))
		{
			$view->title='Удаление пользователя...';
			
			if(!empty($_POST['user_id']) and !empty($_POST['user_type']))
			{
			$view->content=Users::get()->deleteUser($_POST['user_id'], $_POST['user_type']);
			($view->content)? $view->content='Пользователь и все созданные им данные удалены!': $view->content='Ошибка при удалении пользователя!';
			$view->content.='<br><span id="add_test_error"><a href="/users/edit">< < Вернутся к списку пользователей</a></span>';
			
			}
			else
				$view->content.='Необходимые данные для удаления пользователя не найдены!<br><span id="add_test_error"><a href="/users/edit">< < Вернутся к списку пользователей</a></span>';
			
			$result=$view->render('../views/default.php');
		}
		else
		{
			$view->title='Список пользователей';
			$view->users=Users::get()->getUsersList();
			$result=$view->render('../views/edit.php');
		}
		$fc->setBody($result);
		}
		else
			header('Location: ../');
		
	}
}

?>