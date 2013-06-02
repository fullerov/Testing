<div id="top_menu">
<?
if(!empty($_SESSION['loginfo']))
{
	//вывод имени пользователя из сериализованной стрки сессии
	$arr=$_SESSION['loginfo'];
	echo "<button title='Меню пользователя' id='profile'>$arr[login]</button>";
}
else
{												//гость
	echo '<button id="auth">&nbsp;Авторизация&nbsp;</button>
		  <a href="/users">&nbsp;Пользователи&nbsp;</a>
          <a href="/schools">&nbsp;Школы&nbsp;</a>
          <a href="/universities">&nbsp;Университеты&nbsp;</a>
          <a href="/organizations">&nbsp;Организации&nbsp;</a>';
}
	
	if($_SESSION['loginfo']['type_id']==6)		//начальник 
		echo '<a href="/organizations/my">&nbsp;Мои организации&nbsp;</a> 
		<a href="/organizations/workers">&nbsp;Мои сотрудники&nbsp;</a> 
		<a href="/organizations/results">&nbsp;Результаты тестов&nbsp;</a>';
	elseif($_SESSION['loginfo']['type_id']==1) //пользователь 
	{
		if($_SESSION['loginfo']['login']==='admin') //администратор
		echo '<a href="/authorization/countries">&nbsp;Страны&nbsp;</a>
		<a href="/users/types">&nbsp;Типы пользователей&nbsp;</a>
        <a href="/users/edit">&nbsp;Пользователи&nbsp;</a>';	
		else
	  		 echo '<a href="/users">&nbsp;Пользователи&nbsp;</a>
          <a href="/schools">&nbsp;Школы&nbsp;</a>
          <a href="/universities">&nbsp;Университеты&nbsp;</a>
          <a href="/organizations">&nbsp;Организации&nbsp;</a>';	
	}
	elseif($_SESSION['loginfo']['type_id']==2) //студент 
	{
		echo '<a href="/universities/myresult">&nbsp;Мои результаты&nbsp;</a>
			  <a href="/universities/myuniver">&nbsp;Мой университет&nbsp;</a>
         	  <a href="/universities/grouptests">&nbsp;Тесты для группы&nbsp;</a>
              <a href="/universities/lessonstests">&nbsp;Тесты по предметам&nbsp;</a>';		
	}
	elseif($_SESSION['loginfo']['type_id']==3) //школьник 
	{
		echo '<a href="/schools/myschool">&nbsp;Моя школа&nbsp;</a>
          <a href="/schools/myresults">&nbsp;Мои результаты&nbsp;</a>
		  <a href="/schools/classtests">&nbsp;Тесты для класса&nbsp;</a>
		  <a href="/schools/lessonstests">&nbsp;Тесты по предметам&nbsp;</a>
		  ';	
	}
	elseif($_SESSION['loginfo']['type_id']==4) //учитель 
	{
		echo '<a href="/authorization/results">&nbsp;Результаты&nbsp;</a>
		  <a href="/universities/my">&nbsp;Мои университеты&nbsp;</a>
          <a href="/schools/my">&nbsp;Мои школы&nbsp;</a>
          <a href="/schools/mypupils">&nbsp;Мои ученики&nbsp;</a>
          <a href="/universities/mystudents">&nbsp;Мои студенты&nbsp;</a>
		  ';		
	}
	elseif($_SESSION['loginfo']['type_id']==5) //рабочий 
	{
		echo '<a href="/organizations/worker">&nbsp;Моя организация&nbsp;</a>
          <a href="/organizations/myresults">&nbsp;Мои результаты&nbsp;</a>
          <a href="/organizations/orgtests">&nbsp;Тесты организации&nbsp;</a>';
	}
?> 

</div>


