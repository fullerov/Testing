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

//функция посылает запрос на сервер, отправляет номер города и ожидает возвращения строки со списком школ
function getSchool() 
{ 

  if (xmlHttp) 
  { 
    // Попытка отправки запроса серверу 
    try 
    {
		var city = document.getElementById("scity").value;
		xmlHttp.open("GET", "http://"+document.domain+"/server/views/additems.php?scity="+city, true); 
  	    xmlHttp.onreadystatechange = getschools; 
   		xmlHttp.send(null); 
    } 
    // Сообщение об ошибке в случае неудачи 
    catch (e) 
    {} 
  } 
} 
// Функция обработки ответа сервера 
function getschools() 
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
		var school=document.getElementById('school');
		var label=document.getElementById('label_school');
		label.style.visibility='visible';
		school.style.visibility='visible';
		school.innerHTML='<select id="school" onchange="getClass()" name="school"><option value="0">Выберите школу \\/ </option>'+response+'</select>';
		
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


//функция посылает запрос на сервер, отправляет номер города и ожидает возвращения строки со списком классов школы
function getClass() 
{ 

  if (xmlHttp) 
  { 
    // Попытка отправки запроса серверу 
    try 
    {
		var school = document.getElementById("school").value;
		xmlHttp.open("GET", "http://"+document.domain+"/server/views/additems.php?school="+school, true); 
  	    xmlHttp.onreadystatechange = getclasses; 
   		xmlHttp.send(null); 
    } 
    // Сообщение об ошибке в случае неудачи 
    catch (e) 
    {} 
  } 
} 
// Функция обработки ответа сервера 
function getclasses() 
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
		var clas=document.getElementById('class');
		var label=document.getElementById('label_class');
		label.style.visibility='visible';
		clas.style.visibility='visible';
		clas.innerHTML='<select id="class" name="class"><option value="0">Выберите класс \\/ </option>'+response+'</select>';

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

//функция посылает запрос на сервер, отправляет номер города и ожидает возвращения строки со списком предметов класса
function getLessonsList() 
{ 

  if (xmlHttp) 
  { 
    // Попытка отправки запроса серверу 
    try 
    {
		var clas = document.getElementById("class").value;
		xmlHttp.open("GET", "http://"+document.domain+"/server/views/additems.php?class="+clas, true); 
  	    xmlHttp.onreadystatechange = getthislessons; 
   		xmlHttp.send(null); 
    } 
    // Сообщение об ошибке в случае неудачи 
    catch (e) 
    {} 
  } 
} 
// Функция обработки ответа сервера 
function getthislessons() 
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
		var lesson=document.getElementById('lesson');
		var label=document.getElementById('label_lesson');
		label.style.visibility='visible';
		lesson.style.visibility='visible';
		lesson.innerHTML='<select id="lesson" onchange="showButton()" name="lesson"><option value="0">Выберите урок \\/ </option>'+response+'</select>';

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

//функция отображает кнопку добавления ученика после выбора класса
function getButton()
{
	document.getElementById('addbutton').style.visibility='visible';
}

//функция отображает кнопку добавления ученика после выбора класса
function showButton()
{
	$("#create_test_button_appear2 input").css("visibility","visible");
}