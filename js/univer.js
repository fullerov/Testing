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

//функция посылает запрос на сервер, отправляет номер города и ожидает возвращения строки со списком университетов
function getUniver() 
{ 

  if (xmlHttp) 
  { 
    // Попытка отправки запроса серверу 
    try 
    {
		var city = document.getElementById("ucity").value;
		xmlHttp.open("GET", "http://"+document.domain+"/server/views/additems.php?ucity="+city, true); 
  	    xmlHttp.onreadystatechange = getuniversities; 
   		xmlHttp.send(null); 
    } 
    // Сообщение об ошибке в случае неудачи 
    catch (e) 
    {} 
  } 
} 
// Функция обработки ответа сервера 
function getuniversities() 
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
		var univer=document.getElementById('univer');
		var label=document.getElementById('label_univer');
		label.style.visibility='visible';
		univer.style.visibility='visible';
		univer.innerHTML='<select id="univer" onchange="getFaculty()" name="univer">'+response+'</select>';
		
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


//функция посылает запрос на сервер, отправляет номер университета, ожидает возвращения строки со списком факультетов
function getFaculty()
{ 

  if (xmlHttp) 
  { 
    // Попытка отправки запроса серверу 
    try 
    {
		var univer = document.getElementById("univer").value;
			
		xmlHttp.open("GET", "http://"+document.domain+"/server/views/additems.php?univer="+univer, true); 
  	    xmlHttp.onreadystatechange = getfacultites; 
   		xmlHttp.send(null); 
    } 
    // Сообщение об ошибке в случае неудачи 
    catch (e) 
    {} 
  } 
} 
// Функция обработки ответа сервера 
function getfacultites() 
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
		var faculty=document.getElementById('faculty');
		var label=document.getElementById('label_faculty');
		label.style.visibility='visible';
		faculty.style.visibility='visible';
		faculty.innerHTML='<select id="faculty" onchange="getCourse()" name="faculty">'+response+'</select>';
		
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


//функция посылает запрос на сервер, отправляет номер факультета, ожидает возвращения строки со списком курсов
function getCourse()
{ 

  if (xmlHttp) 
  { 
    // Попытка отправки запроса серверу 
    try 
    {
		var faculty = document.getElementById("faculty").value;
		xmlHttp.open("GET", "http://"+document.domain+"/server/views/additems.php?faculty="+faculty, true); 
  	    xmlHttp.onreadystatechange = getcourses; 
   		xmlHttp.send(null); 
    } 
    // Сообщение об ошибке в случае неудачи 
    catch (e) 
    {} 
  } 
} 
// Функция обработки ответа сервера 
function getcourses() 
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
		var course=document.getElementById('course');
		var label=document.getElementById('label_course');
		label.style.visibility='visible';
		course.style.visibility='visible';
		course.innerHTML='<select name="course">'+response;
		document.getElementById('specialty').style.visibility='visible';
		document.getElementById('label_specialty').style.visibility='visible';
		
		
		
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

//функция посылает запрос на сервер, отправляет номер специальности и курса, ожидает возвращения строки со списком групп
function getGroup()
{ 

  if (xmlHttp) 
  { 
    // Попытка отправки запроса серверу 
    try 
    {
		var spec = document.getElementById("specialty").value;
		var course=document.getElementById("course").value;
		xmlHttp.open("GET", "http://"+document.domain+"/server/views/additems.php?specialty="+spec+"&course="+course, true); 
  	    xmlHttp.onreadystatechange = getgroups; 
   		xmlHttp.send(null); 
    } 
    // Сообщение об ошибке в случае неудачи 
    catch (e) 
    {} 
  } 
} 
// Функция обработки ответа сервера 
function getgroups() 
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
		var group=document.getElementById('group');
		var label=document.getElementById('label_group');
		label.style.visibility='visible';
		group.style.visibility='visible';
		group.innerHTML='<select id="group" name="group">'+response+'</select>';
		
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


//функция посылает запрос на сервер, отправляет номер группы, ожидает возвращения строки со списком предметов
function getLessons()
{ 

  if (xmlHttp) 
  { 
    // Попытка отправки запроса серверу 
    try 
    {
		
		var group=document.getElementById("group").value;
	    xmlHttp.open("GET", "http://"+document.domain+"/server/views/additems.php?group="+group, true); 
  	    xmlHttp.onreadystatechange = getallessons; 
   	    xmlHttp.send(null); 
    } 
    // Сообщение об ошибке в случае неудачи 
    catch (e) 
    {} 
  } 
} 
// Функция обработки ответа сервера 
function getallessons() 
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
		var lessons=document.getElementById('lessons');
		var label=document.getElementById('label_lessons');
		label.style.visibility='visible';
		lessons.style.visibility='visible';
		lessons.innerHTML='<select id="lessons" onchange="showUniverTestButton()" name="lessons">'+response+'</select>';
		
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


//функция посылает запрос на сервер, отправляет идентификатор факультета, ожидает возвращения строки со списком специальностей и курсов
function getSpecCour()
{ 

  if (xmlHttp) 
  { 
    // Попытка отправки запроса серверу 
    try 
    {
		var fac = document.getElementsByName("fac_id").item(0).value;
		xmlHttp.open("GET", "http://"+document.domain+"/server/views/additems.php?fac="+fac, true); 
  	    xmlHttp.onreadystatechange = getspeccourses; 
   		xmlHttp.send(null); 
    } 
    // Сообщение об ошибке в случае неудачи 
    catch (e) 
    {} 
  } 
} 
// Функция обработки ответа сервера 
function getspeccourses() 
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
		$("#year").after('<br>'+response+'<br><input type="submit" name="addgroup" value="Готово">');
		
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

//отображает кнопку создания студенческого теста
function showUniverTestButton()
{
	$("#create_test_button_appear input").css("visibility","visible");
}

//функция посылает запрос на сервер, отправляет номер школы, ожидает возвращения строки со списком классов
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
		var classes=document.getElementById('class');
		var label=document.getElementById('label_class');
		label.style.visibility='visible';
		classes.style.visibility='visible';
		classes.innerHTML='<select id="class" onchange="getPredmet()" name="class"><option selected="selected" value="0">Выберите класс для изменения теста \/</option>'+response+'</select>';
		
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

//функция посылает запрос на сервер, отправляет номер класса, ожидает возвращения строки со списком предметов
function getPredmet()
{ 
  if (xmlHttp) 
  { 
    // Попытка отправки запроса серверу 
    try 
    {
		var class_id = document.getElementById("class").value;
			
		xmlHttp.open("GET", "http://"+document.domain+"/server/views/additems.php?class="+class_id, true); 
  	    xmlHttp.onreadystatechange = getpredmets; 
   		xmlHttp.send(null); 
    } 
    // Сообщение об ошибке в случае неудачи 
    catch (e) 
    {} 
  } 
} 
// Функция обработки ответа сервера 
function getpredmets() 
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
		var predmets=document.getElementById('lessonsx');
		var label=document.getElementById('label_lessonsx');
		label.style.visibility='visible';
		predmets.style.visibility='visible';
		predmets.innerHTML='<select id="lessons" name="lessons">'+response+'</select>';
		
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