<?php
ob_start();
?>
<style>
.pagination
{
	position: absolute;
	top: 90%;
	text-align: center;
	width: 100%;
}
.num_question{
	position: absolute;
	top: 13%;
	width: 100%;
	left: 15%;
}
.numb
{
	position: absolute;
	top: 85%;
	width: 100%;
	text-align: center;
	font-size: 30px;
}
.question
{
	position: relative;
	top: 18%;
	width: 70%; 
	margin: auto; 
	text-align: center; 
	font-size: 25px;
	height: 45%;
	display: table;
	line-height: 1.5;
	background: #fff;
}
.answers
{
	position: absolute;
	top: 65%;
	text-align: center;
	width: 100%;
	font-weight: bold;
}
.ui.celled.table{
	border: 1px solid rgba(0, 0, 0, 0.3);	
}
.ui.celled.table td{	
	cursor: pointer;
	width: 50%;
	text-align: center;
	font-size: 18px;	
}
.ui.radio.checkbox{
	display: none;
}
.highlight{
	background: rgba(79, 180, 30, 1);
}
.highlight_2{
	background: rgba(79, 180, 30, 0.2);
}
#timer{
	position: fixed;
	left: 86%;
	top: 8%;	
	font-size: 2.1em;
	min-width: 100px;
	width: 160px;
	padding: .70em .78em;
	font-weight: bold;	
	color: rgba(0, 0, 0, 0.3);
	line-height: 1.1;
	text-align: center;
}
#progress_bar{
	position: fixed;
	left: 86%;
	top: 15%;
	width: 160px;
}
#exit{
	position: fixed;
	right: 1%;
	top: 8%;	
	font-size: 1.3em;		
	font-weight: bold;		
	line-height: 1.1;
	text-align: center;
	cursor: pointer;
}
.end_test{
	position: fixed;
	left: 86%;
	top: 2%;
	width: 160px;	
}
button{
	cursor: pointer;
	overflow: visible;
}
.test_info{
	border: 1px solid rgba(0, 0, 0, 0.7); 
	border-radius: 3px; 
	background: rgba(0, 0, 0, 0.7); 
	width: 70%; 
	text-align: center; 
	color: #fff; 
	padding: 5 5;	
	position: absolute;
}
.ui.celled.table.result td{
	padding: 5;
	text-align: left;
}
.body{
	background: url(img/bg3.png) repeat;
	height: 100%;
	width: 100%;
}
.body_2{
	background: url(img/bg3.png) repeat;
}
</style>
<?php

include 'config.php'; include 'conf.php';
$content .='<div class="body">';
if(isset($_GET['result']) && isset($_COOKIE['test']) && isset($_COOKIE['empl'])){
	$all_result = $connection->query("select a.*, b.*, a.vidpovid as my_vidpovid, b.id as img_id from test_results a left join test_questions b on a.pytannya = b.id where a.test_id = '".$_COOKIE['test']."' and a.vidpovid != b.vidpovid or a.test_id = '".$_COOKIE['test']."' and a.vidpovid is null");
	$content .='<div class="body_2"><div id="exit"><a href="content.php"><i class="sign out large icon"></i><br>Вихід</a></div>';
	$content .= '<table class="ui celled table result" style="width: 90%; margin: auto;">';
	$i = 1;
		$content .='<tr style="background: #000; color: #fff;"><td style="width: 15px;">#</td><td>Запитання</td><td>Ваша відповідь (невірна)</td></tr>';
	while ($row = mysqli_fetch_assoc($all_result)) {
		$content .= '<tr><td style="width: 15px;">'.$i++.'</td><td>'.$row['pytannya'].''.($row['img'] > 0 ? '<br><img src="./img/'.$row['img_id'].'.png" style="max-width: 90%;"' : '').'</td><td>'.($row['img'] == 2 ? '<img src="./img/'.$row['img_id'].'/'.$row['my_vidpovid'].'.png"' : $row['variant_'.$row['my_vidpovid']]).'</td></tr>';
	}
	$content .= '</table></div>';
	echo $content;
	
}else if(isset($_COOKIE['test']) && isset($_COOKIE['empl'])){
$a = mysqli_fetch_assoc($connection->query("SELECT a.* FROM test_users a WHERE a.id = '".$_COOKIE['test']."' AND a.employee_id = '".$_COOKIE['empl']."' LIMIT 1"));

$check = $connection->query("select * from test_results where test_id = '".$a['id']."'");
if(mysqli_num_rows($check) <= '0'){
	
	if(isset($_GET['info'])){
		echo '<div class="ui modal start">			  
			  <div class="header">
				Для вас повідомлення:
			  </div>
			  <div class="image content">
				<div class="ui medium image" style="text-align: center;">
				  <img src="img/info.jpg">
				</div>
				<div class="description">
				  <div class="ui header">Коротка інструкція по користуванню:</div>
				  <p>Запропоноване тестування містить 100 запитань по 10 темах.</p>
				  <p>На кожне питання є 4 варіанти відповіді, один з яких правильний.</p>
				  <p>Для переходу до наступного запитання - натисніть кнопку "Наступне запитання".  За допомогою кнопки "Попереднє запитання" ви можете переглянути та змінити відповіді на запитання, надані раніше.</p>
				  <p>Час на виконання тесту обмежений – 30 хвилин.</p>
				</div>
			  </div>
			  <div class="actions">				
				<a href="content.php"><div class="ui positive right labeled icon button">
				  Зрозуміло
				  <i class="checkmark icon"></i>
				</div></a>
			  </div>
			</div>
			<script>
			$( document ).ready(function() {
				$("#send").hide();
				$("#timer").hide();
			});
			
			
			$(".ui.modal.start")
			  .modal("setting", "closable", false)
			  .modal("show");
			</script>';
	}else{		
		$array = array();
		$array_2 = array();
		
		$b = $connection->query("SELECT * FROM test_location WHERE `locID` = '".$a['category']."'");
		while($row = mysqli_fetch_assoc($b)){
		$array[] = $row;	
		}	
		shuffle($array);
		
		foreach ($array as $result) {
			$rnd = $connection->query("SELECT * FROM test_questions WHERE category = '".$result['catID']."' ORDER BY RAND() LIMIT ".$result['value']."");
			while ($row_2 = mysqli_fetch_assoc ($rnd)){
			$array_2[] = $row_2;
			}		
		}	
		shuffle($array_2);
		
		foreach ($array_2 as $results) {
		$add = $connection->query("INSERT INTO test_results (pytannya, test_id) VALUES ('".$results['id']."', '".$a['id']."')");
		}
		
		$check_words = $connection->query("select * from test_words_result where test_id = '".$a['id']."'");
		if(mysqli_num_rows($check_words) <= '0'){
			$query_words = $connection->query("SELECT * FROM test_words");
			while($row_words = mysqli_fetch_assoc($query_words)){
				$add = $connection->query("INSERT INTO test_words_result (word_id, test_id) VALUES ('".$row_words['id']."', '".$a['id']."')");
			}
		}
	}

}

$get_date = mysqli_fetch_assoc($connection->query("SELECT * FROM test_results WHERE test_id = '".$a['id']."' ORDER BY date_time ASC LIMIT 1"));
	
$data = $connection->query("select a.vidpovid as my_vidpovid, b.*, b.vidpovid as correct from test_results a left join test_questions b on a.pytannya = b.id where a.test_id = '".$a['id']."'");
$rows_max = mysqli_num_rows($data);	

$show_pages = 1;
$this_page = filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT); 
if ($this_page)
{
        $offset = (($show_pages * $this_page) - $show_pages);
}
else
{
        $this_page = 1;
        $offset = 0;
}
 

if ($rows_max > $show_pages)
{
       $r = 1;
	   $next = ($this_page + 1 > $rows_max ? $rows_max : $this_page + 1);	   
	   $prev = ($this_page - 1 == 0  ? 1 : $this_page - 1);
	   $pagination .= '<div class="pagination"><a href="?page='.$prev.'" '.($this_page == 1 ? "onclick='return false;'" : "").'><button class="ui blue basic button" '.($this_page == 1 ? "disabled" : "").'>Попереднє запитання</button></a>';
      /*
		while ($r <= ceil($rows_max/$show_pages))
       {
           if ($rows_max == $this_page)
           {          
                $pagination .= '<a href="?page='.$r.'" title="Перейти на страницу '.$r.'">Додаткове</a>';
				
           }
           else
           {
               //$pagination .= '<b>'.$r.'</b>';
           }
            $r++;      
       }
	   */
	   $pagination .= '&emsp;&emsp;<a href="?page='.($this_page == $rows_max ? $rows_max + 1 : $next).'" '.($this_page == $rows_max + 1 ? "onclick='return false;'" : "").'><button class="ui '.($this_page == $rows_max ? 'green' : 'blue').' basic button" '.($this_page == $rows_max + 1 ? "disabled" : "").'>'.($this_page == $rows_max ? 'Додаткове запитання' : 'Наступне запитання').'</button></a></div>';	
	   $numb .= '<div class="numb">'.$this_page.' / '.$rows_max.'</div>';
}
if($this_page == $rows_max + 1){
	$content .= '<div style="width: 30%; margin: auto; top: 10%; position: relative;">
	<table class="ui table">
	<thead><tr><th style="text-align: center;">Прочитайте уважно слова, записані нижче.<br>Відзначте всі слова, значення яких Ви знаєте.</th></tr></thead>
	<tbody>';
	$query_words = $connection->query("SELECT a.*, b.word FROM `test_words_result` a LEFT JOIN `test_words` b ON a.word_id = b.id WHERE `test_id` = '".$a['id']."'");
	while ($row_words = mysqli_fetch_assoc ($query_words)){
	$content .= '<tr><td>
						<div class="ui checkbox">
							<input type="checkbox" class="word_check" data-word_id="'.$row_words['id'].'" id="example'.$row_words['id'].'" '.($row_words['question'] == '1' ? 'checked' : '').'>
							<label for="example'.$row_words['id'].'" style="cursor: pointer;">'.$row_words['word'].'</label>
						</div>
					</td>
				</tr>';
	}
	
	$content .= '</tbody>
	</table>
	</div>';
} 
$query_limited = "select a.vidpovid as my_vidpovid, b.*, b.vidpovid as correct, b.id, b.img, c.name from test_results a left join test_questions b on a.pytannya = b.id left join test_category c on c.id = b.category where a.test_id = '".$a['id']."' LIMIT $offset, $show_pages";
$final_result = $connection->query($query_limited);
$content .='<div id="timer"></div>';
$content .='
<div class="ui tiny indicating progress" id="progress_bar">
  <div class="bar"></div>
</div>';
$content .= '<div class="end_test"><button class="ui primary button send" style="width: 100%;" id="send" disabled>Завершити тест</button></div>';
while ($row = mysqli_fetch_assoc($final_result)) {  
$content .= '	
	<div class="num_question">
		<div class="test_info">
			<span style="float: right;">Працівник: Тест таб '.$a['employee_id'].'</span>
			<span style="float: left;">Запитання № '.$this_page.'.&emsp;Тема: '.$row['name'].'</span>
		</div>		
	</div>
	<div class="question"><div style="display: table-cell; text-align: center; vertical-align: middle; border: 1px solid rgba(0, 0, 0, 0.3); border-radius: .28571429rem;">'.$row['pytannya'].' <br><br><img src="./img/'.$row['id'].'.png" style="display: '.($row['img'] == 1 || $row['img'] == 2 ? '' : 'none').';"/></div></div>
	<div class="answers">
		<table class="ui celled table" style="width: 70%; margin: auto;">
			<tbody>
				<tr>					
					<td class="click '.($row['my_vidpovid'] == '1' ? 'highlight' : '').'" data-val="'.$row['variant_1'].'">
						<div class="ui radio checkbox">
							<input type="radio" data-ip="'.$a['id'].'" data-id="'.$row['id'].'" name="id_'.$row['id'].'" value="1" '.($row['my_vidpovid'] == '1' ? 'checked' : '').'>
						</div>a) '.($row['img'] == 2 ? '<img src="img/'.$row['id'].'/1.png">' : $row['variant_1']).'
					</td>
					<td class="click '.($row['my_vidpovid'] == '2' ? 'highlight' : '').'" data-val="'.$row['variant_2'].'">
						<div class="ui radio checkbox">
							<input type="radio" data-ip="'.$a['id'].'" data-id="'.$row['id'].'" name="id_'.$row['id'].'" value="2" '.($row['my_vidpovid'] == '2' ? 'checked' : '').'>
						</div>b) '.($row['img'] == 2 ? '<img src="img/'.$row['id'].'/2.png">' : $row['variant_2']).'
					</td>
				</tr>
				<tr>
					<td class="click '.($row['my_vidpovid'] == '3' ? 'highlight' : '').'" data-val="'.$row['variant_3'].'">
						<div class="ui radio checkbox">
							<input type="radio" data-ip="'.$a['id'].'" data-id="'.$row['id'].'" name="id_'.$row['id'].'" value="3" '.($row['my_vidpovid'] == '3' ? 'checked' : '').'>
						</div>c) '.($row['img'] == 2 ? '<img src="img/'.$row['id'].'/3.png">' : $row['variant_3']).'
					</td>
					<td class="click '.($row['my_vidpovid'] == '4' ? 'highlight' : '').'" data-val="'.$row['variant_4'].'" style="display: '.($row['img'] != 2 && empty($row['variant_4']) ? 'none' : '').';">
						<div class="ui radio checkbox">
							<input type="radio" data-ip="'.$a['id'].'" data-id="'.$row['id'].'" name="id_'.$row['id'].'" value="4" '.($row['my_vidpovid'] == '4' ? 'checked' : '').'>
						</div><span>d)</span> '.($row['img'] == 2 ? '<img src="img/'.$row['id'].'/4.png">' : $row['variant_4']).'					
					</td>
				</tr>
			</tbody>
		</table>
	</div>	
';
}
$content .='</div><div class="ui teal message hidden" style="text-align: center; top: 40%; position: fixed; width: 100%; font-size: 20px; background: rgba(0, 0, 0, 0.5); color: #fff;"></div>
	';
echo $content;
echo $numb;
echo $pagination;

}else{
    echo "<script>window.location.href='admin.php';</script>";
    exit;
}
ob_end_flush();
?>
<script>	
$('.click').on('click', function(){
	if($('#timer').html() != ''){
		$(this).find('input[type=radio]').prop("checked", true);
		$("input:radio").each(function() {
			$(this).closest("td").toggleClass("highlight", $(this).is(":checked"));
		});
		
		var ip = $(this).find('input[type=radio]').data('ip');
		var pytannya = $(this).find('input[type=radio]').data('id');
		var vidpovid = $(this).find('input[type=radio]').val();
		
		$.ajax({
			type: "POST",
			url: "data.php",
			data: {pytannya: pytannya, vidpovid: vidpovid, ip: ip, add: 'yes'}
		});
	}
});
</script>

<script>
$('.word_check').on('click', function(){	
	var word_id = $(this).data('word_id');	
	var a = $(this).is(":checked");
	
	$.ajax({
		type: "POST",
		url: "data.php",
		data: {word_id: word_id, checked_word: a}
	});
	
});
</script>
<script>
$('.send').on('click', function(){	
	$.ajax({
		type: "POST",
		url: "data.php",
		data: {ip: '<?php echo $a['id']; ?>', result: 'yes'},
		dataType: "json",
		success: function(response){
			location.reload();
		}
	});
});
</script>
<script>
var countDownDate = new Date("<?php echo ($get_date['check'] > '0' ? '2000-01-01 00:00:00' : $get_date['date_time']); ?>").getTime();
var x = setInterval(function() {
  
  var now = new Date().getTime();
  var distance = (countDownDate - now) + ((1000 * 25 * <?php echo mysqli_num_rows($data); ?>)  + (1000 * 60));
  var r = ((1000 * 25 * <?php echo mysqli_num_rows($data); ?>) + (1000 * 60));
  var k = Math.ceil((distance / r) * 100);
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);


  document.getElementById("timer").innerHTML = "<span style='font-size: 0.5em;'>До завершення</span><br>" + (hours < '10' ? '0' + hours : hours) + ":" + (minutes < '10' ? '0' + minutes : minutes) + ":" + (seconds < '10' ? '0' + seconds : seconds);


    $('#progress_bar').progress({
      percent: k
    });

  //document.write(Math.ceil(distance / 1000 + 30));
  if (distance < 0) {
	$('#progress_bar').hide();
    clearInterval(x);
    document.getElementById("timer").innerHTML = "";
	document.getElementById("send").disabled = true;
	$('.word_check').prop('disabled', true);
	$.ajax({
		type: "POST",
		url: "data.php",
		data: {ip: '<?php echo $a['id']; ?>', result: 'yes'},
		dataType: "json",
		success: function(response){
			$('div.ui.teal.message').html('Для вас тест завершено!<br>Ви вірно відповіли на ' + response.res_test_good + ' з ' + response.res_total + ' запитань<br><a href="?result" style="color: #ffc1ba;">Детальніше</a>&nbsp;&nbsp;&nbsp;<a href="index.php" style="color: #ffc1ba;">Вихід</a>');
			setTimeout(function(){$("div.ui.teal.message").transition('fly down').removeClass("hidden");}, 10);
		}
	});
      $("#timer").hide();
  }else{	
	  document.getElementById("send").disabled = false;
      $("#timer").show();
      $( ".click" ).hover(
      function() {
        $(this).closest("td").addClass( "highlight_2" );
      }, function() {
        $(this).closest("td").removeClass( "highlight_2" );
      });
  
  }
}, 100);
</script>