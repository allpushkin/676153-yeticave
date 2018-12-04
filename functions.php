<?php
function include_template($name, $data) {
    $name = 'templates/' . $name;
    $result = '';

    if (!file_exists($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}
//Функция для форматирования цены и добавления знака рубля к ней
function cost_formatting($cost) {
    $cost = ceil($cost);
    if ($cost >= 1000) {
        $cost = number_format($cost,0,'',' ');
    }
    $cost .= " ₽";
    return $cost;
}

//Функция для вывода оставшегося времени действия лота
function lottime_left() {
    $time_left = strtotime('tomorrow') - time();
    $hours = floor($time_left / 3600);
    $minutes = floor(($time_left % 3600) / 60);
    if ($minutes < 10) {
        $minutes = 0 . $minutes;
    }

    if ($hours < 10) {
        $hours = 0 . $hours;
    }

    $time_left = $hours . ':' . $minutes;
    return $time_left;
}

//Функция для получения списка новых, открытых лотов
function get_lots($connect) {
    $sql = 'SELECT lots.`id`, lots.`title` AS `lot_title`, `start_price`, `picture`, MAX(`bet_amount`), categories.`title` AS `category_title` FROM lots '
         . 'LEFT JOIN bets ON lots.id = bets.lot_id '
         . 'INNER JOIN categories ON lots.category_id = categories.id '
         . 'WHERE `winner_id` IS NULL '
         . 'GROUP BY lots.`id` '
         . 'ORDER BY lots.`creation_date` DESC';

    if ($result = mysqli_query($connect, $sql)) {
        $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $lots;
    }
    else {
        error_show(mysqli_error($connect));
    }
}

function get_lot_byId($connect, $lot_id) {
    $sql = 'SELECT lots.`id`, lots.`title` AS `lot_title`, `desc`, `start_price`, `picture`, MAX(`bet_amount`), categories.`title` AS `category_title` FROM lots '
         . 'LEFT JOIN bets ON lots.id = bets.lot_id '
         . 'INNER JOIN categories ON lots.category_id = categories.id '
         . 'WHERE lots.`id` =' .$lot_id;

    if ($result = mysqli_query($connect, $sql)) {
        $lot = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $lot;
    }
    else {
        error_show(mysqli_error($connect));
    }
}



//Функция для получения списка категорий
function get_categories($connect) {
    $sql = 'SELECT `title` FROM categories';
    $res = mysqli_query($connect, $sql);

    if($res) {
        $categories = mysqli_fetch_all($res, MYSQLI_ASSOC);
        return $categories;
    }
    else {
        error_show(mysqli_error($connect));
    }
}

//Функция для вывода ошибки
function error_show($error) {
    $page_content = include_template('error.php', [
        'error' => $error
    ]);
    $layout_content = include_template('error_layout.php', [
        'content' => $page_content,
        'is_auth' => $is_auth,
        'username' => $user_name,
        'title' => 'Ошибка',
    ]);
    print $layout_content;
    die();
}


?>
