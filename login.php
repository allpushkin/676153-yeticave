<?php
require_once('functions.php');
require_once('init.php');

session_start();

$errors = [];
$dict = [];
$user = [];
$enter = [];
$search = '';

if (isset($_SESSION['user'])) {
    $is_auth = $_SESSION['user'];
}  else {
    $is_auth = [];
}

$categories = get_categories($connect);

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
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
        }
    }

    if (!empty($enter['email'])) {
        $email = mysqli_real_escape_string($connect, $enter['email']);
        $user = get_user_all_by_email($connect, $email);

        if (!$user) {
            $errors['email'] = 'Пользователь с таким email не найден';
        }
    }

    if (empty($errors) && $user) {
        if (password_verify($enter['password'], $user['password'])) {
            $_SESSION['user'] = $user;

        }
        else {
            $errors['password'] = 'Вы ввели неверный пароль';
        }
    }
    if (!empty($_SESSION['user'])) {
        header('Location: /index.php');
        exit();
    }
}

$page_content = include_template('login.php', [
    'enter' => $enter,
    'errors' => $errors,
    'dict' => $dict
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'is_auth' => $is_auth,
    'search' => $search,
    'title' => 'Вход на сайт',
    'categories' => $categories
]);

print($layout_content);

