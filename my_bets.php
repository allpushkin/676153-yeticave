<?php
require_once('functions.php');
require_once('init.php');

session_start();

$categories = get_categories($connect);
$search = "";

if (isset($_SESSION['user'])) {
    $is_auth = $_SESSION['user'];
    $user_id = $is_auth['id'];
} else {
    http_response_code(403);
    error403_show();
    die();
}

$bets = get_bets_by_user_id($connect, $user_id);


$page_content = include_template('my_bets.php', [
    'bets' => $bets,
    'user_id' => $user_id
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'is_auth' => $is_auth,
    'search' => $search,
    'title' => 'Мои ставки',
    'categories' => $categories
]);

print($layout_content);
?>
