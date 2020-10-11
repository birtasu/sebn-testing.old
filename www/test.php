<?php include 'config.php'; include 'conf.php';

$r = $connection->query("SELECT count(*) FROM test_questions WHERE category = 1");
$d = mysqli_fetch_row($r);
$rand = mt_rand(0,$d[0] - 1);

$rnd = $connection->query("SELECT * FROM test_questions LIMIT $rand, 10");


$array = array();
while ($row = mysqli_fetch_assoc ($rnd)){
$array[] = $row;
}
shuffle($array);
    echo 'Shuffled results: <br>';
    foreach ($array as $result) {
        echo $result['category'] . ' ' . $result['pytannya'] . '<br>';
    }
//echo $row['category'].' '.$row['pytannya'].'<br>';


?>