<?php
require_once('functions.php');
require_once('data.php');
require_once('init.php');

session_start();

$categories = get_categories($connect);
$is_auth = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $search = trim($_GET['search']);
}
if (!empty($search)) {
    $lots = search_lots($connect, $search);
}

$page_content = include_template('search.php', [
    'search' => $search,
    'lots' => $lots
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'is_auth' => $is_auth,
    'title' => 'Результаты поиска',
    'search' => $search,
    'categories' => $categories
]);

print($layout_content);
?>
