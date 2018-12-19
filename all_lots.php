<?php
require_once('functions.php');
require_once('init.php');

session_start();

$categories = get_categories($connect);
$search = '';

if (isset($_SESSION['user'])) {
    $is_auth = $_SESSION['user'];
}  else {
    $is_auth = [];
}


if (isset($_GET['category'])) {
    $category_id = $_GET['category'];
    $category = get_category($connect, $category_id);
}
else {
    http_response_code(404);
    error404_show();
};

if (!empty($category_id)) {
    $cur_page = $_GET['page'] ?? 1;
    $page_items = 9;
    $lots_count = count_lots_in_category($connect, $category_id);
    $pages_count = ceil($lots_count / $page_items);
    $offset = ($cur_page - 1) * $page_items;
    $pages = range(1, $pages_count);

    $lots = get_lots_by_category($connect, $category_id, $page_items, $offset);
}

$page_content = include_template('all_lots.php', [
    'categories' => $categories,
    'lots' => $lots,
    'category' => $category,
    'pages' => $pages,
    'pages_count' => $pages_count,
    'cur_page' => $cur_page,
    'category_id' => $category_id
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'is_auth' => $is_auth,
    'search' => $search,
    'title' => 'Лоты по категории',
    'categories' => $categories
]);

print($layout_content);

