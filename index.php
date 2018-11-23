<?php
require_once('functions.php');
require_once('data.php');

$page_content = include_template('index.php', [
    'categories' => $categories,
    'lots' => $products_list
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
