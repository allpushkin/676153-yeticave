<?php
require_once('functions.php');
require_once('data.php');
require_once('init.php');

$categories = get_categories($connect);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lot = $_POST['lot'];

    $required = [
        'title',
        'category',
        'description',
        'start_price',
        'step',
        'completion_date'
    ];
    $dict = [
        'title' => 'Название лота',
        'category' => 'Категория лота',
        'description' => 'Описание лота',
        'lot_picture' => 'Изображение',
        'start_price' => 'Начальная цена',
        'step' => 'Шаг ставки',
        'completion_date' => 'Дата завершения торгов'
    ];
    $errors = [];
    foreach ($required as $key) {
        if (empty($_POST[$key])) {
            $errors[$key] = 'Это поле надо заполнить';
        };
    };

    if (isset($_FILES['lot_picture']['name'])) {
        $tmp_name = $_FILES['lot_picture']['tmp_name'];
        $path = $_FILES['lot_picture']['name'];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);
        if ($file_type !== "image/jpeg" || $file_type !== "image/png") {
            $errors['lot_picture'] = 'Загрузите изображение в формате JPG или PNG';
        }
        else {
            move_uploaded_file($tmp_name, 'img/' . $path);
            $lot['picture'] = $path;
        }
    }
    else {
        $errors['lot_picture'] = 'Вы не загрузили изображение';
    }

    if (count($errors)) {
        $page_content = include_template('add_lot.php', [
            'lot' => $lot,
            'errors' => $errors,
            'dict' => $dict,
            'categories' => $categories
        ]);
        $layout_content = include_template('layout.php', [
            'content' => $page_content,
            'is_auth' => $is_auth,
            'username' => $user_name,
            'title' => 'Добавление лота',
            'categories' => $categories
        ]);

        print($layout_content);
    }
    else {
        if (add_lot($connect)) {
            $lot_id = mysqli_insert_id($connect);

            header("Location: lot.php?id=" . $lot_id);
        }
        else {
            error_show(mysqli_error($connect));
        }
    }
}
else {
    $page_content = include_template('add_lot.php', [
        'categories' => $categories
    ]);
    $layout_content = include_template('layout.php', [
        'content' => $page_content,
        'is_auth' => $is_auth,
        'username' => $user_name,
        'title' => 'Добавление лота',
        'categories' => $categories
    ]);

    print($layout_content);
};

?>
