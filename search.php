<?php
require_once('functions.php');
require_once('init.php');

session_start();

$categories = get_categories($connect);
$is_auth = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $search = trim($_GET['search']);
}

if (!empty($search)) {
    $cur_page = $_GET['page'] ?? 1;
    $page_items = 9;
    $lots_count = count_lots_in_search($connect, $search);
    $pages_count = ceil($lots_count / $page_items);
    $offset = ($cur_page - 1) * $page_items;
    $pages = range(1, $pages_count);

    $lots = search_lots($connect, $search, $page_items, $offset);
}

$pagination = include_template('pagination.php', [
    'search' => $search,
    'pages' => $pages,
    'pages_count' => $pages_count,
    'cur_page' => $cur_page
]);

$page_content = include_template('search.php', [
    'search' => $search,
    'lots' => $lots,
    'pagination' => $pagination

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
