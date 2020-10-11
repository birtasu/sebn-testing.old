<?php
//header('Content-Type: application/json');
header('Content-type: text/html; charset=UTF-8');
include 'conf.php';

$pytannya = $_POST['pytannya'];
$vidpovid = $_POST['vidpovid'];
$id = $_POST['ip'];
$add = $_POST['add'];
$result = $_POST['result'];
$EmployeeID = $_REQUEST['EmployeeID'];
$tema = $_REQUEST['tema'];
$form_tema = $_REQUEST['form_tema'];
$users_lenght = $_REQUEST['users_lenght'];
$category = $_REQUEST['category'];
$edit_this = $_REQUEST['edit_this'];
$del_this = $_REQUEST['del_this'];
$pytannya = $_POST['pytannya'];
$variant_1 = $_POST['variant_1'];
$variant_2 = $_POST['variant_2'];
$variant_3 = $_POST['variant_3'];
$variant_4 = $_POST['variant_4'];
$this_id = $_POST['this_id'];
$add_form = $_POST['add_form'];
$get_gategory = $_POST['get_gategory'];
$get_history = $_POST['get_history'];

$new_value = $_POST['new_value'];
$id_category = $_POST['id_category'];
$id_location = $_POST['id_location'];

$new_districts = $_POST['new_districts'];
$del_districts = $_POST['del_districts'];

$new_category = $_POST['new_category'];
$del_category = $_POST['del_category'];

$word_id = $_POST['word_id'];
$checked_word = $_POST['checked_word'];

if(isset($add)){
	$add = $connection->query("UPDATE test_results SET vidpovid='$vidpovid' WHERE pytannya = '$pytannya' and test_id = '$id'");
}
if(isset($result)){
	$check_result = $connection->query("SELECT a.vidpovid as vidpovidi, b.vidpovid FROM test_questions a LEFT JOIN test_results b ON a.id = b.pytannya WHERE b.test_id = '$id'");
	$total_rows = mysqli_num_rows($check_result);
	$good = '0';
	$no_good = '0';
	while($row = mysqli_fetch_assoc($check_result)){
		if($row['vidpovidi'] == $row['vidpovid']){$good++;}else{$no_good++;}
	}
	$update = $connection->query("UPDATE test_users SET `result` = '".$good." / ".$total_rows."' WHERE id = '$id'");
	$list = array('res_test_good'=>$good, 'res_test_no_good'=>$no_good, 'res_total'=>$total_rows);
	echo json_encode($list);
	
		$check = mysqli_fetch_assoc($connection->query("SELECT `check` FROM test_results WHERE test_id = '$id' LIMIT 1"));
		if(empty($check['check'])){
			$today = date("Y-m-d H:i:s");
			$update = $connection->query("UPDATE test_results SET `check` = '$today' WHERE test_id = '$id'");
		}
	
}
if(isset($EmployeeID)){
	$data = mysqli_fetch_assoc($connection->query("SELECT Employee, Subdivision, EmployeeID FROM `SEBN-UA`.`Employee` WHERE EmployeeID = '".$EmployeeID."'"));		
	$result .= $data['Employee'];
	$result_2 .= $data['Subdivision'];
	$result_3 .= '<img width="100%" height="100%" style="border-radius: 5%;" src="/assets/pictures/employees/'.$data['EmployeeID'].'.jpg"></img>';
	
	echo json_encode(array("result"=>$result, "result_2"=>$result_2, "result_3"=>(isset($data['EmployeeID']) ? $result_3 : '')));
	
}
if(isset($tema)){
    $a = mysqli_fetch_assoc($connection->query("SELECT * FROM test_location WHERE `locID` = '".$tema."' and `value` > '0' LIMIT 1"));

	if(!isset($a['value'])){
		$r .= "<p>Не збережено! Для цієї дільниці не вибрано запитань!</p>";
	}else{
        for ($i = 1; $i <= $users_lenght; $i++ ) {
            if(!empty($_POST['user_'.$i])){
                $user = $_POST['user_'.$i];

                $add_test_users = mysqli_fetch_assoc($connection->query("INSERT INTO test_users (category, employee_id) VALUES ('$tema', '$user')"));
                $data_2 = mysqli_fetch_assoc($connection->query("SELECT a.* FROM test_users a WHERE a.employee_id = '$user' AND a.category='$tema' ORDER BY a.`id` ASC LIMIT 1"));
                $r .= "<p>Тест: ".$data_2['id']."&nbsp;&nbsp;&nbsp;Тут має бути імя&nbsp;&nbsp;&nbsp;таб ".$data_2['employee_id']."</p>";

            }

        }
	}

    echo $r;

}
if(isset($category)){	
	$data = $connection->query("SELECT * FROM `test_questions` WHERE `category` = '".$category."'");		
	$i = 1;
		$html .='<div class="ui styled fluid accordion">';
	while($row = mysqli_fetch_assoc($data)){
		$html .='		
		  <div class="title"><i class="dropdown icon"></i>'.$row['pytannya'].'</div>
		  <div class="content">
			<p class="transition hidden"><img src="./img/'.$row['id'].'.png" style="display: '.($row['img'] == 1 || $row['img'] == 2 ? '' : 'none').';"/></p>
			<p class="transition hidden">Варіант 1: '.($row['img'] == 2 ? '<img src="img/'.$row['id'].'/1.png">' : $row['variant_1']).'</p>
			<p class="transition hidden">Варіант 2: '.($row['img'] == 2 ? '<img src="img/'.$row['id'].'/2.png">' : $row['variant_2']).'</p>
			<p class="transition hidden">Варіант 3: '.($row['img'] == 2 ? '<img src="img/'.$row['id'].'/3.png">' : $row['variant_3']).'</p>
			<p class="transition hidden">Варіант 4: '.($row['img'] == 2 ? '<img src="img/'.$row['id'].'/4.png">' : $row['variant_4']).'</p>
			<div class="transition hidden">
			<div class="edit_this" data-id_test="'.$row['id'].'" style="display: inline-block; width: 10%;"><i class="write green icon"></i>Редагувати</div>
			<div class="del" data-id_test="'.$row['id'].'" style="display: inline-block; width: 20%;"><i class="trash red icon"></i>Видалити запитання</div>
			</div>
		  </div>		
		';
	}			
		$html .='</div>';
	echo json_encode(array("result"=>$html));
}
if(isset($edit_this)){
	$data = mysqli_fetch_assoc($connection->query("SELECT * FROM test_questions WHERE id = '".$edit_this."'"));
	$data['pytannya'] = str_replace('"', '`', $data['pytannya']);$data['pytannya'] = str_replace("'", "`", $data['pytannya']);
	$data['variant_1'] = str_replace('"', '`', $data['variant_1']);$data['variant_1'] = str_replace("'", "`", $data['variant_1']);
	$data['variant_2'] = str_replace('"', '`', $data['variant_2']);$data['variant_2'] = str_replace("'", "`", $data['variant_2']);
	$data['variant_3'] = str_replace('"', '`', $data['variant_3']);$data['variant_3'] = str_replace("'", "`", $data['variant_3']);
	$data['variant_4'] = str_replace('"', '`', $data['variant_4']);$data['variant_4'] = str_replace("'", "`", $data['variant_4']);
	$html .='
	<form class="ui form" id="changed_form" method="POST" action="data.php" enctype="multipart/form-data">
	  <div class="field" style="display: '.($data['img'] == 1 || $data['img'] == 2 ? '' : 'none').'; text-align: center;"><img src="./img/'.$data['id'].'.png"/></div>
	  <div class="field">
		<label style="color: #fff;">Запитання</label>
		<input type="text" name="pytannya" value="'.$data['pytannya'].'" autocomplete="off">
	  </div>
	  <div class="field">
		<label style="color: #fff;">Варіант 1</label>
		<input type="text" name="variant_1" value="'.$data['variant_1'].'" autocomplete="off">
	  </div>
	  <div class="field">
		<label style="color: #fff;">Варіант 2</label>
		<input type="text" name="variant_2" value="'.$data['variant_2'].'" autocomplete="off">
	  </div>
	  <div class="field">
		<label style="color: #fff;">Варіант 3</label>
		<input type="text" name="variant_3" value="'.$data['variant_3'].'" autocomplete="off">
	  </div>
	  <div class="field">
		<label style="color: #fff;">Варіант 4</label>
		<input type="text" name="variant_4" value="'.$data['variant_4'].'" autocomplete="off">
		<input type="hidden" name="this_id" value="'.$data['id'].'">
	  </div>
	  <div class="field">
		<label style="color: #fff;">Вірна відповідь</label>
		<input type="text" name="vidpovid" value="'.$data['vidpovid'].'" autocomplete="off">		
	  </div>
	</form>
	';
	echo json_encode(array("result"=>$html));
}
if(isset($this_id)){
	$update = $connection->query("UPDATE test_questions SET `pytannya` = '$pytannya', `variant_1` = '$variant_1', `variant_2` = '$variant_2', `variant_3` = '$variant_3', `variant_4` = '$variant_4', `vidpovid` = '$vidpovid' WHERE id = '$this_id'");
	
}
if(isset($add_form)){
	$data = mysqli_fetch_assoc($connection->query("INSERT INTO test_questions (category, pytannya, variant_1, variant_2, variant_3, variant_4, vidpovid) VALUES ('$form_tema', '$pytannya', '$variant_1', '$variant_2', '$variant_3', '$variant_4', '$vidpovid')"));
}	
if(isset($del_this)){
	$update = $connection->query("DELETE FROM test_questions WHERE id = '".$del_this."'");
	
}
if(isset($get_gategory)){
$query = $connection->query("select a.*, b.value from test_category a left join test_location b on a.id = b.catID and b.locID = '".$get_gategory."' order by a.id ASC");

$content .= '<table class="ui celled table category">';
$content .= '<thead><tr><th>Категорія</th><th style="width: 20%;">Запитань</th></tr></thead>';	
while ($row = mysqli_fetch_assoc($query)){
$content .= '<tr><td>'.$row['name'].'</td><td class="edit" data-id="'.$row['id'].'" style="text-align: center;">'.$row['value'].'</td></tr>';	
$sum += $row['value'];
}
$content .= '<tr><td></td><td class="q" style="text-align: center; color: #e2fa0f!important;">'.$sum.'</td></tr></table><br>
';

echo json_encode(array("result"=>$content));	
}
if(isset($new_value) && isset($id_category) && isset($id_location)){
	$a = mysqli_fetch_assoc($connection->query("SELECT * FROM test_location WHERE locID = '".$id_location."' AND catID = '".$id_category."'"));
	if(!isset($a['value'])){
		$data = mysqli_fetch_assoc($connection->query("INSERT INTO test_location (locID, catID, value) VALUES ('$id_location', '$id_category', '$new_value')"));
	}else{
		$data = mysqli_fetch_assoc($connection->query("UPDATE test_location SET `value` = '".$new_value."' WHERE locID = '".$id_location."' AND catID = '".$id_category."'"));
	}
	
}
if(isset($new_districts)){
	$a = mysqli_fetch_assoc($connection->query("SELECT * FROM test_districts WHERE name = '".$new_districts."'"));
	if(!isset($a['id'])){
		$data = mysqli_fetch_assoc($connection->query("INSERT INTO test_districts (name) VALUES ('$new_districts')"));
		echo json_encode(array("result"=>"Дільницю успішно додано!"));	
	}else{
		echo json_encode(array("result"=>"Така дільниця уже існує в базі!"));		
	}
}
if(isset($del_districts)){
	$data = mysqli_fetch_assoc($connection->query("DELETE FROM test_districts WHERE id = '".$del_districts."'"));
	$data = mysqli_fetch_assoc($connection->query("DELETE FROM test_location WHERE locID = '".$del_districts."'"));
	echo json_encode(array("result"=>"Дільницю успішно видалено!"));
}
if(isset($new_category)){
	$a = mysqli_fetch_assoc($connection->query("SELECT * FROM test_category WHERE name = '".$new_category."'"));
	if(!isset($a['id'])){
		$data = mysqli_fetch_assoc($connection->query("INSERT INTO test_category (name) VALUES ('".$new_category."')"));
		echo json_encode(array("result"=>"Категорію успішно додано!"));	
	}else{
		echo json_encode(array("result"=>"Така категорія уже існує в базі!"));		
	}
}
if(isset($del_category)){
	$data = mysqli_fetch_assoc($connection->query("DELETE FROM test_category WHERE id = '".$del_category."'"));
	$data = mysqli_fetch_assoc($connection->query("DELETE FROM test_questions WHERE category = '".$del_category."'"));
	echo json_encode(array("result"=>"Категорію успішно видалено!"));
}
if(isset($get_history)){
	$result_history = $connection->query("
	select a.*, b.*, b.vidpovid as virna, a.vidpovid as my_vidpovid, b.id as img_id 
	from test_results a 
	left join test_questions b on a.pytannya = b.id 
	where a.test_id = '".$get_history."'");
	$result_history_word = $connection->query("
	SELECT a.*, b.`word`, b.`true` FROM `test_words_result` a LEFT JOIN `test_words` b ON a.word_id = b.id WHERE a.`test_id` = '".$get_history."'
	");
	$data .= '<table class="ui celled table result" style="width: 100%; margin: auto;">';
	$i = 1;
		$data .='<tr style="background: #000; color: #fff;"><td style="width: 15px;">#</td><td>Запитання</td><td>Відповідь</td></tr>';
	while ($row = mysqli_fetch_assoc($result_history)) {
		$data .= '<tr><td style="width: 15px;">'.$i++.'</td><td>'.$row['pytannya'].''.($row['img'] > 0 ? '<br><img src="./img/'.$row['img_id'].'.png" style="max-width: 90%;"' : '').'</td><td style="background: '.($row['my_vidpovid'] != $row['virna'] ? 'rgba(215, 44, 44, 0.5)' : 'rgba(118, 161, 44, 0.5)').';">'.($row['img'] == 2 ? '<img src="./img/'.$row['img_id'].'/'.$row['my_vidpovid'].'.png"' : $row['variant_'.$row['my_vidpovid']]).'</td></tr>';
	}
	if(mysqli_num_rows($result_history_word)){
		$data .= '<tr><td colspan="3" style="text-align: center; background: #000; color: #fff;">Додаткове завдання</td></tr>';
	}
	while ($row_2 = mysqli_fetch_assoc($result_history_word)) {
		$data .= '<tr><td style="width: 15px;">'.$i++.'</td><td>'.$row_2['word'].'</td><td style="background: '.($row_2['true'] == $row_2['question'] && $row_2['question'] == '1' ? 'rgba(118, 161, 44, 0.5)' : 'rgba(215, 44, 44, 0.5)').';">'.($row_2['true'] == $row_2['question'] && $row_2['question'] == '1' ? 'Так' : 'Ні').'</td></tr>';
	}
	$data .= '</table>';
	
	echo json_encode(array("result"=>$data));
}
if(isset($word_id)){
$a = ($checked_word == 'true' ? 1 : 0);
$data_add = mysqli_fetch_assoc($connection->query("UPDATE `test_words_result` SET `question` = ".$a." WHERE `id` = '".$word_id."'"));
}
?>