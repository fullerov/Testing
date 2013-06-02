
$(document).ready(function() { 
	
//функция добавляет вопрос к редактируемому тесту
$('#add_new').click(function(){
	
	var vars=document.getElementById("var_new").value;
	$(this).hide();
	$("#var_new").hide();
	$("#add_about").hide();
	
	var add='<p>Введите вопрос:</p><form method="post" action="';
	var url=document.URL;
	var mess='/addquestion/new"><textarea cols="80" rows="11" id="edqstion" name="question"></textarea><script language="JavaScript">generate_wysiwyg("edqstion");</script><p>Введите ответ на вопрос:</p><input type="text" name="answer" size="90" maxlength="255" value=""><br>';
	
	if(vars==1 || vars==0)
	{
		mess+='<h5>Тестируемый должен будет ввести ответ на вопрос самостоятельно, в полном соответствии с Вашим ответом</h5>';	
		add+=url+mess;	
	}
	else
	{
		mess+='<p>Введите варианты ответа:</p>';
		add+=url+mess;	
		if(vars>30)
			vars=30;
		
		for(i=0;i<vars;i++)
		{
			var num=i+1;
			add+='<input type="text" name="var'+num+'" size="80" maxlength="255" value=""><br>';
		}
	}
	

	
	add+='<input type="hidden" name="count" value="'+num+'"><input type="submit" name="add_question" value="Добавить новый вопрос">';
	
	$(this).after(add);
	
	});

//функция добавляет еще один вопрос при создании теста 
$('#add').click(function(){
	
	var txtar=document.getElementsByTagName('textarea'); 
	var txt=document.getElementsByClassName('answ');
	
	var qstn=txtar.length; // количество вопросов
	var answr=txt.length; //количество ответов
	
	//количество вариантов ответа для каждого вопроса
	var oneanswr=answr/qstn;  
	var addqstn=++qstn;
	
	//динамическая генерация формы добавления вопроса
	if(answr==0 || answr==1)
	{
		var str='<br><label id="question_label">Введите вопрос №'+addqstn+':</label><br><textarea id="addnewqstn'+addqstn+'" name="question'+addqstn+'" rows="10" cols="60"></textarea><script language="JavaScript">generate_wysiwyg("addnewqstn'+addqstn+'");</script><br><label>Введите ответ на вопрос:</label><br><input type="text" name="answer'+addqstn+'" size="70" maxlength="255"><br><h5>Тестируемый должен будет ввести ответ на вопрос самостоятельно, в полном соответствии с Вашим ответом</h5>';
		
	}
	else
	{
		var str='<br><label id="question_label">Введите вопрос №'+addqstn+':</label><br><textarea id="addnewqstn'+addqstn+'" name="question'+addqstn+'" rows="10" cols="60"></textarea><script language="JavaScript">generate_wysiwyg("addnewqstn'+addqstn+'");</script><br><label>Введите ответ на вопрос:</label><br><input type="text" name="answer'+addqstn+'" size="70" maxlength="255"><br><label>Введите варианты ответа:</label><br><span id="vars">';
		
		var i=0;
		while(i<oneanswr)
		{
			var j=++i;
			str+='<span id="num_answer">'+j+')</span> <input class="answ" type="text" name="q'+addqstn+'var'+j+'" size="60" maxlength="255"><br>';
		}
	
		str+='</span><br><br>';
	}
	
	
	
	//вывод сгенерированной формы перед кнопкой "Добавить еще вопрос"
	$(this).before(str);
	
	//установка количества добавленых вопросов в скрытое поле
	getQuantity();
	
	});
	
	 //функция вычисляет количество отображенных на экране полей ввода вопроса
	 function getQuantity()
	 {
		var newvalue=document.getElementById('hidden_q');
		if(newvalue!=null)
		{
		newvalue.value=document.getElementsByTagName('textarea').length;
		}
	 }
	 //вызов функции только на конкретном адресе
	 function checkURL()
	 {
		var url=document.URL;
		var res=url.indexOf('create/usertest',0);
		if(res!=-1)
		{
			getQuantity();
		}
	 }
	
	//поверка текущего адреса
	checkURL();
	
	//функция выводит список дополнительных параметров для редактирования студента
	$("#pspoiler").toggle(function(){
		
		document.getElementById("pspoiler").innerHTML='Скрыть -';
		$("#spoiler").css({"visibility":"visible","max-height":"210px"});
		
			},
		
		function(){
		
		document.getElementById("pspoiler").innerHTML='Дополнительные параметры +';
		$("#spoiler").css({"visibility":"hidden","max-height":"0px"});
		$("#spoiler input").css({"visibility":"hidden"});
		$("#label_faculty, #faculty, #label_univer, #univer, #label_course, #course, #label_specialty, #specialty, #label_group, #group").css({"visibility":"hidden"});
			});
			
		//функция выводит список дополнительных параметров теста для редактирования университетского теста
	$("#pspoiler2").toggle(function(){
		
		document.getElementById("pspoiler2").innerHTML='Скрыть -';
		$("#spoiler2").css({"visibility":"visible","max-height":"210px"});
		$("#univer").css({"visibility":"visible"});
			},
		
		function(){
		
		document.getElementById("pspoiler2").innerHTML='Дополнительные параметры +';
		$("#spoiler2").css({"visibility":"hidden","max-height":"0px"});
		$("#spoiler2 input").css({"visibility":"hidden"});
		$("#label_faculty, #faculty, #label_univer, #univer, #label_course, #course, #label_specialty, #specialty, #label_group, #group").css({"visibility":"hidden"});
			});
			
			
		//функция выводит список дополнительных параметров для редактирования школьника
		$("#pspoiler3").toggle(function(){
		
		document.getElementById("pspoiler3").innerHTML='Скрыть -';
		$("#spoiler3").css({"visibility":"visible","max-height":"210px"});
		$("#label_scity, #scity").css({"visibility":"visible"});
			},
		
		function(){
		
		document.getElementById("pspoiler3").innerHTML='Дополнительные параметры +';
		$("#spoiler3").css({"visibility":"hidden","max-height":"0px"});
		$("#spoiler3 input").css({"visibility":"hidden"});
		$("#label_scity, #scity, #label_school, #school, #label_class, #class").css({"visibility":"hidden"});
			});
	
	//функция добавляет форму для создания факультета
	$("#add_faculty").click(function(){
	
	document.getElementById('info').innerHTML='Заполните все нижеследующие поля и нажмите на кнопку "Добавить":';
	$(this).hide();
	$(this).after('<label for="name">Введите название факультета:</label><br><input type="text" maxlength=255 size=60 name="name"><br><label for="spec">Введите название первой специальности факультета:</label><br><input id="spec" type="text" maxlength=255 size=60 name="spec"><br><label for="course">Введите количество кусов на факультете:</label> <input type="number" maxlength=1 size=2 max=8 min=1 name="course"><br><center><input type="submit" name="addfac" value="Добавить"></center>');
			
	});
	
	
	//функция добавляет форму для создания новой студенческой группы
	$("#add_group").click(function(){
		
		$(this).hide();
		$(this).after('<p>Заполните все нижеследующие поля и нажмите на кнопку "Готово":</p><label for="name">Название группы:</label><br><input type="text" maxlenght=255 size=40 name="name"><br><label for="year">Год набора группы:</label> <input type="number" max=3000 min=1950 id="year" name="year">');
		
		
		});
	//функция добавляет предмет к университетскому тесту
	$("#addlesson").click(function(){
		
		$(this).hide();
		$(this).after('<center><p>Заполните нижеследующее поле и нажмите на кнопку "Готово":</p><label for="name">Название предмета:</label><br><input type="text" maxlenght=255 size=40 name="name"><br><input type="submit" name="submit" value="Готово"></center>');
		

		});
	//функция добавляет класс к школе
	$("#add_class").click(function(){
		
		$(this).hide();
		$(this).after('<br><label for="name">Введите название класса:</label><br><input type="text" maxlenght=255 size=60 name="name"><br><input type="submit" name="add" value="Добавить новый класс">');
		
		});

		//функция добавляет предмет к классу
	$("#add_lesson").click(function(){
		
		$(this).hide();
		$(this).after('<br><label for="name">Введите название предмета:</label><br><input type="text" maxlenght=255 size=60 name="name"><br><input type="submit" name="add" value="Добавить новый предмет">');
		
		});
	
		//функция выводит список дополнительных параметров для редактирования школьного теста
		$("#pspoiler4").toggle(function(){
		
		document.getElementById("pspoiler4").innerHTML='Скрыть -';
		$("#spoiler4").css({"visibility":"visible","max-height":"210px"});
		$("#school_label, #school").css({"visibility":"visible"});
			},
		
		function(){
		
		document.getElementById("pspoiler4").innerHTML='Дополнительные параметры +';
		$("#spoiler4").css({"visibility":"hidden","max-height":"0px"});
		$("#spoiler4 input").css({"visibility":"hidden"});
		$("#school_label, #school, #label_class, #class, #label_lessonsx, #lessonsx").css({"visibility":"hidden"});
			});
	
});

