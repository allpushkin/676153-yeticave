<?php
/**
 * Функция подключает и выводит шаблон
 * @param $name - имя файла с подключаемым шаблоном
 * @param $data - массив с данными, используемыми в шаблоне
 * @return false|string - возвращает контент страницы из шаблона с данными
 */
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

/**
 * Функция форматирует цену лота (разделяет пробелом число) и добавляет знак рубля
 * @param $cost - цена лота
 * @return float|string - возвращает строку с отформатированным числом и знаком рубля
 */
function cost_formatting($cost) {
    $cost = ceil($cost);
    if ($cost >= 1000) {
        $cost = number_format($cost,0,'',' ');
    }
    $cost .= " ₽";
    return $cost;
}

/**
 * Функция рассчитывает время, оставшееся до конца действия лота
 * @param $val - дата окончания лота
 * @return false|int|string - возвращает оставшееся время в формате ЧЧ:ММ
 */
function lottime_left($val) {
    $time_left = strtotime($val) - time();
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

/**
 * Функция добавляет пользователя в базу данных, в кач-ве значений используются подготовленные выражения
 * @param $connect - ресурс соединения
 * @param $user - массив с данными о пользователе
 * @param $password - захэшированный пароль пользователя
 * @return bool - выполняет подготовленный запрос
 */
function add_user($connect, $user, $password) {
    $sql = 'INSERT INTO users (`add_date`, `email`, `username`, `password`, `avatar`, `contacts`) VALUES (NOW(), ?, ?, ?, ?, ?)';

    $stmt = db_get_prepare_stmt($connect, $sql, [$user['email'], $user['name'], $password, $user['avatar'], $user['contacts']]);
    $res = mysqli_stmt_execute($stmt);
    return $res;
}

/**
 * Функция получает id пользователя по email
 * @param $connect - ресурс соединения
 * @param $email - переменная, содержит email пользователя
 * @return bool|mysqli_result - выполняет запрос к БД и возвращает результат
 */
function get_user_by_email($connect, $email) {
    $sql = "SELECT `id` FROM users WHERE `email` = '$email'";
    $res = mysqli_query($connect, $sql);
    return $res;
}

/**
 * Функция получает все данные о пользователе по email
 * @param $connect - ресурс соединения
 * @param $email - переменная, содержит email пользователя
 * @return array|null - выполняет запрос к БД и возвращает результат в виде массива
 */
function get_user_all_by_email($connect, $email) {
    $sql = "SELECT * FROM users WHERE `email` = '$email'";
    if ($result = mysqli_query($connect, $sql)) {
        $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
        return $user;
    }
}

/**
 * Функция добавляет новый лот в базу данных, использует подготовленные выражения
 * @param $connect - ресурс соединения
 * @param $lot - массив с данными лота
 * @param $user_id - переменная, содержит id пользователя, создавшего лот
 * @return bool - выполняет подготовленный запрос
 */
function add_lot($connect, $lot, $user_id) {
    $sql = 'INSERT INTO lots (`creation_date`, `author_id`, `category_id`, `title`, `desc`, `picture`, `start_price`, `completion_date`, `step`) VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?)';

    $stmt = db_get_prepare_stmt($connect, $sql, [$user_id, $lot['category'], $lot['title'], $lot['desc'], $lot['lot_picture'], $lot['start_price'], $lot['completion_date'], $lot['step']]);
    $res = mysqli_stmt_execute($stmt);
    return $res;
}

/**
 * Функция получает список открытых лотов, отсортированных от самых новых к старым
 * @param $connect - ресурс соединения
 * @return array|null - выбирает все записи и возвращает результат в виде массива, при отсутствии результата выводит ошибку соединения с БД
 */
function get_lots($connect) {
    $sql = 'SELECT lots.`id`, lots.`title` AS `lot_title`, `start_price`, `picture`, MAX(`bet_amount`), categories.`title` AS `category_title`, `completion_date` FROM lots '
         . 'LEFT JOIN bets ON lots.id = bets.lot_id '
         . 'INNER JOIN categories ON lots.category_id = categories.id '
         . 'WHERE `winner_id` IS NULL and UNIX_TIMESTAMP(`completion_date`) > UNIX_TIMESTAMP(NOW())'
         . ' GROUP BY lots.`id` '
         . 'ORDER BY lots.`creation_date` DESC';

    if ($result = mysqli_query($connect, $sql)) {
        $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $lots;
    }
    error_show(mysqli_error($connect));
}

/**
 * Функция получает данные лота по id лота из параметра запроса
 * @param $connect - ресурс соединения
 * @param $lot_id - переменная, содержит id лота
 * @return array|null - выбирает необходимую запись, возвращает результат в виде массива, в случае отсутствия результата выводит ошибку соединения с БД
 */
function get_lot_by_id($connect, $lot_id) {
    $sql = 'SELECT lots.`id`, lots.`title` AS `lot_title`, `author_id`, `desc`, `start_price`, `picture`, MAX(`bet_amount`) AS `current_bet`, `completion_date`, categories.`title` AS `category_title`, `step` FROM lots '
         . 'LEFT JOIN bets ON lots.id = bets.lot_id '
         . 'INNER JOIN categories ON lots.category_id = categories.id '
         . 'WHERE lots.`id` =' .$lot_id;

    if ($result = mysqli_query($connect, $sql)) {
        $lot = mysqli_fetch_assoc($result);
        return $lot;
    }
    error_show(mysqli_error($connect));
}

/**
 * Функция получает список лотов, относящихся к определенной категории
 * @param $connect - ресурс соединения
 * @param $category - переменная, содержит id категории
 * @param $page_items - переменная, содержит максимальное кол-во лотов, допустимое для показа на одной странице
 * @param $offset - переменная, содержит данные о смещении для постраничной навигации
 * @return array|null - выбирает подходящие записи в соответствии с запросом, возвращает результат в виде массива, или ошибку соединения с БД
 */
function get_lots_by_category($connect, $category, $page_items, $offset) {
    $sql = 'SELECT lots.`id`, lots.`title` AS `lot_title`, `start_price`, `picture`, COUNT(`bet_amount`) AS `lot_bets`, MAX(`bet_amount`) AS `current_bet`, `category_id`, categories.`title` AS `category_title`, `completion_date` FROM lots '
         . 'LEFT JOIN bets ON lots.id = bets.lot_id '
         . 'INNER JOIN categories ON lots.category_id = categories.id '
         . 'WHERE `winner_id` IS NULL and UNIX_TIMESTAMP(`completion_date`) > UNIX_TIMESTAMP(NOW()) and `category_id` =' . $category
         . ' GROUP BY lots.`id` '
         . 'ORDER BY lots.`creation_date` DESC LIMIT ' . $page_items . ' OFFSET ' . $offset;

    if ($result = mysqli_query($connect, $sql)) {
        $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $lots;
    }
    error_show(mysqli_error($connect));
}

/**
 * Функция получает количество лотов, относящихся к определенной категории
 * @param $connect - ресурс соединения
 * @param $category - переменная, содержит id категории
 * @return mixed - возвращает кол-во найденных записей
 */
function count_lots_in_category($connect, $category) {
    $sql = 'SELECT COUNT(*) as `lots_count` FROM lots '
         . 'WHERE `winner_id` IS NULL and UNIX_TIMESTAMP(`completion_date`) > UNIX_TIMESTAMP(NOW()) and `category_id` =' .$category;

    if ($result = mysqli_query($connect, $sql)) {
        $lots_count = mysqli_fetch_assoc($result)['lots_count'];
        return $lots_count;
    }
}

/**
 * Функция получает лоты, в названии или описании которых есть совпадение с поисковым запросом
 * @param $connect - ресурс соединения
 * @param $search - переменная, содержит значение, полученное из формы поиска
 * @param $page_items - переменная, содержит максимальное кол-во лотов, допустимое для показа на одной странице
 * @param $offset - переменная, содержит данные о смещении для постраничной навигации
 * @return array|null - выбирает подходящие записи в соответствии с запросом, возвращает результат в виде массива
 */
function search_lots($connect, $search, $page_items, $offset) {
    $sql = 'SELECT lots.`id`, `picture`, categories.`title` AS `category_title`, lots.`title` AS `lot_title`, `desc`, `start_price`, COUNT(`bet_amount`) AS `lot_bets`, MAX(`bet_amount`) AS `current_bet`, `completion_date` FROM lots '
         . 'LEFT JOIN bets ON lots.`id` = bets.`lot_id` '
         . 'INNER JOIN categories ON lots.`category_id` = categories.`id` '
         . 'WHERE MATCH(lots.`title`, `desc`) AGAINST(?)'
         . 'GROUP BY lots.`id` '
         . 'ORDER BY lots.`creation_date` DESC LIMIT ' . $page_items . ' OFFSET ' . $offset;

    $stmt = db_get_prepare_stmt($connect, $sql, [$search]);
    $result = mysqli_stmt_execute($stmt);
    if ($result) {
        $res = mysqli_stmt_get_result($stmt);
        $lots = mysqli_fetch_all($res, MYSQLI_ASSOC);
        return $lots;
    }
}

/**
 * Функция получает количество лотов, попадающих под поисковый запрос
 * @param $connect - ресурс соединения
 * @param $search - переменная, содержит значение, полученное из формы поиска
 * @return mixed - возвращает кол-во найденных записей
 */
function count_lots_in_search($connect, $search) {
    $sql = 'SELECT COUNT(*) as `lots_count` FROM lots'
         . ' WHERE MATCH(lots.`title`, `desc`) AGAINST(?)';

    $stmt = db_get_prepare_stmt($connect, $sql, [$search]);
    $result = mysqli_stmt_execute($stmt);
    if ($result) {
        $res = mysqli_stmt_get_result($stmt);
        $lots_count = mysqli_fetch_assoc($res)['lots_count'];
        return $lots_count;
    }
}

/**
 * Функция добавляет ставку к лоту, сведения вносятся в базу данных
 * @param $connect - ресурс соединения
 * @param $lot - переменная, содержит id лота, к которому добавляется ставка
 * @param $bet - сумма ставки, веденная пользователем
 * @param $user_id - переменная, содержит id пользователя - автора ставки
 * @return bool - выполняет подготовленный запрос
 */
function add_bet($connect, $lot, $bet, $user_id) {
    $sql = 'INSERT INTO bets (`add_date`, `lot_id`, `user_id`, `bet_amount`) VALUES (NOW(), ?, ?, ?)';

    $stmt = db_get_prepare_stmt($connect, $sql, [$lot['id'], $user_id, $bet]);
    $res = mysqli_stmt_execute($stmt);
    return $res;
}

/**
 * Функция получает все ставки, относящиеся к определенному лоту, по id этого лота
 * @param $connect - ресурс соединения
 * @param $lot_id - переменная, содержит id лота
 * @return array|null - выбирает все подходящие записи, возвращает результат в виде массива
 */
function get_bets_by_lot_id($connect, $lot_id) {
    $sql = 'SELECT bets.`add_date`, users.`username`, `bet_amount`, `user_id` FROM bets '
         . 'JOIN users ON bets.`user_id` = users.`id` '
         . 'WHERE bets.`lot_id` =' .$lot_id
         . ' ORDER BY bets.`id` DESC';

    $result = mysqli_query($connect, $sql);
    if ($result) {
        $bets = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $bets;
    }
}

/**
 * Функция получает все ставки, созданные определенным пользователем, по id этого пользователя
 * @param $connect - ресурс соединения
 * @param $user_id - переменная, содержит id пользователя
 * @return array|null - выбирает все подходящие записи, возвращает результат в виде массива
 */
function get_bets_by_user_id($connect, $user_id) {
    $sql = 'SELECT lots.`id` AS `lot_id`, lots.`title` AS `lot_title`, lots.`picture`, categories.`title` AS `category_title`, lots.`completion_date`,`bet_amount`, bets.`add_date`, lots.`winner_id`, users.`contacts` FROM bets '
         . 'INNER JOIN lots ON bets.`lot_id` = lots.`id` '
         . 'INNER JOIN users ON lots.`author_id` = users.`id` '
         . 'INNER JOIN categories ON lots.`category_id` = categories.`id` '
         . 'WHERE bets.`user_id` =' .$user_id;

    $result = mysqli_query($connect, $sql);
    if ($result) {
        $bets = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $bets;
    }
}

/**
 * Функция высчитывает интервал времени, прошедший с даты создания ставки и выводит его в человеческом формате
 * @param $val - переменная, содержит дату добавления ставки
 * @return false|string - возвращает результат в виде строки
 */
function add_time_of_bet($val) {
    $time = strtotime('now');
    $interval = $time - strtotime($val);
    if ($interval > 86400) {
        $add_time = date('d.m.Y в H:i', strtotime($val));
    }
    else if ($interval > 3600 && $interval < 86400) {
        $add_time = floor($interval / 3600) . ' часов назад';
    }
    else if ($interval > 60 && $interval < 3600) {
        $add_time = floor($interval / 60) . ' минут назад';
    }
    else {
        $add_time = 'меньше минуты назад';
    }
    return $add_time;
}

/**
 * Функция получает список всех доступных категорий
 * @param $connect - ресурс соединения
 * @return array|null - возвращает все записи в виде массива, или выводит ошибку подключения к БД
 */
function get_categories($connect) {
    $sql = 'SELECT `id`, `title` FROM categories';
    $res = mysqli_query($connect, $sql);

    if($res) {
        $categories = mysqli_fetch_all($res, MYSQLI_ASSOC);
        return $categories;
    }
    error_show(mysqli_error($connect));
}

/**
 * Функция получает данные об определенной категории по id этой категории
 * @param $connect - ресурс соединения
 * @param $category_id - переменная, содержит id категории
 * @return array|null - возвращает данные категории в виде ассоциативного масссива
 */
function get_category($connect, $category_id) {
    $sql = 'SELECT `id`, `title` FROM categories WHERE `id`= ' .$category_id;

    $result = mysqli_query($connect, $sql);
    $category = mysqli_fetch_assoc($result);
    return $category;
}

/**
 * Функция показывает страницу с ошибкой и прекращает выполнение дальнейшего кода
 * @param $error - переменная, содержит данные об ошибке
 */
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

/**
 * Функция выводит на экран страницу 404 и прекращает выполнение дальнейшего кода
 */
function error404_show() {
    $search = "";
    $page_content = include_template('404.php', []);
    $layout_content = include_template('error_layout.php', [
        'content' => $page_content,
        'title' => 'Ошибка',
        'search' => $search
    ]);
    print $layout_content;
    die();
}

/**
 *  Функция выводит на экран страницу 403 и прекращает выполнение дальнейшего кода
 */
function error403_show() {
    $search = "";
    $page_content = include_template('403.php', []);
    $layout_content = include_template('error_layout.php', [
        'content' => $page_content,
        'title' => 'Ошибка',
        'search' => $search
    ]);
    print $layout_content;
    die();
}

/**
 * Функция создает подготовленные выражения на основе готового sql запроса и данных
 * @param $link - ресурс соединения
 * @param $sql  - SQL запрос с плейсхолдерами
 * @param array $data - Данные, вставляющиеся на место плейсхолдеров
 * @return bool|mysqli_stmt - возвращает подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);
    if ($data) {
        $types = '';
        $stmt_data = [];
        foreach ($data as $value) {
            $type = null;
            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }
            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }
        $values = array_merge([$stmt, $types], $stmt_data);
        $func = 'mysqli_stmt_bind_param';
        $func(...$values);
    }
    return $stmt;
}

?>
