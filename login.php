<?php
require_once('functions.php');
require_once('data.php');
require_once('init.php');

session_start();

$categories = get_categories($connect);

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $enter = $_POST['enter'];

    $required = [
        'email',
        'password'
    ];
    $dict = [
        'email' => 'Электронный адрес',
        'password' => 'Пароль'
    ];
    $errors = [];
    foreach ($required as $key) {
        if (empty($enter[$key])) {
            $errors[$key] = 'Поле ' . $key . ' не заполнено';
        };
    };


}

$page_content = include_template('login.php', [
    'enter' => $enter,
    'errors' => $errors,
    'dict' => $dict
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'is_auth' => $is_auth,
    'username' => $user_name,
    'title' => 'Вход на сайт',
    'categories' => $categories
]);

print($layout_content);
?>
