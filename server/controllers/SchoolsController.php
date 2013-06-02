<? 
//контроллер вызываемый по запросу /schools
class SchoolsController implements IController
{
	function indexAction()
	{
		$fc=FrontController::get();
		//создание экземпляра модели "вида"
		$view=new View();
		$view->schoolstests=Schools::get()->getAllSchoolsTests();
		$view->keywords='Дистанционное тестирование, тестирование школьников, тестирование студентов, тестирование сотрудников, онлайн, тестирование, пройти тестирование, создать тест, о ИС, описание';
		$view->description='Информационная система дистанционного тестирования знаний, для студентов, пользователей, школьников и сотрудников предприятий. Вы можете легко создать или пройти тест...';
		$view->title='Все школы и тесты';
		$result=$view->render('../views/tests.php');
		$fc->setBody($result);
		
	}
	
	//метод выводит университеты школы добавленные пользователем!!!!!!!!!
	function myAction()
	{
		if($_SESSION['loginfo']['type_id']==4)
		{
		$fc=FrontController::get();
		$view=new View();
		$view->myschools=Schools::get()->getMySchool($_SESSION['loginfo']['id']);
		$view->cities=Schools::get()->getCities();
		$view->title='Мои школы';
		$result=$view->render('../views/school.php');
		$fc->setBody($result);
		}
		else header('Location: ../');
	}
	
	//метод добавляет новую школу пользователя в базу
	function addAction()
	{	//если зашел пользователь с рангом "учитель"
		if($_SESSION['loginfo']['type_id']==4)
		{
		$fc=FrontController::get();
		$view=new View();
		$view->title='Добавление школы...';
		//действие при добавлении школы
		if(isset($_POST['add']))
		{
			//проверка наличия необходимых данных для добавления школы
			if(!empty($_POST['name']) and !empty($_POST['about']) and !empty($_POST['image']) and !empty($_POST['site']) and !empty($_POST['email']) and !empty($_POST['tel']) and !empty($_POST['city']) and !empty($_POST['address']) and !empty($_POST['user_id']))
			{
			 $view->content=Schools::get()->addSchool($_POST['name'], $_POST['about'], $_POST['image'], $_POST['site'], $_POST['email'], $_POST['tel'], $_POST['city'], $_POST['address'], $_POST['user_id']);
			($view->content)? $view->content='Школа успешно добавлена!': $view->content='Данный школа уже была добавлена в базу данных!';
			$view->content.='<br><span id="add_test_error"><a href="/schools/my">< < Назад к моим школам</a></span>';
			}
			
			
			else
				$view->content='Необходимые данные для добавления школы не найдены!<br><span id="add_test_error"><a href="/schools/add">< < Добавить школу</a></span>';
			
			$result=$view->render('../views/default.php');
		}
		else
		{
			$view->user_id=$_SESSION['loginfo']['id'];
			$view->cities=Schools::get()->getCities();
			$result=$view->render('../views/school.php');
		}
	
		$fc->setBody($result);
		}
		else header('Location: ../');
	}

	//редактирование информации о школе
	function editAction()
	{//если зашел пользователь с рангом "учитель"
		if($_SESSION['loginfo']['type_id']==4)
		{
			$fc=FrontController::get();
			$view=new View();
			$view->title='Редактирование школы...';
		
			//редактирование
			if(isset($_POST['save']))
			{
				if(!empty($_POST['name']) and !empty($_POST['about']) and !empty($_POST['image']) and !empty($_POST['site']) and !empty($_POST['email']) and !empty($_POST['tel']) and !empty($_POST['city']) and !empty($_POST['address']) and !empty($_POST['user_id']))
				{
					$view->content=Schools::get()->editMySchool($_POST['school_id'], $_SESSION['loginfo']['id'], $_POST['name'], $_POST['image'], $_POST['city'], $_POST['address'], $_POST['about'], $_POST['site'], $_POST['email'], $_POST['tel']);
				 	($view->content)? $view->content='Информация о школе успешно сохранена!': $view->content='При редактировании информации о школе возникли ошибки!';	
				$view->content.='<br><span id="add_test_error"><a href="/schools/my"><< К моим школам</a></span>';
					
				}
				else
				{
					$view->title='Ошибка при редактировании...';
					$view->content='Поля не должны содержать пустые значения!<br><span id="add_test_error"><a href="/schools/my"><< К моим школам</a></span>';
				}
			}
			//удаление школы
			elseif(isset($_POST['delete']))
			{
				if(!empty($_POST['school_id']))
				{
					$view->title='Удаление школы...';
					$view->content=Schools::get()->deleteMySchool($_POST['school_id'], $_SESSION['loginfo']['id']);
					($view->content)? $view->content='Данные о школе, учениках, тестах и результатов их прохождения успешно удалены!': $view->content;	
					$view->content.='<br><span id="add_test_error"><a href="/schools/my"><< К моим школам</a></span>';
				}
				else
				{
					$view->title='Ошибка при удалении...';
					$view->content='Необходимые данные для удаления школы не найдены!<br><span id="add_test_error"><a href="/schools/my"><< К моим школам</a></span>';
				}
			}
			$result=$view->render('../views/default.php');
			$fc->setBody($result);
		}
		else header('Location: ../');
	}
	
	//выводим классы школы
	function classesAction()
	{
		$fc=FrontController::get();
		$view=new View();	
		
		//если зашел пользователь с рангом "учитель"
		if($_SESSION['loginfo']['type_id']==4)
		{
			$fc=FrontController::get();
			$view=new View();
			
			//сохраняем изменения в названии класса
			if(isset($_POST['save']))
			{
				$view->title='Редактирование класса...';
				if(!empty($_POST['class_id']) and !empty($_POST['name']))
				{
					$view->content=Schools::get()->editClass($_POST['class_id'], $_POST['name']);
					($view->content)? $view->content='Изменения успешно сохранены!': $view->content='Ошибка при сохранении информации о классе!';
					$view->content.='<br><span id="add_test_error"><a href="/schools/my"><< К моим школам</a></span>';
				}
				else
				{
					$view->content='Необходимые данные для изменения информации о классе не найдены!<br><span id="add_test_error"><a href="/schools/my"><< К моим школам</a></span>';
				}	
			}
			//удаляет класс и все дочерние сущности
			elseif(isset($_POST['delete']))
			{
				$view->title='Удаление класса...';
				if(!empty($_POST['class_id']))
				{
					$view->content=Schools::get()->deleteClass($_POST['class_id']);
					($view->content)? $view->content='Класс, ученики данного класса, тесты и результаты их прохождения успешно удалены!': $view->content;
					$view->content.='<br><span id="add_test_error"><a href="/schools/my"><< К моим школам</a></span>';
				}
				else 
					$view->content='Необходимые данные для удаления класса не найдены!<br><span id="add_test_error"><a href="/schools/my"><< К моим школам</a></span>';
			}
			//добавление нового класса
			elseif(isset($_POST['add']))
			{
				$view->title='Добавление класса...';
				if(!empty($_POST['school_id']) and !empty($_POST['name']))
				{
					$view->content=Schools::get()->addNewClass($_POST['school_id'], $_POST['name']);
					($view->content)? $view->content='Новый класс успешно добавлен!': $view->content='Данный класс уже был добавлен к базу данных';		
					$view->content.='<br><span id="add_test_error"><a href="/schools/my"><< К моим школам</a></span>';
				}
				else 
					$view->content='Поля не должны содержать пустые значения!<br><span id="add_test_error"><a href="/schools/my"><< К моим школам</a></span>';
			}
			//выводим все классы школы
			else
			{
				$view->title='Классы школы...';
				$view->classes=Schools::get()->getSchoolClasses($_POST['school_id']);
				$view->school_id=$_POST['school_id'];
			}
			
			$result=$view->render('../views/school.php');
			$fc->setBody($result);
		}
		else header('Location: ../');
	}
	
	//выводим предметы класса
	function lessonsAction()
	{
		$fc=FrontController::get();
		$view=new View();	
		
		//если зашел пользователь с рангом "учитель"
		if($_SESSION['loginfo']['type_id']==4)
		{
			$view->title='Все предметы класса';

			//добавление нового предмета
			if(isset($_POST['add']))
			{
				$view->title='Добавление предмета...';
				if(!empty($_POST['name']) and !empty($_POST['class_id']))
				{
					$view->content=Schools::get()->addLesson($_POST['class_id'], $_POST['name']);	
					($view->content)? $view->content='Новый предмет успешно добавлен к классу!': $view->content='Данный предмет уже был добавлен в базу данных!';
					$view->content.='<br><span id="add_test_error"><a href="/schools/my"><< К моим школам</a></span>';
				}
				else
					$view->content='Необходимые данные для добавления предмета не найдены!<br><span id="add_test_error"><a href="/schools/my"><< К моим школам</a></span>';
			}
			//удаляем предмет
			elseif($_POST['delete'])
			{
				$view->title='Удаление предмета...';
				if(!empty($_POST['lesson_id']))
				{
					$view->content=Schools::get()->deleteLesson($_POST['lesson_id']);	
					($view->content)? $view->content='Предмет, тесты, и результаты их прохождения успешно удалены!': $view->content;
					$view->content.='<br><span id="add_test_error"><a href="/schools/my"><< К моим школам</a></span>';
				}
				else
					$view->content='Необходимые данные для удаления предмета не найдены!<br><span id="add_test_error"><a href="/schools/my"><< К моим школам</a></span>';
				
			}
			//сохраняем изменения в предмете
			elseif($_POST['save'])
			{
				$view->title='Редактирование предмета...';
				
				if(!empty($_POST['lesson_id']) and !empty($_POST['name']))
				{
					$view->content=Schools::get()->editLesson($_POST['lesson_id'], $_POST['name']);	
					($view->content)? $view->content='Информация о предмете успешно обновлена!': $view->content='Ошибка при редактировании информации о предмете!';
					$view->content.='<br><span id="add_test_error"><a href="/schools/my"><< К моим школам</a></span>';
				}
				else
					$view->content='Поля не должны содержать пустые значения!<br><span id="add_test_error"><a href="/schools/my"><< К моим школам</a></span>';
						
			}
			//выводим все предметы класса
			elseif(!empty($_POST['class_id']))
			{
				$view->lessons=Schools::get()->getAllLessons($_POST['class_id']);
				$view->class_id=$_POST['class_id'];
			}
			else
				$view->content='Необходимые данные для вывода предметов класса не найдены!<br><span id="add_test_error"><a href="/schools/my"><< К моим школам</a></span>';
			
			$result=$view->render('../views/school.php');
			$fc->setBody($result);
		}
		else header('Location: ../');
	}
	
//выводим учеников школ добавленых пользователем!!!!!!!!!!!!
function mypupilsAction()
{
		if($_SESSION['loginfo']['type_id']==4)
		{
		$fc=FrontController::get();
		$view=new View();
		//редактирование данных о школьнике
		if(isset($_POST['save']))
		{
			if(!empty($_POST['fio']) and !empty($_POST['email']) and !empty($_POST['tel']) and !empty($_POST['address']) and !empty($_POST['city']) and !empty($_POST['school']) and !empty($_POST['class']) and !empty($_POST['pupil_id']))
			{
				$view->content=Schools::get()->updatePupil($_POST['pupil_id'], $_POST['fio'], $_POST['email'], $_POST['tel'], $_POST['address'], $_POST['city'], $_POST['school'], $_POST['class']);
				($view->content)? $view->content='Информация о школьнике успешно обновлена!': $view->content='Возникла ошибка при обновлении информации о школьнике!';
				$view->content.='<br><span id="add_test_error"><a href="/schools/mypupils"><< К моим ученикам</a></span>';
				
			}
			elseif(!empty($_POST['fio']) and !empty($_POST['email']) and !empty($_POST['tel']) and !empty($_POST['address']) and !empty($_POST['pupil_id']))
			{
				$view->content=Schools::get()->updatePupilPartly($_POST['pupil_id'], $_POST['fio'], $_POST['email'], $_POST['tel'], $_POST['address']);
				($view->content)? $view->content='Информация о школьнике успешно обновлена!': $view->content='Возникла ошибка при обновлении информации о школьнике!';
				$view->content.='<br><span id="add_test_error"><a href="/schools/mypupils"><< К моим ученикам</a></span>';
			}
			else
				$view->content='Необходимые данные для обновления информации не найдены! Поля не должны содержать пустые значения!<br><span id="add_test_error"><a href="/schools/mypupils"><< К моим ученикам</a></span>';
			
			$view->title='Обновление информации о ученике...';
			$result=$view->render('../views/default.php');
		}
		//удаление школьника и его результатов
		elseif(isset($_POST['delete']))
		{
			$view->title='Удаление школьника...';
			if(!empty($_POST['pupil_id']))
			{
				$view->content=Schools::get()->deletePupil($_POST['pupil_id']);
				($view->content)? $view->content='Удалена информация о ученике и результатах прохождения им тестов!': $view->content='Возникла ошибка при удалении ученика!';
				$view->content.='<br><span id="add_test_error"><a href="/schools/mypupils"><< К моим ученикам</a></span>';
			}
			else 
				$view->content='Необходимые данные, для удаления ученика и результатов его тестирования, не найдены! Заполните все поля формы корректно и без пустых значений!<br><span id="add_test_error"><a href="/schools/mypupils"><< К моим ученикам</a></span>';
				$result=$view->render('../views/default.php');
		}
		else
		{
			//получаем данные школьниках добавленых пользователем
			$view->title='Мои ученики';
			$view->mypupils=Schools::get()->getMyPupils($_SESSION['loginfo']['id']);
			$view->cities=Organizations::get()->getCities();
			$result=$view->render('../views/pupils.php');
		}
	
		$fc->setBody($result);
		}
		else header('Location: ../');
	
	}
		
	//добавление нового ученика
	function addpupilAction()
	{
		if($_SESSION['loginfo']['type_id']==4)
		{
		$fc=FrontController::get();
		$view=new View();
		//если пользователь нажал ка кнопку добавить школьника
		if(isset($_POST['add']))
		{
			if(!empty($_POST['fio']) and !empty($_POST['email']) and !empty($_POST['tel']) and !empty($_POST['address']) and !empty($_POST['city']) and !empty($_POST['school']) and !empty($_POST['class']))
			{
				$view->content=Schools::get()->addNewPupil($_POST['fio'], $_POST['email'], $_POST['tel'], $_POST['address'], $_POST['city'], $_POST['school'], $_POST['class']);
				($view->content)? $view->content='Ученик успешно добавлен в базу данных!': $view->content='Данный ученик уже добавлен в базу данных!';
				$view->content.='<br><span id="add_test_error"><a href="/schools/mypupils"><< К моим ученикам</a></span>';
			}
			else
			{
				$view->title='Необходимые данные не найдены';
				$view->content='Необходимые данные для добавления ученика не найдены! Заполните все поля формы корректно и без пустых значений!<br><span id="add_test_error"><a href="/schools/mypupils"><< К моим ученикам</a></span>';
			}
			
			$result=$view->render('../views/default.php');
		}
		else
		{//получаем данные об университетах добавленных пользователем
			$view->addpupil='Заполните все нижеследующие поля и нажмите на кнопку "Добавить":';
			$view->cities=Organizations::get()->getCities();
			$view->title='Добавить ученика';
			$result=$view->render('../views/pupils.php');
		}
		
		
		$fc->setBody($result);
		}
		else header('Location: ../');
	}
	
	//вывод школы ученика и созданных для неё тестов 
	function myschoolAction()
	{
		
		//если пользователь ранга школьник
		if($_SESSION['loginfo']['type_id']==3)
		{
		
		$fc=FrontController::get();
		$view=new View();
		$view->title='Моя школа';
		$view->myschool=Schools::get()->getSchoolByPupil($_SESSION['loginfo']);
		$view->schooltests=Schools::get()->getSchoolTests($_SESSION['loginfo']);
		$result=$view->render('../views/school.php');
		$fc->setBody($result);
		
		}
		else header('Location: ../');
		
	}
	
	//выводим тесты по предметам для класса студента
	function lessonstestsAction()
	{
		//если пользователь ранга школьник
		if($_SESSION['loginfo']['type_id']==3)
		{
		
		$fc=FrontController::get();
		$view=new View();
		$view->title='Тесты по предметам';
		$view->mylessons=Schools::get()->getPupilLessons($_SESSION['loginfo']);
		$view->lessonstests=Schools::get()->getLessonTestsByPupil($_SESSION['loginfo']);
		$result=$view->render('../views/pupils.php');
		$fc->setBody($result);
		
		}
		else header('Location: ../');
	}
	
	//выводим все тесты для класса ученика
	function classtestsAction()
	{
		//если пользователь ранга школьник
		if($_SESSION['loginfo']['type_id']==3)
		{
		
		$fc=FrontController::get();
		$view=new View();
		$view->title='Тесты для моего класса';
		$view->myclass=Schools::get()->getPupilClass($_SESSION['loginfo']);
		$view->classtests=Schools::get()->getClassTestsByPupil($_SESSION['loginfo']);
		$result=$view->render('../views/pupils.php');
		$fc->setBody($result);
		
		}
		else header('Location: ../');
	}
	
	//выводим результаты прохождения тестов школьником
	function myresultsAction()
	{
		//если пользователь ранга школьник
		if($_SESSION['loginfo']['type_id']==3)
		{
		
		$fc=FrontController::get();
		$view=new View();
		$view->title='Мои результаты';
		$view->mytestsresults=Schools::get()->getPupilResults($_SESSION['loginfo']);
		$result=$view->render('../views/results.php');
		$fc->setBody($result);
		
		}
		else header('Location: ../');
	}
}

?>