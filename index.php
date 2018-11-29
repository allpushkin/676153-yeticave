<?php
require_once('functions.php');
require_once('data.php');

$connect = mysqli_connect($database['host'], $database['user'], $database['password'], $database['db']);
mysqli_set_charset($connect, "utf8");

if (!$connect) {
    print('Ошибка: Невозможно подключиться к MySQL ' . mysqli_connect_error());
}
else {
    //SQL-запрос для получения списка новых лотов
    $sql = 'SELECT lots.`id`, lots.`title`, `start_price`, `picture`, MAX(`bet_amount`), categories.`category_title` FROM lots '
         . 'LEFT JOIN bets ON lots.id = bets.lot_id '
         . 'INNER JOIN categories ON lots.category_id = categories.id '
         . 'WHERE `winner_id` IS NULL '
         . 'GROUP BY lots.`id` '
         . 'ORDER BY lots.`creation_date` DESC';

    if ($result = mysqli_query($connect, $sql)) {
        $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    else {
        $print('Произошла ошибка при выполнении запроса');
    }
}

$page_content = include_template('index.php', [
    'categories' => $categories,
    'lots' => $lots
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
