<?php
require_once('functions.php');
require_once('data.php');
require_once('init.php');

session_start();

$categories = get_categories($connect);
$is_auth = $_SESSION['user'];

if (isset($_GET['id'])) {
    $lot_id = $_GET['id'];
}
else {
    http_response_code(404);
    error404_show();
};

$lot = get_lot_by_id($connect, $lot_id);

if(!isset($lot['id'])) {
    http_response_code(404);
    error404_show();
};

$page_content = include_template('lot.php', [
    'categories' => $categories,
    'is_auth' => $is_auth,
    'lot' => $lot
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'is_auth' => $is_auth,
    'title' => 'YetiCave - Интернет-аукцион',
    'categories' => $categories
]);

print($layout_content);
?>
