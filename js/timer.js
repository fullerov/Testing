// создание объекта xmlHttp
var minutes=0;
function createXmlHttpRequestObject() 
{ 
  var xmlHttp; 
  try 
  { 
    // Firefox, Opera, Chrome
    xmlHttp=new XMLHttpRequest(); 
  } 
  catch (e) 
  { 
    // Internet Explorer 
    try 
    { 
      xmlHttp=new ActiveXObject("Msxml2.XMLHTTP"); 
    } 
    catch (e) 
    { 
      try 
      { 
        xmlHttp=new ActiveXObject("Microsoft.XMLHTTP"); 
      } 
      catch (e) 
      { 
        alert("Ваш браузер не поддерживает AJAX!"); 
        return false; 
      } 
    } 
  } 
  return xmlHttp; 
} 

var xmlHttp=createXmlHttpRequestObject();

function getTimer() 
{ 

  if (xmlHttp) 
  { 
    // Попытка отправки запроса серверу 
    try 
    {
	 
	 var time = document.getElementById("timer").innerHTML;
	 xmlHttp.open("GET", "http://"+document.domain+"/server/views/timer.php?time="+time, true); 
     xmlHttp.onreadystatechange = tick; 
     xmlHttp.send(null); 
    } 
    // Сообщение об ошибке в случае неудачи 
    catch (e) 
    { 
      
    } 
  } 
} 
// Функция обработки ответа сервера 
function tick() 
{ 
  // Только в этом состоянии ответа обрабатываем пришедшие данные 
  if (xmlHttp.readyState == 4) 
  { 
    // Данные читаем, только если статус - "OK" 
    if (xmlHttp.status == 200) 
    { 
      try 
      { 
        // Чтение сообщения сервера 
        response = xmlHttp.responseText; 
        // Ищем место на странице, где будем писать ответ сервера 
        myDiv = document.getElementById("timer"); 
        // Отображение сообщения 
		var val=document.getElementById("user_time");
		if(response==0)
		{
		$("#message").hide();
		++minutes;
		val.value=minutes;
		alert("Время отведенное на выполнение теста истекло, нажмите на кнопку 'ОК'");
		document.title="Время истекло";	
		document.getElementById("submit").click();
		}
		else
		{
	   	 myDiv.innerHTML=response; 
        ++minutes;
		val.value=minutes;
		
		}
      } 
      catch(e) 
      { 
      } 
    } 
    else 
    {  // Вывод сообщения о статусе ответа 
      alert("Возникла проблема при получении данных с сервера:\n" + 
             xmlHttp.statusText); 
    } 
  }
}