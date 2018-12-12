<?php
require_once('functions.php');
require_once('data.php');
require_once('init.php');

$categories = get_categories($connect);

$page_content = include_template('login.php', []);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'is_auth' => $is_auth,
    'username' => $user_name,
    'title' => 'Вход на сайт',
    'categories' => $categories
]);

print($layout_content);
?>
