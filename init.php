<?php
require_once('functions.php');

$database = [
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
    'db' => 'yeticave'
];

$connect = mysqli_connect($database['host'], $database['user'], $database['password'], $database['db']);
mysqli_set_charset($connect, "utf8");

if (!$connect) {
    die('Ошибка: Невозможно подключиться к MySQL ' . mysqli_connect_error());
}
?>
