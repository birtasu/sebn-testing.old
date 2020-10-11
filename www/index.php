<?php 
ob_start();

include 'config.php'; include 'conf.php';
$content .= '
	<style>
		.login_form{
			position: relative;
			width: 30%;
			margin: auto;
			border: 1px solid rgba(0, 0, 0, 0.1);
			border-radius: 3px 3px;
			padding: 30;
			top: 20%;
			background: #fff;
		}
		.ui.button.submit{
			width: 100%;
		}
		.tab1{
			position: relative;
			margin: auto;
			top: 20%;
		}
		.body{
			background: url(resources/img/bg3.png) repeat;
			height: 100%;
			width: 100%;
		}
	</style>
	<div class="body">
	<div class="login_form">
		<div class="ui top attached tabular menu">
		  <a class="item active" data-tab="first">Працівник</a>
		  <a class="item" data-tab="second">Адміністратор</a>	  
		</div>
		<div class="ui bottom attached tab segment active" data-tab="first">
			 <form class="ui form" method="POST">
				  <div class="field">		  
					<label>Табельний</label>
					<input type="text" name="empl" id="empl" placeholder="Введіть табельний номер" autofocus autocomplete="off">
				  </div>
				  <div class="field">
					<label>Номер тесту</label>
					<input type="text" name="test" id="test" placeholder="Введіть номер тесту" autocomplete="off">
				  </div>				  
				  <button class="ui button submit" name="submit_test" type="submit">Пошук</button>
			</form>
		</div>
		<div class="ui bottom attached tab segment" data-tab="second">
			  <form class="ui form" method="POST">			
				  <div class="field">		  
					<label>Табельний</label>
					<input type="text" name="empl" id="empl" placeholder="Введіть табельний номер" autofocus autocomplete="off">
				  </div>
				  <div class="field">
					<label>Пароль адміністратора</label>
					<input type="password" name="pass" id="pass" placeholder="Введіть пароль адміністрування" autocomplete="off">
				  </div>
				  <button class="ui button submit" name="submit" type="submit">Вхід</button>
			  </form>
		</div>		
	</div>
	</div>	
	<script>
		$(".menu .item")
		  .tab()
		;
	</script>';
	echo $content;
	
# Функція для генерування випадкової стрічки 
  function generateCode($length=6) { 
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789"; 
    $code = ""; 
    $clen = strlen($chars) - 1;   
    while (strlen($code) < $length) { 
        $code .= $chars[mt_rand(0,$clen)];   
    } 
    return $code; 
  }   

  //$_POST['empl'] = ltrim($_POST['empl'],'0');
 
  if (isset($_COOKIE['errors'])){
      $errors = $_COOKIE['errors'];
      setcookie('errors', '', time() - 60*60*24*30*12, '/');
  }

  if(isset($_POST['submit'])) 
  {    
 
    $data = mysqli_fetch_assoc($connection->query("SELECT * FROM `Preproduction`.`users` WHERE `users_login`='".$connection->real_escape_string($_POST['empl'])."' and `testuvannya` = '1' LIMIT 1"));

	if($data['users_password'] === md5(md5($_POST['pass']))) 
    {
      $hash = md5(generateCode(10));
      $connection->query("UPDATE `Preproduction`.`users` SET users_hash='".$hash."' WHERE `users_id`='".$data['users_id']."'")or die ("<br>Invalid query: " . $connection->error);

      setcookie("id", $data['users_id']);
      setcookie("login", $data['users_login']);
      setcookie("hash", $hash);

      echo "<script>window.location.href='admin.php';</script>";
      ob_end_flush();
      exit;
    }
    else
    {
      print("<table class='tab1'>");
      print "<tr><th>Ви ввели невірний табельний або пароль</th></tr><br>";
      print("</table>");
    }
  } 
  if(isset($_POST['submit_test'])) 
  { 
	$data = mysqli_fetch_assoc($connection->query("SELECT a.* FROM test_users a WHERE a.id = '".$_POST['test']."' AND a.employee_id = '".$_POST['empl']."' LIMIT 1"));
	if(empty($data['category'])){
		print("<table class='tab1'>");
		print "<tr><th>Ви ввели невірний табельний або номер тесту</th></tr><br>";
		print("</table>");
	}else{
		setcookie("test", $_POST['test'], time()+60*60*2); 
		setcookie("empl", $_POST['empl'], time()+60*60*2);
		header("Location: content.php?info"); exit();
	}
  }
  if (isset($errors)) {
	  print("<table class='tab1'>");
	  print "<tr><th>".$error[$errors]."</th></tr><br>";
	  print("</table>");
	  }

?>
