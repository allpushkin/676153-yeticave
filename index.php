<?php
require_once('functions.php');
require_once('data.php');
require_once('init.php');

session_start();

$categories = get_categories($connect);
$lots = get_lots($connect);

$page_content = include_template('index.php', [
    'categories' => $categories,
    'lots' => $lots
 ]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'YetiCave - Интернет-аукцион',
    'categories' => $categories
]);

print($layout_content);
?>
