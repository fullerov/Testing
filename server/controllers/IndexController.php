<?
//контраллер вызываемый изначально
class IndexController implements IController 
{
	function indexAction()
	{
		$fc=FrontController::get();
		
		//создание экземпляра модели "вида"
		$view=new View();
		$view->content='Добро пожаловать в информационную систему дистанционного тестирования знаний!';
		$view->stat['results']=Search::get()->getResultStat();
		$view->stat['count']=Search::get()->getCountStat();
		$view->keywords='Дистанционное тестирование, тестирование школьников, тестирование студентов, тестирование сотрудников, онлайн, тестирование, пройти тестирование, создать тест, статистика';
		$view->description='Информационная система дистанционного тестирования знаний, для студентов, пользователей, школьников и сотрудников предприятий. Вы можете легко создать или пройти тест';
		$view->title='Дистанционное тестирование знаний: Статистика';
		$result=$view->render('../views/statistic.php');
		$fc->setBody($result);
		
	}

}

?>