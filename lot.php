<?php
require_once('functions.php');
require_once('init.php');

session_start();

$lot_close = false;
$bet_done = false;
$search = "";

$categories = get_categories($connect);

if (isset($_SESSION['user'])) {
    $is_auth = $_SESSION['user'];
}  else {
    $is_auth = [];
}

if (!empty($is_auth)) {
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
$bets = get_bets_by_lot_id($connect, $lot_id);

if(!isset($lot['id'])) {
    http_response_code(404);
    error404_show();
}

if (strtotime($lot['completion_date']) < strtotime('now')) {
    $lot_close = true;
}

if (!empty($bets) && !empty($is_auth)) {
    foreach ($bets as $bet) {
        if ((int)$bet['user_id'] === (int)$is_auth['id']) {
            $bet_done = true;
        }
    }
}

$current_price = $lot['current_bet'] ? $lot['current_bet'] : $lot['start_price'];

$min_bet = $current_price + $lot['step'];
$error ='';
$bet = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bet = $_POST['bet_amount'];

    if ((int)$bet < $min_bet) {
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
        header("Refresh:0");
    }
}

$page_content = include_template('lot.php', [
    'categories' => $categories,
    'min_bet' => $min_bet,
    'error' => $error,
    'is_auth' => $is_auth,
    'lot' => $lot,
    'lot_close' => $lot_close,
    'bet' => $bet,
    'bets' => $bets,
    'bet_done' => $bet_done
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
