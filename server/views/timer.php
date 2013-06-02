<?
// Передаем заголовки, возвращаем вермя
header('Content-type: text/plain; charset=utf-8');
header('Cache-Control: no-store, no-cache');
header('Expires: ' . date('r'));

if(isset($_GET['time']))
{
	$min=$_GET['time'];
	--$min;
	echo $min;
}



?>