<?php
require_once('functions.php');
require_once('data.php');
require_once('timestamp.php');

$page_content = include_template('index.php', [
    'categories' => $categories,
    'lots' => $products_list,
    'time_left' => $time_left
]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'is_auth' => $is_auth,
    'username' => $user_name,
    'title' => 'YetiCave - Интернет-аукцион',
    'categories' => $categories
]);

print($layout_content);
?>
