<?php
error_reporting(0);
define ('DB_HOST', $_ENV["DEFAULT_DATABASE_HOST"]);
define ('DB_LOGIN', $_ENV["DEFAULT_DATABASE_USER"]);
define ('DB_PASSWORD', $_ENV["DEFAULT_DATABASE_PASSWORD"]);
define ('DB_NAME', $_ENV["DEFAULT_DATABASE_NAME"]);

$connection = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD) or die ("MySQL Error: " . $connection->error);
//mysqli_set_charset( $connection, 'utf8');

$connection->query("set names utf8") or die ("<br>Invalid query: " . $connection->error);
$connection->select_db(DB_NAME) or die ("<br>Invalid query: " . $connection->error);


$error[0] = 'Я вас не знаю';
$error[1] = 'Включіть куки';
$error[2] = 'Вам сюди не можна';
?>