<?php
include 'conf.php';
$chart = $_REQUEST['chart'];


	$d_virni=array();
	$d_name=array();
	$d_nevirni=array();
	$result = $connection->query("
	select c.name, SUM(if(a.vidpovid = b.vidpovid, 1, 0)) as suma, SUM(if(a.vidpovid != b.vidpovid or a.vidpovid is null, 1, 0)) as suma_2    
	from test_results a 
	left join test_questions b 
	on a.pytannya = b.id 
	left join test_category c 
	on b.category = c.id 
	where a.test_id = '".$chart."'
	group by b.category
	order by b.category asc
	");	

	while ($row = mysqli_fetch_array($result)) {
		//$a .='<br><div style="color: red;">'.$row['suma'].'</div>';
		$d_virni[] = str_replace("\r\n", "", $row['suma']);
		$d_nevirni[] = str_replace("\r\n", "", $row['suma_2']);
		$d_name[] = str_replace("\r\n", "", "'".$row['name']."'");	
	}
		

?>
<script>
Highcharts.chart('container', {
    chart: {
        type: 'line'
    },
    title: {
        text: 'Тест №<?php echo $chart; ?>'
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        categories: [<?php echo join($d_name, ',') ?>]
    },
    yAxis: {
        title: {
            text: ''
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },
    series: [{
        name: 'Вірних відповідей',
        data: [<?php echo join($d_virni, ',') ?>],
		color: 'green'
    }, {
        name: 'Невірних відповідей',
        data: [<?php echo join($d_nevirni, ',') ?>],
		color: 'red'
    }]
});
</script>