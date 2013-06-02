<? 
session_start();
try{
//пути до файлов с классами	
set_include_path(get_include_path()
.PATH_SEPARATOR.'server/controllers'
.PATH_SEPARATOR.'server/models'
.PATH_SEPARATOR.'server/views');
//автозагрузка объявленых классов
function __autoload($name)
{require_once $name.'.php';}
//вызов класса-обработчика запросов
$front=FrontController::get();
$front->route();
echo $front->getBody();
}

//при возникновении ошибки
catch(Exception $e)
{
		try{
		$fc=FrontController::get();
		$view=new View();
		$view->content='<h3>Вы ввели некорректную ссылку! Здесь данных нет!</h3> <b><a href="/">< < Назад на главную страницу</a></b></b>';
		$view->title='Ошибка пользователя!';
		$result=$view->render('../views/default.php');
		$fc->setBody($result);
		}
		catch(Exception $e)
		{
			header('Location: ../');
		}
}

?>




 

