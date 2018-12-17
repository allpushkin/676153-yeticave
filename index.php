<?php
require_once('functions.php');
require_once('init.php');

session_start();

$categories = get_categories($connect);
$lots = get_lots($connect);
$search = "";

if (isset($_SESSION['user'])) {
    $is_auth = $_SESSION['user'];
}  else {
    $is_auth = [];
}

$page_content = include_template('index.php', [
    'categories' => $categories,
    'lots' => $lots
 ]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'is_auth' => $is_auth,
    'search' => $search,
    'title' => 'YetiCave - Интернет-аукцион',
    'categories' => $categories
]);

print($layout_content);
?>
