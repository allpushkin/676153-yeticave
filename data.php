<?php
$database = [
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
    'db' => 'yeticave'
];
$is_auth = rand(0, 1);
$user_name = 'Natalia'; // укажите здесь ваше имя
$user_avatar = 'img/user.jpg';
$categories = ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'];
$products_list = [
    [
        'title' => '2014 Rossignol District Snowboard',
        'category' => 'Доски и лыжи',
        'cost' => 10999,
        'image' => 'img/lot-1.jpg'
    ],
    [
        'title' => 'DC Ply Mens 2016/2017 Snowboard',
        'category' => 'Доски и лыжи',
        'cost' => 159999,
        'image' => 'img/lot-2.jpg'
    ],
    [
        'title' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'category' => 'Крепления',
        'cost' => 8000,
        'image' => 'img/lot-3.jpg'
    ],
    [
        'title' => 'Ботинки для сноуборда DC Mutiny Charocal',
        'category' => 'Ботинки',
        'cost' => 10999,
        'image' => 'img/lot-4.jpg'
    ],
    [
        'title' => 'Куртка для сноуборда DC Mutiny Charocal',
        'category' => 'Одежда',
        'cost' => 7500,
        'image' => 'img/lot-5.jpg'
    ],
    [
        'title' => 'Маска Oakley Canopy',
        'category' => 'Разное',
        'cost' => 5400,
        'image' => 'img/lot-6.jpg'
    ]
];
?>
