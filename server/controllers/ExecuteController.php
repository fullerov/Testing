<? 
//контроллер вызываемый по запросу /execute
class ExecuteController implements IController
{
	//метод вызываемый изначально
	function indexAction()
	{
		$fc=FrontController::get();
		//создание экземпляра модели "вида"
		$view=new View();
		$view->keywords='Дистанционное тестирование, тестирование школьников, тестирование студентов, тестирование сотрудников, онлайн, тестирование, пройти тестирование, список всех тестов';
		$view->description='Информационная система дистанционного тестирования знаний, для студентов, пользователей, школьников и сотрудников предприятий. Вы можете легко создать или пройти тест...';
		
		//получение информации о тестах из БД, указание диапазона выборки тестов
		$view->usertests=Execute::getUserTests(1, 100);
		$view->orgtests=Execute::getOrgTests(1, 100);
		$view->univertests=Execute::getUniverTests(1,100);
		$view->schooltests=Execute::getSchoolTests(1,100);
		//проверка наличия тестов в базе данных
		if(is_array($view->orgtests) or is_array($view->usertests) or is_array($view->univertests) or is_array($view->schooltests))
		{
			$view->content='Список последних пяти тестов по всем категориям, для того чтобы вывести полный список тестов определенной категории щелкните на её названии, а чтобы пройти тест кликните по его заголовку:';
			$view->title='Все тесты';
			$result=$view->render('../views/execute.php');
		}
		else
		{
			$view->content.='<b>В базе данных еще нет ни одного теста!</b>';
			$view->title='Нет тестов в БД';
			$result=$view->render('../views/default.php');
		}
		
		$fc->setBody($result);
		
	}
	
	//метод выводит все организационные тесты
	function orgallAction()
	{
		$fc=FrontController::get();
		//создание экземпляра модели "вида"
		$view=new View();
		$view->keywords='Дистанционное тестирование, тестирование школьников, тестирование студентов, тестирование сотрудников, онлайн, тестирование, пройти тестирование, список всех тестов';
		$view->description='Информационная система дистанционного тестирования знаний, для студентов, пользователей, школьников и сотрудников предприятий. Вы можете легко создать или пройти тест...';
		
		//получение информации о тестах из БД, указание диапазона выборки тестов
		$view->orgtests=Execute::getOrgTests(1, 10000);

		//проверка наличия тестов в базе данных
		if(is_array($view->orgtests))
		{
			$view->content='Для того чтобы пройти тест, кликните по заголовку теста:';
			$view->title='Организационные тесты';
			$result=$view->render('../views/execute.php');
		}
		else
		{
			$view->content.='<b>В базе данных еще нет ни одного теста!</b>';
			$view->title='Нет организационных тестов в БД';
			$result=$view->render('../views/default.php');
		}
		
		$fc->setBody($result);
	}
	
	//выводим все университетские тесты
	function univerallAction()
	{
		$fc=FrontController::get();
		//создание экземпляра модели "вида"
		$view=new View();
		$view->keywords='Дистанционное тестирование, тестирование школьников, тестирование студентов, тестирование сотрудников, онлайн, тестирование, пройти тестирование, список всех тестов';
		$view->description='Информационная система дистанционного тестирования знаний, для студентов, пользователей, школьников и сотрудников предприятий. Вы можете легко создать или пройти тест...';
		
		//получение информации о тестах из БД, указание диапазона выборки тестов
		$view->univertests=Execute::getUniverTests(1,10000);

		//проверка наличия тестов в базе данных
		if(is_array($view->univertests))
		{
			$view->content='Для того чтобы пройти тест, кликните по заголовку теста:';
			$view->title='Университетские тесты';
			$result=$view->render('../views/execute.php');
		}
		else
		{
			$view->content.='<b>В базе данных еще нет ни одного теста!</b>';
			$view->title='Нет университетских тестов в БД';
			$result=$view->render('../views/default.php');
		}
		
		$fc->setBody($result);
		
	}
	
	//выводим все школьные тесты 
	function schoolallAction()
	{
		$fc=FrontController::get();
		//создание экземпляра модели "вида"
		$view=new View();
		$view->keywords='Дистанционное тестирование, тестирование школьников, тестирование студентов, тестирование сотрудников, онлайн, тестирование, пройти тестирование, список всех тестов';
		$view->description='Информационная система дистанционного тестирования знаний, для студентов, пользователей, школьников и сотрудников предприятий. Вы можете легко создать или пройти тест...';
		
		//получение информации о тестах из БД, указание диапазона выборки тестов
		$view->schooltests=Execute::getSchoolTests(1,10000);
		//проверка наличия тестов в базе данных
		if(is_array($view->schooltests))
		{
			$view->content='Для того чтобы пройти тест, кликните по заголовку теста:';
			$view->title='Школьные тесты';
			$result=$view->render('../views/execute.php');
		}
		else
		{
			$view->content.='<b>В базе данных еще нет ни одного теста!</b>';
			$view->title='Нет школьных тестов в БД';
			$result=$view->render('../views/default.php');
		}
		
		$fc->setBody($result);
	}
	
	//выводим все пользовательские тесты
	function userallAction()
	{
		$fc=FrontController::get();
		//создание экземпляра модели "вида"
		$view=new View();
		$view->keywords='Дистанционное тестирование, тестирование школьников, тестирование студентов, тестирование сотрудников, онлайн, тестирование, пройти тестирование, список всех тестов';
		$view->description='Информационная система дистанционного тестирования знаний, для студентов, пользователей, школьников и сотрудников предприятий. Вы можете легко создать или пройти тест...';
		
		//получение информации о тестах из БД, указание диапазона выборки тестов
		$view->usertests=Execute::getUserTests(1, 10000);
		
		//проверка наличия тестов в базе данных
		if(is_array($view->usertests))
		{
			$view->content='Для того чтобы пройти тест, кликните по заголовку теста:';
			$view->title='Пользовательские тесты';
			$result=$view->render('../views/execute.php');
		}
		
		else
		{
			$view->content.='<b>В базе данных еще нет ни одного теста!</b>';
			$view->title='Нет пользовательских тестов в БД';
			$result=$view->render('../views/default.php');
		}
		
		$fc->setBody($result);
	}
	
	//метод вызываемый при выборе пользовательского теста для прохождения
	function usertestAction()
	{
		//создание экземпляра модели "вида"
		$fc=FrontController::get();
		$view=new View();
		//получение параметров из строки запроса
		$url=$fc->getParams();
		//вывод названия исполняемого теста
		$testname=Execute::getTestData($url['id'],'user');
		//если пользователь уже проходил тест
		if(isset($_SESSION['exectest']['user'.$url['id']]))
		{
			$view->title=$testname['name'];
			$view->content='<center><br><b>Вы уже прохожили данный тест!</b><br><h4><a href="/execute/">Пройти другие теста > ></a></h4></center>';
			$result=$view->render('../views/default.php');
		}
		else
		{
		//вывод теста из базы по передаваемому параметру - идентификатору теста
		$test=Execute::getTestById($url['id'], 'user');
			//если при выборке тестов возникла ошибка
			if($test==false or $testname==false)
			{
				$view->title='Ошибка при выборке теста!';
				$view->content='<center><h3>Тест не найден!</h3></center><br><b><a href="/execute">< < Назад к списку тестов</a></b>';
				$result=$view->render('../views/default.php');
			}
			else
			{
				$view->timer=$testname['time_min'];
				$view->title=$testname['name'];
				$view->content=$test;
				$view->id=$url['id'];
				$result=$view->render('../views/testing.php');
			}
		}
		$fc->setBody($result);
	}
	
	//метод возвращает результат прохождения теста в представление
	function resultAction()
	{
		//создание экземпляра модели "вида"
		$fc=FrontController::get();
		$view=new View();
		
		//если существуют необходимые переменные, то выводим результат, иначе вывод ошибки 
		if(!empty($_SESSION['exec']['type']) and !empty($_SESSION['exec']['id']) and !empty($_SESSION['exec']['count']) and isset($_POST['submit']) and isset($_POST['q1']) and isset($_SESSION['exec']['q1']))
		{
			//формируем массив с ответами на вопросы
			for($i=1;$i<=$_SESSION['exec']['count'];$i++)
			{
				$results[$_SESSION['exec']['q'.$i]]=$_POST['q'.$i];
			}
			
		//вызываемый метод класса возвращает результат проверки прохождения теста
		$res=Execute::getResult($_SESSION['exec'], $results);
		
		if(!is_numeric($res))
			$res='Возникли ошибки при проверке прохождения теста!';
		
		$view->title='Ваш результат';
		$view->r_answers=$_SESSION['exec']['count']*($res/100);
		$view->res=$res;
		$view->test_id=$_SESSION['exec']['id'];
		$view->count=$_SESSION['exec']['count'];
		$view->time=$_POST['time'];
		$result=$view->render('../views/testing.php');
		
		}
		else
		{
		$view->title='Ошибка';
		$view->content='Необходимые для проверки результата данные не найдены! Вы должны указать ответы на все вопросы!<br><h4><a href="/execute">Пройти тесты > ></a></h4>';
		$result=$view->render('../views/default.php');
		}

		$view->keywords='Дистанционное тестирование, тестирование школьников, тестирование студентов, тестирование сотрудников, онлайн, тестирование, пройти тестирование, список всех тестов';
		$view->description='Информационная система дистанционного тестирования знаний, для студентов, пользователей, школьников и сотрудников предприятий. Вы можете легко создать или пройти тест...';
		$fc->setBody($result);
	}
	
	//метод вызываемый при выборе организационного теста для прохождения
	function orgtestAction()
	{
		//создание экземпляра модели "вида"
		$fc=FrontController::get();
		$view=new View();
		//получение параметров из строки запроса
		$url=$fc->getParams();
		//вывод названия исполняемого теста
		$testname=Execute::getTestData($url['id'],'org');
		//если пользователь уже проходил тест
		if(isset($_SESSION['exectest']['org'.$url['id']]))
		{
			$view->title=$testname['name'];
			$view->content='<center><br><b>Вы уже прохожили данный тест!</b><br><h4><a href="/execute/">Пройти другие теста > ></a></h4></center>';
			$result=$view->render('../views/default.php');
		}
		else
		{
		//вывод теста из базы по передаваемому параметру - идентификатору теста
		$test=Execute::getTestById($url['id'],'org');
		//если при выборке тестов возникла ошибка
			if($test==false or $testname==false)
			{
				$view->title='Ошибка при выборке теста!';
				$view->content='<center><h3>Тест не найден!</h3></center><br><b><a href="/execute">< < Назад к списку тестов</a></b>';
				$result=$view->render('../views/default.php');
			}
			else
			{
		
		$view->timer=$testname['time_min'];
		$view->title=$testname['name'];
		$view->org_id=$testname['org_id'];
		$view->orgtest=$test;
		$view->id=$url['id'];
		$view->keywords='Дистанционное тестирование, тестирование школьников, тестирование студентов, тестирование сотрудников, онлайн, тестирование, пройти тестирование, список всех тестов';
		$view->description='Информационная система дистанционного тестирования знаний, для студентов, пользователей, школьников и сотрудников предприятий. Вы можете легко создать или пройти тест...';
		$result=$view->render('../views/testing.php');
			}
		}
		$fc->setBody($result);
	}
	
	//метод возвращает результат прохождения теста в представление
	function orgresultAction()
	{
		$fc=FrontController::get();
		$view=new View();
		
		//если существуют необходимые переменные, то выводим результат, иначе вывод ошибки 
		if(!empty($_SESSION['exec']['type']) and !empty($_SESSION['exec']['id']) and !empty($_SESSION['exec']['count']) and isset($_POST['submit']) and isset($_POST['q1']) and isset($_SESSION['exec']['q1']) and !empty($_POST['org_id']))
		{
			//формируем массив с ответами на вопросы
			for($i=1;$i<=$_SESSION['exec']['count'];$i++)
			{
				$results[$_SESSION['exec']['q'.$i]]=$_POST['q'.$i];
			}
			
		//вызываемый статический метод класса возвращает результат проверки прохождения теста
		$result=Execute::getResult($_SESSION['exec'], $results, $_POST['org_id']);
		//если тест прошел пользователь с типом регистрации сотрудник
		if($_SESSION['loginfo']['type_id']==5)
		{
		//проверка зарегистрированного сотрудника на наличие в списках тестируемых и запись данных в БД, возвращается строка 
		$view->check=Execute::setOrgResult($_SESSION['results'], $_SESSION['loginfo']['fio'], $_SESSION['loginfo']['address'], $_SESSION['loginfo']['tel']);
		unset($_SESSION['results']);
		}
		
		if(!is_numeric($result))
			$result='Возникли ошибки при проверке прохождения теста!';
		
		$view->title='Результат';
		$r_answers=$_SESSION['exec']['count']*($result/100);
		$view->count=$_SESSION['exec']['count'];
		$view->answers=$r_answers;
		$view->result=$result;
		$view->time=$_POST['time'];
		$view->org_id=$_POST['org_id'];
		$view->test_id=$_POST['test_id'];
		$result=$view->render('../views/testing.php');
		}
		else
		{
		$view->title='Ошибка';
		$view->content='Необходимые для проверки результата данные не найдены! Вы должны указать ответы на все вопросы!<br><h4><a href="/execute">Пройти тесты > ></a></h4>';
		$result=$view->render('../views/default.php');
		}

		$fc->setBody($result);
	}
	
	//метод вызывает класс для добавления результата тестирования сотрудника в базу данных
	function addorgresultAction()
	{
		//создание экземпляра модели "вида"
		$fc=FrontController::get();
		$view=new View();
		$view->title='Добавление результата в БД';
		
		//если пришли необходиме данные проверяем наличие соответствующего сотрудника в базе и записываем результат теста
		if(is_array($_SESSION['results']) and !empty($_POST['fio']) and !empty($_POST['address']) and !empty($_POST['tel']))
		{
			$view->content=Execute::setOrgResult($_SESSION['results'], $_POST['fio'], $_POST['address'], $_POST['tel']);
			unset($_SESSION['results']);
		}
		else 
			$view->content='Вернитесь назад и заполните все поля коректно и без пустых значений!';
		
		$view->content.='<h4><a href="/execute">Вы можете пройти другие тесты > ></a></h4>';
		$result=$view->render('../views/default.php');
		$fc->setBody($result);
	}
	
	//метод для прохождения университетских тестов
	function univertestAction()
	{
		//создание экземпляра модели "вида"
		$fc=FrontController::get();
		$view=new View();
		//получение параметров из строки запроса
		$url=$fc->getParams();
		//вывод названия исполняемого теста
		$testname=Execute::getTestData($url['id'],'univer');
		
		//если пользователь уже проходил тест
		if(isset($_SESSION['exectest']['univer'.$url['id']]))
		{
			$view->title=$testname['name'];
			$view->content='<center><br><b>Вы уже прохожили данный тест!</b><br><h4><a href="/execute/">Пройти другие теста > ></a></h4></center>';
			$result=$view->render('../views/default.php');
		}
		else
		{
		//вывод теста из базы по передаваемому параметру - идентификатору теста
		$test=Execute::getTestById($url['id'],'univer');
		
		//если при выборке тестов возникла ошибка
			if($test==false or $testname==false)
			{
				$view->title='Ошибка при выборке теста!';
				$view->content='<center><h3>Тест не найден!</h3></center><br><b><a href="/execute">< < Назад к списку тестов</a></b>';
				$result=$view->render('../views/default.php');
			}
			else
			{
		$view->timer=$testname['time_min'];
		$view->title=$testname['name'];
		$view->univer_id=$testname['university_id'];
		$view->univertest=$test;
		$view->id=$url['id'];
		$result=$view->render('../views/testing.php');
			}
		}
		$fc->setBody($result);
	}
	
	//метод возвращает рузультат прохождения теста!!!!!!!!!!!!!!!!!!!!
	function univerresultAction()
	{
		//создание экземпляра модели "вида"
		$fc=FrontController::get();
		$view=new View();
		
		//если существуют необходимые переменные, то выводим результат, иначе вывод ошибки 
		if(!empty($_SESSION['exec']['type']) and !empty($_SESSION['exec']['id']) and !empty($_SESSION['exec']['count']) and isset($_POST['submit']) and isset($_POST['q1']) and isset($_SESSION['exec']['q1']) and !empty($_POST['univer_id']))
		{
			//формируем массив с ответами на вопросы
			for($i=1;$i<=$_SESSION['exec']['count'];$i++)
			{
				$results[$_SESSION['exec']['q'.$i]]=$_POST['q'.$i];
			}
			
		//вызываемый статический метод класса возвращает результат проверки прохождения теста
		$result=Execute::getResult($_SESSION['exec'], $results, $_POST['univer_id']);
		//если тест прошел пользователь с типом регистрации студент
		if($_SESSION['loginfo']['type_id']==2)
		{
		//проверка зарегистрированного сотрудника на наличие в списках тестируемых и запись данных в БД, возвращается строка 
		$view->check=Execute::setUniverResult($_SESSION['results'], $_SESSION['loginfo']['fio'], $_SESSION['loginfo']['address'], $_SESSION['loginfo']['tel']);
		unset($_SESSION['results']);
		}
		
		if(!is_numeric($result))
			$result='Возникли ошибки при проверке прохождения теста!';
		
		$view->title='Результат';
		$r_answers=$_SESSION['exec']['count']*($result/100);
		$view->count=$_SESSION['exec']['count'];
		$view->answers=$r_answers;
		$view->result=$result;
		$view->time=$_POST['time'];
		$view->univer_id=$_POST['univer_id'];
		$view->test_id=$_POST['test_id'];
		$result=$view->render('../views/testing.php');
		}
		else
		{
		$view->title='Ошибка';
		$view->content='Необходимые для проверки результата данные не найдены! Вы должны указать ответы на все вопросы!<br><h4><a href="/execute">Пройти тесты > ></a></h4>';
		$result=$view->render('../views/default.php');
		}

		$fc->setBody($result);
	}
	
	//добавляем результат прохождения теста студентом в базу данных
	function adduniverresultAction()
	{
		//создание экземпляра модели "вида"
		$fc=FrontController::get();
		$view=new View();
		$view->title='Добавление результата в БД';
		
		//если пришли необходиме данные проверяем наличие соответствующего студента в базе и записываем результат теста
		if(is_array($_SESSION['results']) and !empty($_POST['fio']) and !empty($_POST['address']) and !empty($_POST['tel']))
		{
			$view->content=Execute::setUniverResult($_SESSION['results'], $_POST['fio'], $_POST['address'], $_POST['tel']);
			unset($_SESSION['results']);
		}
		else 
			$view->content='Вернитесь назад и заполните все поля коректно и без пустых значений!';
		
		$view->content.='<h4><a href="/execute">Вы можете пройти другие тесты > ></a></h4>';
		$result=$view->render('../views/default.php');
		$fc->setBody($result);
		
	}
	
	//выводим вопросы школьного теста
	function schooltestAction()
	{
		//создание экземпляра модели "вида"
		$fc=FrontController::get();
		$view=new View();
		$url=$fc->getParams();
		//вывод названия исполняемого теста
		$testname=Execute::getTestData($url['id'],'school');
		
		//если пользователь уже проходил тест
		if(isset($_SESSION['exectest']['school'.$url['id']]))
		{
			$view->title=$testname['name'];
			$view->content='<center><br><b>Вы уже прохожили данный тест!</b><br><h4><a href="/execute/">Пройти другие теста > ></a></h4></center>';
			$result=$view->render('../views/default.php');
		}
		else
		{
		//вывод теста из базы по передаваемому параметру - идентификатору теста
		$view->schooltest=Execute::getTestById($url['id'],'school');
		
		//если при выборке тестов возникла ошибка
			if($view->schooltest==false or $testname==false)
			{
				$view->title='Ошибка при выборке теста!';
				$view->content='<center><h3>Тест не найден!</h3></center><br><b><a href="/execute">< < Назад к списку тестов</a></b>';
				$result=$view->render('../views/default.php');
			}
			else
			{
		
		$view->timer=$testname['time_min'];
		$view->title=$testname['name'];
		$view->school_id=$testname['school_id'];
		$view->class_id=$testname['class_id'];
		$view->lesson=$testname['lesson_id'];
		$view->id=$url['id'];
		$result=$view->render('../views/testing.php');
			}
		}
		$fc->setBody($result);
		
	}
	
	//выводим результат прохождения школьного теста
	function schoolresultAction()
	{
		//создание экземпляра модели "вида"
		$fc=FrontController::get();
		$view=new View();
		
		//если существуют необходимые переменные, то выводим результат, иначе вывод ошибки 
		if(!empty($_SESSION['exec']['type']) and !empty($_SESSION['exec']['id']) and !empty($_SESSION['exec']['count']) and isset($_POST['submit']) and isset($_POST['q1']) and isset($_SESSION['exec']['q1']) and !empty($_POST['school_id']) and !empty($_POST['class_id']) and !empty($_POST['lesson']))
		{
			$_SESSION['results']['school_id']=$_POST['school_id'];
			$_SESSION['results']['class_id']=$_POST['class_id'];
			$_SESSION['results']['lesson']=$_POST['lesson'];
			//формируем массив с ответами на вопросы
			for($i=1;$i<=$_SESSION['exec']['count'];$i++)
			{
				$results[$_SESSION['exec']['q'.$i]]=$_POST['q'.$i];
			}
			
		//вызываемый статический метод класса возвращает результат проверки прохождения теста
		$result=Execute::getResult($_SESSION['exec'], $results, $_POST['school_id']);
		//если тест прошел пользователь с типом регистрации школьник
		if($_SESSION['loginfo']['type_id']==3)
		{
		//проверка зарегистрированного школьника на наличие в списках тестируемых и запись данных в БД, возвращается строка 
		$view->check=Execute::setSchoolResult($_SESSION['results'], $_SESSION['loginfo']['fio'], $_SESSION['loginfo']['address'], $_SESSION['loginfo']['tel']);
		unset($_SESSION['results']);
		}
		
		if(!is_numeric($result))
			$result='Возникли ошибки при проверке прохождения теста!';
		
		$view->title='Результат';
		$r_answers=$_SESSION['exec']['count']*($result/100);
		$view->count=$_SESSION['exec']['count'];
		$view->answers=$r_answers;
		$view->result=$result;
		$view->time=$_POST['time'];
		$view->school_id=$_POST['school_id'];
		$view->class_id=$_POST['class_id'];
		$view->lesson=$_POST['lesson'];
		$view->test_id=$_POST['test_id'];
		$result=$view->render('../views/testing.php');
		}
		else
		{
		$view->title='Ошибка';
		$view->content='Необходимые для проверки результата данные не найдены! Вы должны указать ответы на все вопросы!<br><h4><a href="/execute">Пройти тесты > ></a></h4>';
		$result=$view->render('../views/default.php');
		}

		$fc->setBody($result);	
	}
	
	//голосование за тест!!!!!!!!!!!!!!!!!!!!
	function voteAction()
	{
		$fc=FrontController::get();
		$view=new View();
		$view->title='Голосование за тест...';
		
		if(!empty($_POST['mark']) and !empty($_POST['test_type']) and !empty($_POST['test_id']))
			{
				if($_POST['test_type']=='user')
				{	
					if(isset($_SESSION['votes']['utest'.$_POST['test_id']]))
					{
						$view->content='Вы уже голосовали за данный тест!';
					}
					else
					{
						$view->content=Execute::setTestVote($_POST['test_id'], $_POST['test_type'], $_POST['mark']);
						($view->content)? $view->content='Ваш голос учтён! Спасибо!': $view->content='Ошибка при голосовании!';
					}
				}
				elseif($_POST['test_type']=='org')
				{
					if(isset($_SESSION['votes']['otest'.$_POST['test_id']]))
					{
						$view->content='Вы уже голосовали за данный тест!';
					}
					else
					{
						$view->content=Execute::setTestVote($_POST['test_id'], $_POST['test_type'], $_POST['mark']);
						($view->content)? $view->content='Ваш голос учтён! Спасибо!': $view->content='Ошибка при голосовании!';
					}
				}
				elseif($_POST['test_type']=='univer')
				{
					if(isset($_SESSION['votes']['untest'.$_POST['test_id']]))
					{
						$view->content='Вы уже голосовали за данный тест!';
					}
					else
					{
						$view->content=Execute::setTestVote($_POST['test_id'], $_POST['test_type'], $_POST['mark']);
						($view->content)? $view->content='Ваш голос учтён! Спасибо!': $view->content='Ошибка при голосовании!';
					}
				}
				elseif($_POST['test_type']=='school')
				{
					if(isset($_SESSION['votes']['stest'.$_POST['test_id']]))
					{
						$view->content='Вы уже голосовали за данный тест!';
					}
					else
					{
						$view->content=Execute::setTestVote($_POST['test_id'], $_POST['test_type'], $_POST['mark']);
						($view->content)? $view->content='Ваш голос учтён! Спасибо!': $view->content='Ошибка при голосовании!';
					}
				}
				
				$view->content.='<br><br><h4><a href="/execute">Вы можете пройти другие тесты > ></a></h4>';
			}
		else	
			$view->content='Необходимые для голосования данные не найдены!';
		
		$result=$view->render('../views/default.php');
		$fc->setBody($result);
	}
	
	//добавляем результат прохождения теста школьником в базу данных
	function addschoolresultAction()
	{
		//создание экземпляра модели "вида"
		$fc=FrontController::get();
		$view=new View();
		$view->title='Добавление результата в БД';
		
		//если пришли необходиме данные проверяем наличие соответствующего студента в базе и записываем результат теста
		if(is_array($_SESSION['results']) and !empty($_POST['fio']) and !empty($_POST['address']) and !empty($_POST['tel']))
		{
			$view->content=Execute::setSchoolResult($_SESSION['results'], $_POST['fio'], $_POST['address'], $_POST['tel']);
			unset($_SESSION['results']);
		}
		else 
			$view->content='Вернитесь назад и заполните все поля коректно и без пустых значений!';
		
		$view->content.='<h4><a href="/execute">Вы можете пройти другие тесты > ></a></h4>';
		$result=$view->render('../views/default.php');
		$fc->setBody($result);
		
	}
	
}

?>