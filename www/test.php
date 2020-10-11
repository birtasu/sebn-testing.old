<?php include 'config.php'; include 'conf.php';
/*
$rnd = $connection->query('
(SELECT * FROM test_questions WHERE category = 1 ORDER BY RAND() LIMIT 10)
UNION
(SELECT * FROM test_questions WHERE category = 2 ORDER BY RAND() LIMIT 10)
UNION
(SELECT * FROM test_questions WHERE category = 3 ORDER BY RAND() LIMIT 10)
UNION
(SELECT * FROM test_questions WHERE category = 4 ORDER BY RAND() LIMIT 5)
UNION
(SELECT * FROM test_questions WHERE category = 6 ORDER BY RAND() LIMIT 5)
UNION
(SELECT * FROM test_questions WHERE category = 7 ORDER BY RAND() LIMIT 5)
UNION
(SELECT * FROM test_questions WHERE category = 8 ORDER BY RAND() LIMIT 20)
UNION
(SELECT * FROM test_questions WHERE category = 10 ORDER BY RAND() LIMIT 10)
UNION
(SELECT * FROM test_questions WHERE category = 11 ORDER BY RAND() LIMIT 15)
UNION
(SELECT * FROM test_questions WHERE category = 12 ORDER BY RAND() LIMIT 10)
');
*/

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