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
}

if($lot['current_bet']) {
    $current_price = $lot['current_bet'];
}  else {
    $current_price = $lot['start_price'];
}

$min_bet = $current_price + $lot['step'];
$error ='';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bet = $_POST['bet_amount'];

    if (intval($bet) < $min_bet) {
        $error = 'Ставка не может быть меньше указанной минимальной ставки';
    }

    if (!is_numeric($bet)) {
        $error = 'Поле заполнено некорректно. Здесь должно быть целое положительное число';
    }

    if (empty($bet)) {
        $error = 'Вы не указали ставку';
    }

    if (empty($error)) {
        $user_id = $is_auth['id'];
        add_bet($connect, $lot, $bet, $user_id);
    }
}

$page_content = include_template('lot.php', [
    'categories' => $categories,
    'min_bet' => $min_bet,
    'error' => $error,
    'is_auth' => $is_auth,
    'lot' => $lot,
    'bet' => $bet
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'is_auth' => $is_auth,
    'title' => 'YetiCave - Интернет-аукцион',
    'categories' => $categories
]);

print($layout_content);
?>
