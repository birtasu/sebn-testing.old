<style>
.add_test{	
	margin: auto;
}
.result{	
	position: relative;
	font-weight: bold;
	display: inline-block;
	width: 100%;
}
.ui.celled.table.all_result{
	position: relative;	
	font-weight: bold;
	font-size: 16px;
	width: 100%;
	margin: auto;
}
.buttons{
	width: 100%;
	text-align: center;
	display: none;
	margin: auto;
}
.ui.input.users input{
	width: 100px;
	border: none;
	font-weight: bold;
	font-size: 16px;	
}
.edit, .edit_this{
	cursor: pointer;
	font-weight: bold;	
}
.del{
	cursor: pointer;
	font-weight: bold;
}
p{
	font-size: 17px;
	font-weight: bold;
}
i{
	cursor: pointer;
}
.body{	
	background-image: url(img/bg3.png);
    background-repeat: repeat;   
	min-height: 100%;
	min-width: 100%;
	
}
.accordion .content {
	background: rgba(0, 0, 0, 0.8);
	color: #fff;
}
.ui.styled.accordion .active.title {
	border: 1px solid #000;
	background: rgba(0, 0, 0, 0.9)!important;
	color: #fff!important;
}
.cont_det{
	font-weight: normal;
	font-size: 15px;
	font-style: italic;
}
</style>
<style>
.category th, .districts th{
	text-align: center!important;
	background: #000!important;
	color: #fff!important;
}
.category td, .districts td{	
	background: rgba(0, 0, 0, 0.7)!important;
	color: #fff!important;
}
.content_2{
	position: relative;
	width: 60%;
	margin: auto;
	top: 10%;
}
.location{
	width: 100%;
}
</style>
<title>Адмін сторінка</title>
<?php
include 'config.php'; include 'conf.php';


if (isset($_COOKIE['id'])) 
{    
    $userdata = mysqli_fetch_assoc($connection->query("SELECT * FROM `users` WHERE users_id = '".intval($_COOKIE['id'])."' and `testuvannya` = '1' LIMIT 1"));

     if($userdata['users_id'] !== $_COOKIE['id'])
    { 

        setcookie('id', '', time() - 60*60*24*30*12, '/');
		setcookie('login', '', time() - 60*60*24*30*12, '/');
        setcookie('hash', '', time() - 60*60*24*30*12, '/');
        setcookie('errors', '1', time() + 60*60*24*30*12, '/');
        echo "<script>window.location.href='index.php';</script>";
        exit;
    } 
} 
else 
{ 
  setcookie('errors', '2', time() + 60*60*24*30*12, '/');
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

if (isset($_GET['exit']))
{
     setcookie('id', '', time() - 30);
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

$districts = array();
$category = array();	
$result = $connection->query("select * from `test_districts`");
while ($row = mysqli_fetch_assoc($result)) {
	$districts[] = $row;
}
$result = $connection->query("select * from `test_category`");
while ($row = mysqli_fetch_assoc($result)) {
	$category[] = $row;
}
$content .= '
<div class="body">
<div style="position: fixed; width: 8%; height: 15%; left: 1%; top: 6%;" class="img"></div>
<div class="ui top attached tabular menu" style="width: 80%; margin: auto; top: 2%; position: relative;">
  <a class="item active" data-tab="first"><i class="file text outline icon"></i> Створити тестування</a>
  <a class="item" data-tab="second"><i class="bar chart icon"></i> Результати</a>
  <a class="item" data-tab="read"><i class="write icon"></i> Редагування</a>
  <a class="item" data-tab="admins"><i class="user outline icon"></i> Адміністратори</a>
  <a class="item" href="index.php"><i class="sign out icon"></i> Вихід</a>
</div>
<div class="ui bottom attached tab segment active" data-tab="first" style="width: 80%; margin: auto; top: 2%; position: relative;">

<form id="form" method="POST" action="data.php" enctype="multipart/form-data">
	<div class="add_test">
		<div class="ui input">
			<input type="text" placeholder="Пошук..." id="search" autocomplete="off" autofocus>
		</div>		
		<div class="ui input" style="width: 300px;">
			<input type="text" class="result" data-info="" autocomplete="off" readonly="readonly">
		</div>
		<i class="plus icon add_user" style="display: none;"></i>';
		$content .= '<select class="ui dropdown" name="tema" id="tema">';
		$content .= '<option value="">Оберіть дільницю</option>';
		for ($k = 0; $k < count($districts); $k++){
			$content .= '<option value="'.$districts[$k]['id'].'">'.$districts[$k]['name'].'</option>';
		}
		$content .= '</select>
		<input type="hidden" name="users_lenght" id="users_lenght"><input type="hidden" name="lenght" id="lenght">	
		<br><br>
		<table class="ui celled table all_result"><tr class="no_users"><td style="text-align: center; padding: 60; font-size: 25px;"><i class="users large icon"></i>&nbsp;&nbsp;Працівників не додано</td></tr></table>		
	</div>
</form>
<div class="buttons">	
	<button class="ui green button" id="save">Зберегти</button>
</div>

</div>
<div class="ui bottom attached tab segment" data-tab="second" style="width: 80%; margin: auto; top: 2%; position: relative;">';
	
	$result = $connection->query("select a.*, c.name as districts from test_users a left join test_districts c ON a.category = c.id ORDER BY a.`id` DESC");
	$content .= '<table class="ui celled table" id="datatable"><thead><tr><th>Дата створення</th><th>Номер тесту</th><th>Табельний</th><th>ПІБ</th><th>Група</th><th>Результати</th><th>Дата проходження</th></tr></thead><tbody>';		
	
	while ($row = mysqli_fetch_assoc($result)) {
		$content .= '<tr><td>'.$row['date_created'].'</td><td>'.$row['id'].'</td><td>'.$row['employee_id'].'</td><td>'.$row['employee_id'].'</td><td>'.$row['districts'].'</td><td>'.$row['result'].'&nbsp;&nbsp;&nbsp;<i class="line chart icon result_chart_2" data-id="'.$row['id'].'" style="display: '.(!empty($row['result']) ? '' : 'none').'; cursor: pointer;"></i> <i class="line chart icon result_chart_3" data-id="'.$row['id'].'" style="display: '.(!empty($row['result']) ? '' : 'none').'; cursor: pointer;"></i> <i class="file text outline icon get_history" data-id="'.$row['id'].'" style="display: '.(!empty($row['result']) ? '' : 'none').'; cursor: pointer;"></i></td><td>'.$row['date_end'].'</td></tr>';
	} 
$content .= '</tbody></table>
</div>
<div class="ui bottom attached tab segment" data-tab="read" style="width: 80%; margin: auto; top: 2%; position: relative;">	
';		
	
	$content .= '<select class="ui dropdown fluid" id="category">';
	$content .= '<option value="">Оберіть категрію</option>';
for ($k = 0; $k < count($category); $k++){
	$content .= '<option value="'.$category[$k]['id'].'">'.$category[$k]['name'].'</option>';
}
	$content .= '<option value="add">Додати нове запитання</option>';
	$content .= '<option value="category">Редагувати категорії</option>';
	$content .= '<option value="count_questions" class="ttt">Редагувати кількість запитань</option>';
	$content .= '<option value="districts">Редагувати дільниці</option>';
	$content .= '</select>';
	$content .= '<br><br><div class="result_category"></div><br>';		

	
$content .='	
</div>
<div class="ui bottom attached tab segment" data-tab="admins" style="width: 80%; margin: auto; top: 2%; position: relative;">';

$result = $connection->query("select * from `users`");
$content .= '<table class="ui celled table" id="datatable"><thead><tr><th>Дата додавання</th><th>Табельний</th><th>ПІБ</th><th>Дії</th></tr></thead><tbody>';

while ($row = mysqli_fetch_assoc($result)) {
    $content .= '<tr><td>'.$row['data'].'</td><td>'.$row['users_login'].'</td><td>ПІБ</td><td><i class="line minus icon del_admin" data-id="'.$row['users_id'].'"></i></td></tr>';
}
$content .= '<tr><td colspan="4" style="text-align: center;"><i class="plus green icon add_admin"></i></td></tr></tbody></table>
</div>
<div class="ui small modal" style="width: 60%; margin: 0 0 0 -30%;">
  <div class="header">Результати тестування: </div>
  <div class="content">
    <div id="container"></div><div id="container_2"></div><div id="container_3"></div><div id="container_4"></div>
  </div>
  <div class="actions">    
    <div class="ui cancel button">Закрити</div>
  </div>
</div>

<div class="ui basic modal edit_test_modal" style="width: 900px; height: 800px;">
	
	  <div class="ui icon header"><i class="write icon"></i>Редагування:</div>
	  <div class="contents"></div>
	  
	  <div class="actions">
		<div class="ui red basic cancel inverted button">
		  <i class="remove icon"></i>
		  Відмінити
		</div>
		<div class="ui green ok inverted button edit_form">
		  <i class="checkmark icon"></i>
		  Редагувати
		</div>
	  </div>	 
</div>

<div class="ui tiny modal save_result">
  <div class="header" style="color: green;">Повідомлення:</div>
  <div class="content"></div>
  <div class="actions">
    <div class="ui black deny cancel button">Закрити</div>    
  </div>
</div>
</div>

<div class="ui tiny modal history">
  <div class="header" style="color: green;">Повідомлення:</div>
  <div class="content"></div>
  <div class="actions">
    <div class="ui black deny cancel button">Закрити</div>    
  </div>
</div>
</div>

<div style="	
    position: fixed;
    top: 1%;
    right: 1%;
	opacity: 0;
	display: none;
	text-align: center;		
	border: 1px solid #000;
	" id="smail">
	<img></img>
</div>
';
echo $content; 	
	
$content_location .= '<select class="ui dropdown location">';
$content_location .= '<option value="">Оберіть дільницю</option>';
for ($k = 0; $k < count($districts); $k++){
$content_location .= '<option value="'.$districts[$k]['id'].'">'.$districts[$k]['name'].'</option>';
}
$content_location .= '</select>'; 


$content_add .= '<form class="ui form" id="add_form" method="POST" action="data.php" enctype="multipart/form-data">';
$content_add .= '<div class="field">';
$content_add .= '<label>Категорія</label>';
$content_add .= '<select class="ui dropdown" name="form_tema" id="form_tema">';
$content_add .= '<option value="">Оберіть категрію</option>';
for ($k = 0; $k < count($category); $k++){
$content_add .= '<option value="'.$category[$k]['id'].'">'.$category[$k]['name'].'</option>';
}	  
$content_add .= '</select>';
$content_add .= '</div>';
$content_add .= '<div class="field">';
$content_add .= '<label>Запитання</label>';
$content_add .= '<input type="text" name="pytannya" id="pytannya" value="" autocomplete="off">';
$content_add .= '</div>';
$content_add .= '<div class="field">';
$content_add .= '<label>Варіант 1</label>';
$content_add .= '<input type="text" name="variant_1" id="variant_1" value="" autocomplete="off">';
$content_add .= '</div>';
$content_add .= '<div class="field">';
$content_add .= '<label>Варіант 2</label>';
$content_add .= '<input type="text" name="variant_2" id="variant_2" value="" autocomplete="off">';
$content_add .= '</div>';
$content_add .= '<div class="field">';
$content_add .= '<label>Варіант 3</label>';
$content_add .= '<input type="text" name="variant_3" id="variant_3" value="" autocomplete="off">';
$content_add .= '</div>';
$content_add .= '<div class="field">';
$content_add .= '<label>Варіант 4</label>';
$content_add .= '<input type="text" name="variant_4" id="variant_4" value="" autocomplete="off">';
$content_add .= '</div>';
$content_add .= '<div class="field">';
$content_add .= '<label>Вірна відповідь</label>';
$content_add .= '<input type="text" name="vidpovid" id="vidpovid" value="" autocomplete="off"><input type="hidden" name="add_form" value="yes">';
$content_add .= '</div>';
$content_add .= '</form><button class="ui black button add_questions" style="width: 100%;">Зберегти</button>';

$content_districts .='<table class="ui celled table districts">';
$content_districts .= '<thead><tr><th>Дільниці:</th><th></th></tr></thead><tbody>';
for ($k = 0; $k < count($districts); $k++){
$content_districts .= '<tr><td>'.$districts[$k]['name'].'</td><td style="width: 1%;"><i data-id="'.$districts[$k]['id'].'" class="minus red icon del_districts"></i></td></tr>';
}
$content_districts .='</tbody><tfoot><tr><td colspan="2" style="text-align: center;"><i class="plus green icon add_districts"></i></td></tr></tfoot></table">';	

$content_category .='<table class="ui celled table districts">';
$content_category .= '<thead><tr><th>Категорії:</th><th></th></tr></thead><tbody>';
for ($k = 0; $k < count($category); $k++){
$content_category .= '<tr><td>'.$category[$k]['name'].'</td><td style="width: 1%;"><i data-id="'.$category[$k]['id'].'" class="minus red icon del_category"></i></td></tr>';
}
$content_category .='</tbody><tfoot><tr><td colspan="2" style="text-align: center;"><i class="plus green icon add_category"></i></td></tr></tfoot></table">';	

?>
<script>
$('body').on('click', '.del_districts', function(){
	var a = confirm('Дійсно бажаєте видалити цю дільницю?');
	var b = $(this).data('id');
	if(a == true){
		$.ajax({
		type: "POST",
		url: "data.php",
		data: {del_districts: b},
		dataType: "json",
		success: function(response){
			alert(response.result);
			location.reload();
		}
		});
	}
});
$('body').on('click', '.del_admin', function(){
    var a = confirm('Дійсно бажаєте видалити адміністратора?');
    var b = $(this).data('id');
    if(a == true){
        $.ajax({
            type: "POST",
            url: "data.php",
            data: {del_admin: b},
            dataType: "json",
            success: function(response){
                alert(response.result);
                location.reload();
            }
        });
    }
});

$('body').on('click', '.del_category', function(){
	var a = confirm('Дійсно бажаєте видалити цю категорію?');
	var b = $(this).data('id');
	if(a == true){
		$.ajax({
		type: "POST",
		url: "data.php",
		data: {del_category: b},
		dataType: "json",
		success: function(response){
			alert(response.result);
			location.reload();
		}
		});
	}
});
$('body').on('click', '.add_districts', function(){
	var a = prompt('Введіть назву дільниці:');
	if(a.length > 0){
		$.ajax({
		type: "POST",
		url: "data.php",
		data: {new_districts: a},
		dataType: "json",
		success: function(response){
			alert(response.result);
			location.reload();
		}
		});
	}else{
		alert("Не збережено! Поле було пустим!")
	}
});
$('body').on('click', '.add_category', function(){
	var a = prompt('Введіть назву категорії:');
	if(a.length > 0){
		$.ajax({
		type: "POST",
		url: "data.php",
		data: {new_category: a},
		dataType: "json",
		success: function(response){
			alert(response.result);
			location.reload();
		}
		});
	}else{
		alert("Не збережено! Поле було пустим!")
	}
});
$('body').on('click', '.add_admin', function(){
    var a = prompt('Введіть табельний номер:');
    if(a.length > 0){
        $.ajax({
            type: "POST",
            url: "data.php",
            data: {new_admin: a},
            dataType: "json",
            success: function(response){
                alert(response.result);
                location.reload();
            }
        });
    }else{
        alert("Не збережено! Поле було пустим!")
    }
});
$('#search').on('input', function(){
	// $.ajax({
	// 	type: "POST",
	// 	url: "data.php",
	// 	data: {EmployeeID: $(this).val()},
	// 	dataType: "json",
	// 	success: function(response){
	// 		$('.result').val(response.result);
	// 		$('.result').data('info', response.result_2);
	// 		$('.img').html(response.result_3);
	// 		if($('.result').val().length > '0'){
	// 			$('.plus.icon.add_user').show();
	// 		}else{
	// 			$('.plus.icon.add_user').hide();
	// 		}
	// 	}
	// });
    $('.result').val("Тут має бути ПІБ");
    $('.result').data('info', "Тут має бути посада");
    //$('.img').html(response.result_3);
    if($('.result').val().length > '0'){
        $('.plus.icon.add_user').show();
    }else{
        $('.plus.icon.add_user').hide();
    }
});
$(document).ready(function(){
	$('*[data-value="add"]').css("background", "#70e08e");
	$('*[data-value="count_questions"]').css("background", "#70c8e0");
	$('*[data-value="districts"]').css("background", "#e29cf0");
	$('*[data-value="category"]').css("background", "#f5bf82");
	$('#search').keypress(function(e){
		if(e.keyCode==13){
			if($('#search').val().length)
			add_user();
		}
	});
});
$('.plus.icon.add_user').on('click', function(){
	add_user();
});
$('#save').on('click', function(e){
	if($('#tema').val() <= '0'){
		$('#smail img').attr('src', 'img/error_2.png');
		$('#smail').css({height: "0px", display : "block", opacity : "1" }).animate({ height: "300px" }, 1000).delay(3000).animate({ height: "0px" }, 1000);
		setTimeout(function(){
			$('#smail').css({display: "none", opacity : "0"});
		}, 5000);
	}else{
		e.preventDefault();
		var $that = $("#form");
		$.ajax({
			url: 'data.php',
			type: 'post',
            data: $that.serialize(),
            dataType: "text",
			success: function(response){
				$('.save_result .content').html(response);
				$('.save_result')
				  .modal('setting', 'closable', false)
				  .modal('show')
				;
				$('.cancel').on('click', function(){
					setTimeout(function(){location.reload();}, 500);
				});				
			},
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error: ' + textStatus + ' ' + errorThrown);
            }
		});	
	}
});
var i = 1;
function add_user(){
		
	$('.all_result').append(
	'<tr>' +	
	'<td style="text-align: center; width: 10%;">' + $('#search').val() + '<input class="users_test" type="hidden" value="'+ $('#search').val() +'" name="user_'+ i +'" readonly></td>' +		
	'<td><div class="ui big list">' +
			'<div class="item">' +
				'<img class="ui avatar image" src="/assets/pictures/employees/'+ $('#search').val() +'.jpg">' +
				'<div class="content">' +
					'<div class="header">' + $('.result').val()  + '</div>' +
					'<div class="cont_det">' + $('.result').data('info') + '</div>' +
				'</div>' +
			'</div>' +
		'</div>' +
	'</td>' +	
	'<td style="text-align: center; width: 10%;">' +'<i class="minus icon del_user" style="cursor: pointer;"></td>' +
	'<tr>');
	$('#search').val('');
	$('.result').val('');
	$('.img').html('');
	$('.plus.icon.add_user').hide();	
	i = i + 1;	
	$('#lenght').val($('.users_test').length);
	$('#users_lenght').val($('.users_test').length);
	
	if($('#lenght').val() > '0'){
		$('.no_users').hide();
		$('.buttons').show();
	}
	
}
	
$('html').on('click', '.del_user', function(){	
	$(this).closest('tr').remove();	
	$('#lenght').val($('#lenght').val() - 1);	
	if($('#lenght').val() <= '0'){
		$('.no_users').show();
		$('.buttons').hide();
	}
	return false;	
});
</script>


<script>
$('.result_chart_2').on('click', function(){	
	$.ajax({
		type: "POST",
		url: "chart_2.php",
		data: {chart: $(this).data('id')},
		//dataType: "json",
		success: function(response){			
			$('#container').html("");
			$('#container_2').html(response);
			$('#container_3').html("");
			$('#container_4').html("");
			$('.small.modal')
			  .modal('show')
			;			
		}
	});	
});
</script>

<script>
$('.result_chart_3').on('click', function(){	
	$.ajax({
		type: "POST",
		url: "chart_3.php",
		data: {chart: $(this).data('id')},
		//dataType: "json",
		success: function(response){			
			$('#container_3').html(response);			
			$('#container_2').html("");
			$('#container_4').html("");
			$('.small.modal')
			  .modal('show')
			;			
		}
	});	
});
</script>

<script>
$('.get_history').on('click', function(){	
	$.ajax({
		type: "POST",
		url: "data.php",
		data: {get_history: $(this).data('id')},
		dataType: "json",
		success: function(response){
			$('#container').html("");
			$('#container_2').html("");
			$('#container_3').html("");
			$('#container_4').html(response.result);
			$('.small.modal')
			  .modal('show')
			;			
		}
	});	
});
</script>

<script>
$('#category').on('change', function(){
	$('.ui.dropdown').dropdown();
	if($(this).val() == 'add'){
		$('.result_category').html('<?php echo $content_add; ?>');
		$('.ui.dropdown').dropdown();
	}else if($(this).val() == 'category'){
		$('.result_category').html('<div class="content_2">' + '<?php echo $content_category; ?>' + '</div>');
		$('.ui.dropdown').dropdown();
	}else if($(this).val() == 'districts'){
		$('.result_category').html('<div class="content_2">' + '<?php echo $content_districts; ?>' + '</div>');
		$('.ui.dropdown').dropdown();
	}else if($(this).val() == 'count_questions'){
		$('.result_category').html('<div class="content_2">' + '<?php echo $content_location; ?>' + '<br><br><div class="result_2"></div>' + '</div>');
		$('.ui.dropdown').dropdown();
		
		
		$('.location').on('change', function(){
			if($('.location option:selected')){		
				
				$.ajax({
				type: "POST",
				url: "data.php",
				data: {get_gategory: $('.location option:selected').val()},
				dataType: "json",
				success: function(response){
					$('.result_2').html(response.result);
				}
				});
			}
		});

		$('body').on('click', '.edit', function(e){
		var t = e.target || e.srcElement; 
		var elm_name = t.tagName.toLowerCase(); 
		if(elm_name == 'input') {return false;}
		var a = $(this).html();
		var b = $(this).data('id');
		var width = $(this).width() - 20; 
		var code = '<input type="text" style="text-align: center; border: none; color: #000;" id="edit" value="'+a+'" />';



		$(this).empty().append(code);
		$('#edit').css("width", width+"px");
		$('#edit').focus().select();

		$('#edit').blur(function() {
		var val = $(this).val(); 
		$(this).parent().empty().html(val);		

		var sum = 0;
		$('table tr').each(function(){  
			sum+=Number($('.edit', this).text());
		});		
		$('.q').html(sum);
		
		$.ajax({
		type: "POST",
		url: "data.php",
		data: {new_value: val, id_category: b, id_location: $('.location option:selected').val()} 
		});


		});
		});
		$(window).keydown(function(event){ 
		if(event.keyCode == 13) {
		$('#edit').blur();
		}
		});
	}else{
	$.ajax({
		type: "POST",
		url: "data.php",
		data: {category: $(this).val()},
		dataType: "json",
		success: function(response){		
			$('.result_category').html(response.result);
			$('.ui.accordion')
			  .accordion()
			;
			$('.edit_this').on('click', function(){
				var a = $(this).data('id_test');
				
					$.ajax({
					type: "POST",
					url: "data.php",
					data: {edit_this: a},
					dataType: "json",
					success: function(response){
						$('.edit_test_modal .contents').html(response.result);
						
						$('.edit_form').on('click', function(e){
							e.preventDefault();
							var that = $("#changed_form"),
							formData = new FormData(that.get(0));			
							$.ajax({
								url: that.attr('action'),
								type: that.attr('method'),
								contentType: false,
								processData: false,
								data: formData,	
								//dataType: "json",
								success: function(response){
									location.reload();
								}
							});
						});
						
					}
					});	
				$('.ui.basic.modal.edit_test_modal')
					.modal('setting', 'closable', false)
					.modal('show')
				;				
				
			});
			$('.del').on('click', function(){
				var a = confirm("Дійсно видалити це запитання?");
				if(a == true){
					var a = $(this).data('id_test');
				
					$.ajax({
					type: "POST",
					url: "data.php",
					data: {del_this: a},
					//dataType: "json",
					success: function(response){
						location.reload();
					}
					});
				}
			});
		}
	});	
	}
	
});
</script>
<script>
$('body').on('click', '.add_questions', function(e){
	if($('#form_tema').val() > '0' && $('#pytannya').val() > '0' && $('#variant_1').val() > '0' && $('#variant_2').val() > '0' && $('#variant_3').val() > '0' && $('#variant_4').val() > '0' && $('#vidpovid').val() > '0'){
		e.preventDefault();
		var that = $("#add_form"),
		formData = new FormData(that.get(0));			
		$.ajax({
			url: that.attr('action'),
			type: that.attr('method'),
			contentType: false,
			processData: false,
			data: formData,	
			//dataType: "json",
			success: function(response){
				$('#add_form').form('clear');
			}
		});
	}else{
		$('#smail img').attr('src', 'img/error.png');
		$('#smail').css({height: "0px", display : "block", opacity : "1" }).animate({ height: "300px" }, 1000).delay(3000).animate({ height: "0px" }, 1000);
		setTimeout(function(){
			$('#smail').css({display: "none", opacity : "0"});
		}, 5000);		
	}
	
});
</script>
<script>
$(".menu .item")
  .tab()
;
$('.ui.dropdown')
  .dropdown()
;
$('.ui.accordion')
  .accordion()
;

</script>
