<?php
require_once('functions.php');
require_once('data.php');
require_once('init.php');

session_start();

$categories = get_categories($connect);
$is_auth = $_SESSION['user'];

if (isset($is_auth)) {
    $user_id = $is_auth['id'];
}

$bets = get_bets_by_user_id($connect, $user_id);


$page_content = include_template('my_bets.php', [
    'bets' => $bets
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'is_auth' => $is_auth,
    'title' => 'Мои ставки',
    'categories' => $categories
]);

print($layout_content);
?>
