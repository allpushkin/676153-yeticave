<?php
require_once('functions.php');
require_once('init.php');

session_start();

$dict = [];
$errors = [];
$lot = [];
$search = '';

if (isset($_SESSION['user'])) {
    $is_auth = $_SESSION['user'];
    $user_id = $is_auth['id'];
} else {
    http_response_code(403);
    error403_show();
    die();
}



$categories = get_categories($connect);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lot = $_POST['lot'];

    $required = [
        'title',
        'category',
        'desc',
        'start_price',
        'step',
        'completion_date'
    ];
    $dict = [
        'title' => 'Название лота',
        'category' => 'Категория лота',
        'desc' => 'Описание лота',
        'lot_picture' => 'Изображение',
        'start_price' => 'Начальная цена',
        'step' => 'Шаг ставки',
        'completion_date' => 'Дата завершения торгов'
    ];
    foreach ($required as $key) {
        if (empty($lot[$key])) {
            $errors[$key] = 'Это поле надо заполнить';
        };
    };

    if (!is_numeric($lot['start_price']) || $lot['start_price'] <= 0) {
        $errors['start_price'] = 'Поле заполнено некорректно. Здесь должно быть целое положительное число';
    }
    if (!is_numeric($lot['step']) || $lot['step'] <= 0) {
        $errors['step'] = 'Поле заполнено некорректно. Здесь должно быть целое положительное число';
    }

    if (strtotime($lot['completion_date']) <= strtotime('now')) {
        $errors['completion_date'] = 'Дата завершения торгов должна быть больше текущей даты хотя бы на 1 день';
    }

    if ($lot['category'] === 'Выберите категорию') {
        $errors['category'] = 'Выберите, пожалуйста, категорию';
    }

    if (!isset($lot['category'])) {
        $errors['category'] = 'Выберите, корректную категорию';
    }

    if (isset($_FILES['lot_picture']['name']) && !empty($_FILES['lot_picture']['tmp_name'])) {
        $tmp_name = $_FILES['lot_picture']['tmp_name'];
        $file_name = uniqid() . '.jpg';

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);
        if ($file_type !== 'image/jpeg' && $file_type !== 'image/png' && $file_type !== 'image/jpg') {
            $errors['lot_picture'] = 'Загрузите изображение в формате JPG или PNG';
        }
    }
    else {
        $errors['lot_picture'] = 'Вы не загрузили изображение';
    }

    if (empty($errors)) {
        move_uploaded_file($tmp_name, 'img/' . $file_name);
        $lot['lot_picture'] = 'img/' . $file_name;

        if (add_lot($connect, $lot, $user_id)) {
            $lot_id = mysqli_insert_id($connect);
            header('Location: lot.php?id=' . $lot_id);
        } else {
            error_show(mysqli_error($connect));
        }
    }

};
$page_content = include_template('add_lot.php', [
    'lot' => $lot,
    'errors' => $errors,
    'dict' => $dict,
    'categories' => $categories
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'is_auth' => $is_auth,
    'search' => $search,
    'title' => 'Добавление лота',
    'categories' => $categories
]);

print($layout_content);
?>
