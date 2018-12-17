<?php
require_once('functions.php');
require_once('init.php');

$categories = get_categories($connect);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['user'];

    $required = [
        'email',
        'password',
        'name',
        'contacts'
    ];
    $dict = [
        'email' => 'Электронный адрес',
        'password' => 'Пароль',
        'name' => 'Имя',
        'contacts' => 'Контактные данные',
        'avatar' => 'Аватар'
    ];
    $errors = [];
    foreach ($required as $key) {
        if (empty($user[$key])) {
            $errors[$key] = 'Поле ' . $key . ' не заполнено';
        };
    };

    if (!filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Данный email некорректен';
    } else {
        $email = mysqli_real_escape_string($connect, $user['email']);

        if (mysqli_num_rows(get_user_by_email($connect, $email)) > 0) {
            $errors['email'] = 'Пользователь с таким email уже зарегистрирован';
        }
    }

    if (isset($_FILES['avatar']['name']) && !empty($_FILES['avatar']['tmp_name'])) {
        $tmp_name = $_FILES['avatar']['tmp_name'];
        $file_name = uniqid() . '.jpg';

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);
        if ($file_type !== "image/jpeg" && $file_type !== "image/png" && $file_type !== "image/jpg") {
            $errors['avatar'] = 'Загрузите изображение в формате JPG или PNG';
        }
    } else {
        $user['avatar'] = 'NULL';
    }

    if (empty($errors)) {
        if (!empty($_FILES['avatar']['tmp_name'])) {
            move_uploaded_file($tmp_name, 'img/' . $file_name);
            $user['avatar'] = 'img/' . $file_name;
        }

        $password = password_hash($user['password'], PASSWORD_DEFAULT);

        if (add_user($connect, $user, $password) && empty($errors)) {
            header("Location: /login.php");
            exit();
        }
    }
}

$page_content = include_template('registration.php', [
    'user' => $user,
    'errors' => $errors,
    'dict' => $dict
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Регистрация пользователя',
    'categories' => $categories
]);

print($layout_content);
?>
