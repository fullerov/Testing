<? 
//контроллер вызываемый по запросу /search
class SearchController implements IController
{
	function indexAction()
	{
		$fc=FrontController::get();
		//создание экземпляра модели "вида"
		$view=new View();
		$view->searchforms=true;
		$view->usertests=Search::get()->getUserThemes();
		$view->orgtests=Search::get()->getOrgThemes();
		$view->keywords='Дистанционное тестирование, тестирование школьников, тестирование студентов, тестирование сотрудников, онлайн, тестирование, поиск, поиск тестов, поиск статей';
		$view->description='Поиск статей и тестов для пользователей, университетов, организаций и школ';
		$view->title='Поиск';
		$result=$view->render('../views/search.php');
		$fc->setBody($result);
		
	}
	
	//поиск по названию теста и описанию
	function bynameAction()
	{
		$fc=FrontController::get();
		$view=new View();
		$view->title='Результат поиска всем тестам';
		//если пользователь нажал кнопку "Поиск"
		if(isset($_POST['search']))
		{
			if(strlen($_POST['searchtest'])>2)
			{
				$view->nameabout=Search::get()->searchTestsByNames(trim($_POST['searchtest']));
				(is_array($view->nameabout))? $view->nameabout: $view->nameabout='Ошибка при выборке данных!';
			}
			else
				$view->nameabout='<b>Строка запроса должна содержать минимум три символа!</b>';
			$view->key=trim($_POST['searchtest']);
		}
		else header('Location: /search');
		
		$result=$view->render('../views/search.php');
		$fc->setBody($result);
	}
	
	//поиск теста по дате
	function bydateAction()
	{
		$fc=FrontController::get();
		$view=new View();
		$view->title='Результат поиска по дате';
		//если пользователь нажал кнопку "Поиск"
		if(isset($_POST['searchdate']))
		{
			if(strlen($_POST['date'])>2)
			{
				$view->testsdate=Search::get()->searchTestsByDate(trim($_POST['date']));
				(is_array($view->testsdate))? $view->testsdate: $view->testsdate='Ошибка при выборке данных!';
			}
			else
				$view->testsdate='<b>Строка запроса должна содержать минимум три символа!</b>';
			$view->key=trim($_POST['date']);
		}
		else header('Location: /search');
		
		$result=$view->render('../views/search.php');
		$fc->setBody($result);
	}
	
	//поиск статей по названию и описанию
	function articlesAction()
	{
		$fc=FrontController::get();
		$view=new View();
		$view->title='Результат поиска статей';
		//если пользователь нажал кнопку "Поиск"
		if(isset($_POST['searcharticles']))
		{
			if(strlen($_POST['articles'])>2)
			{
				$view->articles=Search::get()->searchArticles(trim($_POST['articles']));
				(is_array($view->articles))? $view->articles: $view->articles='Ошибка при выборке данных!';
			}
			else
				$view->articles='<b>Строка запроса должна содержать минимум три символа!</b>';
			$view->key=trim($_POST['articles']);
		}
		else header('Location: /search');
		
		$result=$view->render('../views/search.php');
		$fc->setBody($result);
	}
	
	//поиск пользовательского теста по теме
	function usertestAction()
	{
		$fc=FrontController::get();
		$url=$fc->getParams();
		$view=new View();
		
		//возваращаем массив с пользовательскими тестами по данной категории
		$view->usertests=Search::get()->getUserTests((int)$url['id']);
		$view->title='Все тесты данной тематики';
		
		$result=$view->render('../views/search.php');
		$fc->setBody($result);
		
	}
	
	//поиск организационного теста по теме
	function orgtestAction()
	{
		$fc=FrontController::get();
		$url=$fc->getParams();
		$view=new View();
		
		//возваращаем массив с пользовательскими тестами по данной категории
		$view->orgtests=Search::get()->getOrgTests((int)$url['id']);
		$view->title='Все тесты данной тематики';
		
		$result=$view->render('../views/search.php');
		$fc->setBody($result);
		
	}
}




?>