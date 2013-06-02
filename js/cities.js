// создание объекта xmlHttp

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

function getCities() 
{ 

  if (xmlHttp) 
  { 
    // Попытка отправки запроса серверу 
    try 
    {
	 
	 var country = document.getElementById("country").value;
		xmlHttp.open("GET", "http://"+document.domain+"/server/views/additems.php?country="+country, true); 
  	    xmlHttp.onreadystatechange = getcities; 
   		xmlHttp.send(null); 
    } 
    // Сообщение об ошибке в случае неудачи 
    catch (e) 
    {} 
  } 
} 
// Функция обработки ответа сервера 
function getcities() 
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
		var city=document.getElementById('city');
		var label=document.getElementById('label_city');
		city.style.marginTop='0';
		label.style.marginTop='0';
		label.style.visibility='visible';
		city.style.visibility='visible';
		city.innerHTML='<select id="city" name="city">'+response+'</select>';
		
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

