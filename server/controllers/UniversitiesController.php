<? 
//контроллер вызываемый по запросу /universities
class UniversitiesController implements IController
{
	function indexAction()
	{
		$fc=FrontController::get();
		//создание экземпляра модели "вида"
		$view=new View();
		$view->universtests=Universities::get()->getAllUniverTests();
		$view->keywords='Дистанционное тестирование, тестирование школьников, тестирование студентов, тестирование сотрудников, онлайн, тестирование, пройти тестирование, создать тест, о ИС, описание';
		$view->description='Информационная система дистанционного тестирования знаний, для студентов, пользователей, школьников и сотрудников предприятий. Вы можете легко создать или пройти тест...';
		$view->title='Университеты и тесты';
		$result=$view->render('../views/tests.php');
		$fc->setBody($result);
		
	}
	
	//метод выводит университеты добавленные пользователем!!!!!!!!!
	function myAction()
	{
		if($_SESSION['loginfo']['type_id']==4)
		{
		$fc=FrontController::get();
		//создание экземпляра модели "вида"
		$view=new View();
		//получаем данные об университетах добавленных пользователем
		$view->myunivers=Universities::get()->getMyUniver($_SESSION['loginfo']['id']);
		$view->cities=Universities::get()->getCities();
		$view->keywords='Дистанционное тестирование, тестирование школьников, тестирование студентов, тестирование сотрудников, онлайн, тестирование, пройти тестирование, создать тест, о ИС, описание';
		$view->description='Информационная система дистанционного тестирования знаний, для студентов, пользователей, школьников и сотрудников предприятий. Вы можете легко создать или пройти тест...';
		$view->title='Мои университеты';
		$result=$view->render('../views/university.php');
		$fc->setBody($result);
		}
		else header('Location: ../');
	}
	
	//метод добавляет новый университет пользователя в базу
	function addAction()
	{	//если зашел пользователь с рангом "учитель"
		if($_SESSION['loginfo']['type_id']==4)
		{
		$fc=FrontController::get();
		//создание экземпляра модели "вида"
		$view=new View();
		$view->title='Добавление университета...';
		//действие при добавлении университета
		if(isset($_POST['add']))
		{
			//проверка наличия необходимых данных для добавления университета
			if(!empty($_POST['name']) and !empty($_POST['about']) and !empty($_POST['image']) and !empty($_POST['site']) and !empty($_POST['email']) and !empty($_POST['tel']) and !empty($_POST['city']) and !empty($_POST['address']) and !empty($_POST['user_id']))
			{
			$view->content=Universities::get()->addUniversity($_POST['user_id'], $_POST['name'], $_POST['about'], $_POST['image'], $_POST['site'], $_POST['email'], $_POST['tel'], $_POST['city'], $_POST['address']);
			($view->content)? $view->content='Университет успешно добавлен!': $view->content='Данный университет уже добавлен в базу!';
			$view->content.='<br><span id="add_test_error"><a href="/universities/my">< < Назад к моим университетам</a></span>';
			}
			else
				$view->content='Необходимые данные для добавления университета не найдены!<br><span id="add_test_error"><a href="/universities/add">< < Добавить ВУЗ</a></span>';
			$result=$view->render('../views/default.php');
		}
		else
		{
			$view->user_id=$_SESSION['loginfo']['id'];
			$view->cities=Organizations::get()->getCities();
			$result=$view->render('../views/university.php');
		}
	
		$fc->setBody($result);
		}
		else header('Location: ../');
	}
	
	//метод добавляет структурные подразделения к университету пользователя
	function myfacultiesAction()
	{	//если зашел пользователь с рангом "учитель"
		if($_SESSION['loginfo']['type_id']==4)
		{
		$fc=FrontController::get();
		//создание экземпляра модели "вида"
		$view=new View();
		//удаление факультета
		if(isset($_POST['delete']))
		{
			if(!empty($_POST['fac_id']))
			{
				$view->title='Удаление факультета...';
				$view->content=Universities::get()->deleteFaculty($_POST['fac_id']);
				($view->content)? $view->content='Факультет успешно удалён!': $view->content='Ошибка при удалении факультета!';
				$view->content.='<br><span id="add_test_error"><a href="/universities/my">< < Вернутся к списку университетов</a></span>';
			}
			else
				$view->content='Необходимых данных для удаления университета не найдено!';
			
			$result=$view->render('../views/default.php');
		}
		//редактирование факультета
		elseif(isset($_POST['edit']))
		{
			if(!empty($_POST['fac_id']) and !empty($_POST['name']) and !empty($_POST['courses']) and !empty($_POST['specs']))
			{
				$view->title='Редактирование факультета...';
				$view->content=Universities::get()->editFaculty($_POST['fac_id'], $_POST['name'], $_POST['courses'], $_POST['specs']);				($view->content)? $view->content='Информация о факультете успешно обновлена!': $view->content='Ошибка при редактировании факультета!';
				$view->content.='<br><span id="add_test_error"><a href="/universities/my">< < Вернутся к списку университетов</a></span>';
				
			}
			else
				$view->content='Необходимых данных для редактирования университета не найдено! Поля не должны содержать пустые значения!<br><span id="add_test_error"><a href="/universities/my">< < Вернутся к списку университетов</a></span>';
			
			$result=$view->render('../views/default.php');
		}
		//действие при нажатии на кнопку "факультеты университета"
		elseif(!empty($_POST['univer_id']))
		{
			$view->title='Факультеты ВУЗ`а';
			$view->univer=$_POST['univer_id'];
			$view->courses=Universities::get()->getCoursesByUniver($_POST['univer_id']);
			$view->specs=Universities::get()->getSpecsByUniver($_POST['univer_id']);
			$view->facult=Universities::get()->getUniverFaculties($_POST['univer_id']);
			($view->facult==false)? $view->facult='Ошибка при выборке факультетов университета!':$view->facult;
			$result=$view->render('../views/university.php');
		}
		else
		{
			$view->title='Ошибка при выборке факультетов';
			$view->content='Необходимые данные для выборки факультетов не найдены!<br><span id="add_test_error"><a href="/universities/my">< < Назад к ВУЗ`ам</a></span>';
			$result=$view->render('../views/default.php');
		}
			
		
	
		$view->keywords='Дистанционное тестирование, тестирование школьников, тестирование студентов, тестирование сотрудников, онлайн, тестирование';
		$view->description='Информационная система дистанционного тестирования знаний, для студентов, пользователей, школьников и сотрудников предприятий. Вы можете легко создать или пройти тест...';
		$fc->setBody($result);
		}
		else header('Location: ../');
	}
	
	//метод добавляет новый факультет
	function addfacultyAction()
	{
		//если зашел пользователь с рангом "учитель"
		if($_SESSION['loginfo']['type_id']==4)
		{
		$fc=FrontController::get();
		$view=new View();
		
		if(!empty($_POST['univer_id']) and !empty($_POST['name']) and !empty($_POST['spec']) and !empty($_POST['course']))
		{
			
			$view->content=Universities::get()->addNewFaculty($_POST['univer_id'], $_POST['name'], $_POST['spec'], $_POST['course']);
			($view->content)? $view->content='Новый факультет добавлен успешно!': $view->content='Такой факультет уже есть в базе данных!';
			$view->title='Добавление факультета...';
			$view->content.='<br><span id="add_test_error"><a href="/universities/my">< < Назад к ВУЗ`ам</a></span>';
		}
		else
		{
			$view->title='Ошибка при добавлении факультета';
			$view->content='Необходимые данные для добавления факультета не найдены!<br><span id="add_test_error"><a href="/universities/my">< < Назад к ВУЗ`ам</a></span>';
		}
		
		$result=$view->render('../views/default.php');
		$view->keywords='Дистанционное тестирование, тестирование школьников, тестирование студентов, тестирование сотрудников, онлайн, тестирование';
		$view->description='Информационная система дистанционного тестирования знаний, для студентов, пользователей, школьников и сотрудников предприятий. Вы можете легко создать или пройти тест...';
		$fc->setBody($result);
		}
		else header('Location: ../');
	}	
	
	//метод для редактирования университетов пользователя
	function edituniverAction()
	{
		//если зашел пользователь с рангом "учитель"
		if($_SESSION['loginfo']['type_id']==4)
		{
		$fc=FrontController::get();
		//создание экземпляра модели "вида"
		$view=new View();
		//действие при редактировании университета
		if(isset($_POST['save']))
		{
			if(!empty($_POST['name']) and !empty($_POST['image']) and !empty($_POST['city']) and !empty($_POST['address']) and !empty($_POST['about']) and !empty($_POST['site']) and !empty($_POST['email']) and !empty($_POST['tel']) and !empty($_POST['univer_id']))
			{
				$view->title='Редактирование университета...';
				$view->content=Universities::get()->editMyUniver($_POST['univer_id'], $_SESSION['loginfo']['id'], $_POST['name'], $_POST['image'], $_POST['city'], $_POST['address'], $_POST['about'], $_POST['site'], $_POST['email'], $_POST['tel']);
				($view->content)? $view->content='Информация о университете успешно сохранена!': $view->content='При редактировании информации о университете возникли ошибки!';	
				$view->content.='<br><span id="add_test_error"><a href="/universities/my"><< К моим университетам</a></span>';
			}
			else
			{
				$view->title='Необходимые данные не найдены';
				$view->content='Необходимые данные для редактирования университета не найдены!<br><span id="add_test_error"><a href="/universities/my"><< К моим университетам</a></span>';
			}
			
		}
		//действие при удалении университета
		elseif(isset($_POST['delete']))
		{
			if(!empty($_POST['univer_id']))
			{
				$view->title='Удаление университета...';
				$view->content=Universities::get()->deleteMyUniver($_POST['univer_id'], $_SESSION['loginfo']['id']);
				($view->content)? $view->content='Данные о университете, студентах, тестах данного университета и результатов их прохождения успешно удалены!': $view->content='При удалении университета из базы данных возникла ошибка!';	
				$view->content.='<br><span id="add_test_error"><a href="/universities/my"><< К моим университетам</a></span>';
			}
			else
			{
				$view->title='Необходимые данные не найдены';
				$view->content='Необходимые данные для удаления университета не найдены!<br><span id="add_test_error"><a href="/universities/my"><< К моим университетам</a></span>';
			}
		}
		
		$view->keywords='Дистанционное тестирование, тестирование школьников, тестирование студентов, тестирование сотрудников, онлайн, тестирование';
		$view->description='Информационная система дистанционного тестирования знаний, для студентов, пользователей, школьников и сотрудников предприятий. Вы можете легко создать или пройти тест...';
		$result=$view->render('../views/default.php');
		$fc->setBody($result);
		
		}
		else header('Location: ../');
	}
	
	//метод выводит студентов добавленных пользователем!!!!!!!!!!!!!!!!!!!!
	function mystudentsAction()
	{
		if($_SESSION['loginfo']['type_id']==4)
		{
		$fc=FrontController::get();
		//создание экземпляра модели "вида"
		$view=new View();
		//редактирование данных о студенте
		if(isset($_POST['save']))
		{
			if(!empty($_POST['fio']) and !empty($_POST['email']) and !empty($_POST['tel']) and !empty($_POST['address']) and !empty($_POST['date']) and !empty($_POST['city']) and !empty($_POST['univer']) and !empty($_POST['faculty']) and !empty($_POST['course']) and !empty($_POST['specialty']) and !empty($_POST['group']) and !empty($_POST['student_id']))
			{
				$view->content=Universities::get()->updateStudentAll($_POST['student_id'], $_POST['fio'], $_POST['email'], $_POST['tel'], $_POST['address'], $_POST['date'], $_POST['city'], $_POST['univer'], $_POST['faculty'], $_POST['course'], $_POST['specialty'], $_POST['group']);
				($view->content)? $view->content='Информация о студенте успешно обновлена!': $view->content='Возникла ошибка при обновлении информации остуденте!';
				$view->content.='<br><span id="add_test_error"><a href="/universities/mystudents"><< К моим студентам</a></span>';
				
			}
			elseif(!empty($_POST['fio']) and !empty($_POST['email']) and !empty($_POST['tel']) and !empty($_POST['address']) and !empty($_POST['date']) and !empty($_POST['student_id']))
			{
				$view->content=Universities::get()->updateStudentPartly($_POST['student_id'], $_POST['fio'], $_POST['email'], $_POST['tel'], $_POST['address'], $_POST['date']);
				($view->content)? $view->content='Информация о студенте успешно обновлена!': $view->content='Возникла ошибка при обновлении информации о студенте!';
				$view->content.='<br><span id="add_test_error"><a href="/universities/mystudents"><< К моим студентам</a></span>';
			}
			else
				$view->content='Необходимые данные для обновления информации не найдены! Поля не должны содержать пустые значения!<br><span id="add_test_error"><a href="/universities/mystudents"><< К моим студентам</a></span>';
			
			$view->title='Обновление информации о студенте...';
			$result=$view->render('../views/default.php');
		}
		//удаление студента пользователем
		elseif(isset($_POST['delete']))
		{
			if(!empty($_POST['student_id']))
			{
				$view->content=Universities::get()->deleteStudent($_POST['student_id'], $_SESSION['loginfo']['id']);
				($view->content)?  $view->content='Информация о студенте и результатах прохождения им тестов успешно удалена!': $view->content='Возникла ошибка при удалении информации о студенте!';
				$view->content.='<br><span id="add_test_error"><a href="/universities/mystudents"><< К моим студентам</a></span>';
			}
			else
				$view->content='Необходимые данные для удаления студента не найдены!<br><span id="add_test_error"><a href="/universities/mystudents"><< К моим студентам</a></span>';
			
			$view->title='Удаление студента...';
			$result=$view->render('../views/default.php');
		}
		else
		{
			//получаем данные о студентах добавленных пользователем
			$view->title='Мои студенты';
			$view->mystudents=Universities::get()->getMyStudents($_SESSION['loginfo']['id']);
			$view->cities=Organizations::get()->getCities();
			$result=$view->render('../views/students.php');
		}
	
		$fc->setBody($result);
		}
		else header('Location: ../');
	}
	
	//метод добавляет студента в базу данных!!!!!!!!!!!!!!!!!!!!!!!
	function addstudentAction()
	{
		if($_SESSION['loginfo']['type_id']==4)
		{
		$fc=FrontController::get();
		//создание экземпляра модели "вида"
		$view=new View();
		//если пользователь нажал ка кнопку добавить студента
		if(isset($_POST['add']))
		{
			if(!empty($_POST['fio']) and !empty($_POST['email']) and !empty($_POST['tel']) and !empty($_POST['address']) and !empty($_POST['date']) and !empty($_POST['city']) and !empty($_POST['univer']) and !empty($_POST['faculty']) and !empty($_POST['course']) and !empty($_POST['specialty']) and !empty($_POST['group']))
			{
				$view->content=Universities::get()->addNewStudent($_POST['fio'], $_POST['email'], $_POST['tel'], $_POST['address'], $_POST['date'], $_POST['city'], $_POST['univer'], $_POST['faculty'], $_POST['course'], $_POST['specialty'], $_POST['group']);
				($view->content)? $view->content='Студент успешно добавлен в базу данных!': $view->content='Данный студент уже добавлен в базу данных!';
				$view->content.='<br><span id="add_test_error"><a href="/universities/mystudents"><< К моим студентам</a></span>';
			}
			else
			{
				$view->title='Необходимые данные не найдены';
				$view->content='Необходимые данные для добавления студента не найдены! Заполните все поля формы корректно и без пустых значений!<br><span id="add_test_error"><a href="/universities/mystudents"><< К моим студентам</a></span>';
			}
			
			$result=$view->render('../views/default.php');
		}
		else
		{//получаем данные об университетах добавленных пользователем
			$view->addstudent='Заполните все нижеследующие поля и нажмите на кнопку "Добавить":';
			$view->cities=Organizations::get()->getCities();
			$view->title='Добавить студента';
			$result=$view->render('../views/students.php');
		}
		
		
		$fc->setBody($result);
		}
		else header('Location: ../');
		
	}
	
	//метод для редактирования и добавления студенческих групп факультета
	function mygroupsAction()
	{
		if($_SESSION['loginfo']['type_id']==4)
		{
		$fc=FrontController::get();
		$view=new View();
		//если пользователь нажал кнопку "Студенческие группы факультета"
		if(isset($_POST['groups']))
		{
			if(!empty($_POST['fac_id']))
			{
				$view->groups=Universities::get()->getGroupsByFac($_POST['fac_id']);	
				$view->fac_id=$_POST['fac_id'];	
				$view->specs=Universities::get()->getSpecByFacArr($_POST['fac_id']);
				$view->courses=Universities::get()->getCoursesByFacArr($_POST['fac_id']);
				$view->title='Все группы факультета';
				$result=$view->render('../views/university.php');
			}
			else
			{
				$view->title='Не найден параметр факультета!';
				$view->content='Необходимые данные для вывода студенческих групп факультета не найдены!<br><span id="add_test_error"><a href="/universities/my"><< К моим университетам</a></span>';
				$result=$view->render('../views/default.php');	
			}
			
		}
		//если пользователь добавляет новую студенческую группу
		elseif(isset($_POST['addgroup']))
		{
			if(!empty($_POST['name']) and !empty($_POST['year']) and !empty($_POST['spec']) and !empty($_POST['course']))
			{
				$view->content=Universities::get()->addNewGroup($_POST['name'], $_POST['year'], $_POST['spec'], $_POST['course']);
				($view->content)? $view->content='Новая студенческая группа успешно добавлена!': $view->content='Данная студенческая группа уже есть в базе данных!';
				$view->content.='<br><span id="add_test_error"><a href="/universities/my"><< К моим университетам</a></span>';
				$result=$view->render('../views/default.php');
			}
		}
		else header('Location: ../');
		
		$fc->setBody($result);
		}
		else header('Location: ../');
	}
	
	//метод для вывода результата редактирования студенческих групп 
	function editgroupsAction()
	{
		//если пользователь ранга учитель
		if($_SESSION['loginfo']['type_id']==4)
		{
		$fc=FrontController::get();
		$view=new View();
		$view->title='Редактирование студенческой группы...';
	
		//если пользователь хочет удалить группу
		if(isset($_POST['delete']))
		{
			if(!empty($_POST['group_id']))
			{
				$view->content=Universities::get()->deleteGroup($_POST['group_id']);
				($view->content)? $view->content='Группа, студенты группы и их результаты тестирования успешно удалены!': $view->content='Возникла ошибка при удалении группы!';
			}
			else 
				$view->content='Необходимые данные для удаления группы не найдены!';
		}
		//если пользователь хочет отредактировать группу
		if(isset($_POST['save']))
		{
			if(!empty($_POST['group_id']) and !empty($_POST['name']) and !empty($_POST['year']) and !empty($_POST['course']) and !empty($_POST['spec']))
			{
				$view->content=Universities::get()->editGroup($_POST['group_id'], $_POST['name'], $_POST['year'], $_POST['course'], $_POST['spec']);
				($view->content)? $view->content='Информация о группе успешно обновлена!': $view->content='Возникла ошибка при редактировании группы!';
			}
			else 
				$view->content='Необходимые данные для редактирования группы не найдены!';
		}

		$view->content.='<br><span id="add_test_error"><a href="/universities/my"><< К моим университетам</a></span>';
		$result=$view->render('../views/default.php');
		$fc->setBody($result);
		}
		else header('Location: ../');
	}
	
	//добавление и редактирование предметов
	function lessonsAction()
	{
		//если пользователь ранга учитель
		if($_SESSION['loginfo']['type_id']==4)
		{
		
		$fc=FrontController::get();
		$view=new View();
		
		//действие при нажатии на кнопку предметов группы
		if(isset($_POST['lessons']))
		{
			$view->title='Редактирование предметов...';
			if(!empty($_POST['group_id']))
			{
				$view->lessons=Universities::get()->getGroupLessons($_POST['group_id']);
				$view->group_id=$_POST['group_id'];
				$result=$view->render('../views/university.php');
			}
			else
			{
				$view->content='Необходимые данные для выборки предметов не найдены!<br><span id="add_test_error"><a href="/universities/my"><< К моим университетам</a></span>';
				$result=$view->render('../views/default.php');
			}
		}
		//действие при добавлении предмета
		elseif(isset($_POST['submit']))
		{
			$view->title='Добавление предмета...';
			
			if(!empty($_POST['name']) and !empty($_POST['group_id']))
			{
				$view->content=Universities::get()->addNewLesson($_POST['group_id'], $_POST['name']);
				($view->content)? $view->content='Новый предмет успешно добавлен!': $view->content='Такой предмет уже добавлен!';
				$view->content.='<br><span id="add_test_error"><a href="/universities/my"><< К моим университетам</a></span>';
				
			}
			else
				$view->content='Необходимые данные для добавления предмета не найдены!<br><span id="add_test_error"><a href="/universities/my"><< К моим университетам</a></span>';
				
			$result=$view->render('../views/default.php');
		}
		//если пользователь сохраняет внесенные изменения
		elseif($_POST['save'])
		{
			if(!empty($_POST['id']) and !empty($_POST['name']))
			{
				$view->title='Редактирование предмета...';
				$view->content=Universities::get()->editLesson($_POST['id'], $_POST['name']);
				($view->content)? $view->content='Информация о предмете успешно обновлена!': $view->content='Возникла ошибка при обновлении информации о предмете!';
				$view->content.='<br><span id="add_test_error"><a href="/universities/my"><< К моим университетам</a></span>';
			}
			else
				$view->content='Необходимые данные для редактирования предметов не найдены! Поля не должны содержать пустые значения!<br><span id="add_test_error"><a href="/universities/my"><< К моим университетам</a></span>';
				
			$result=$view->render('../views/default.php');
		}
		elseif($_POST['delete'])
		{
			$view->title='Удаление предмета...';
			
			if(!empty($_POST['id']))
			{
				$view->content=Universities::get()->deleteLesson($_POST['id']);
				($view->content)? $view->content='Предмет, тесты созданные для данного предмета и их результаты успешно удалены!': $view->content='Возникла ошибка при удалении предмета, тестов и результата их прохождения!';
				$view->content.='<br><span id="add_test_error"><a href="/universities/my"><< К моим университетам</a></span>';
			}
			else
				$view->content='Необходимых данных для удаления предмета не найдено!<br><span id="add_test_error"><a href="/universities/my"><< К моим университетам</a></span>';
			
			$result=$view->render('../views/default.php');
		}
		
		
		$fc->setBody($result);
		
		}
		else header('Location: ../');
	}
	
	//выводим университет студента и список тестов созданных для студентов университета
	function myuniverAction()
	{
			//если пользователь ранга студент
		if($_SESSION['loginfo']['type_id']==2)
		{
		
		$fc=FrontController::get();
		$view=new View();
		$view->title='Мой ВУЗ';
		$view->myuniver=Universities::get()->getUniverByStudent($_SESSION['loginfo']);
		$view->univertests=Universities::get()->getUniverTests($_SESSION['loginfo']);
		$result=$view->render('../views/university.php');
		$fc->setBody($result);
		
		}
		else header('Location: ../');
	}
	
	//выводим все тесты для группы студента
	function grouptestsAction()
	{
			//если пользователь ранга студент
		if($_SESSION['loginfo']['type_id']==2)
		{
		
		$fc=FrontController::get();
		$view=new View();
		$view->title='Тесты для моей группы';
		$view->mygroup=Universities::get()->getStudentGroup($_SESSION['loginfo']);
		$view->grouptests=Universities::get()->getGroupTestsByStudent($_SESSION['loginfo']);
		$result=$view->render('../views/students.php');
		$fc->setBody($result);
		
		}
		else header('Location: ../');
	}
	
	//выводим тесты по предметам для группы студента
	function lessonstestsAction()
	{
		//если пользователь ранга студент
		if($_SESSION['loginfo']['type_id']==2)
		{
		
		$fc=FrontController::get();
		$view=new View();
		$view->title='Тесты по предметам';
		$view->mylessons=Universities::get()->getStudentLessons($_SESSION['loginfo']);
		$view->lessonstests=Universities::get()->getLessonTestsByStudent($_SESSION['loginfo']);
		$result=$view->render('../views/students.php');
		$fc->setBody($result);
		
		}
		else header('Location: ../');
	}
	
	//выводим результат тестирование студента
	function myresultAction()
	{
		//если пользователь ранга студент
		if($_SESSION['loginfo']['type_id']==2)
		{
		
		$fc=FrontController::get();
		$view=new View();
		$view->title='Мои результаты';
		$view->myresults=Universities::get()->getStudentResults($_SESSION['loginfo']);
		$result=$view->render('../views/results.php');
		$fc->setBody($result);
		
		}
		else header('Location: ../');
		
	}
}

?>