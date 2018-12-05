<?php
require_once('functions.php');
require_once('data.php');
require_once('init.php');

$categories = get_categories($connect);

if (isset($_GET['id'])) {
    $lot_id = $_GET['id'];
}
else {
    http_response_code(404);
    error404_show();
};

$lot = get_lot_by_id($connect, $lot_id);

$page_content = include_template('lot.php', [
    'categories' => $categories,
    'lot' => $lot
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
