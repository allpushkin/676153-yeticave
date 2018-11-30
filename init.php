<?php
require_once('functions.php');
require_once('data.php');

$connect = mysqli_connect($database['host'], $database['user'], $database['password'], $database['db']);
mysqli_set_charset($connect, "utf8");

?>
