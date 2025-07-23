<?php

/**
 * @var bool $is_auth Флаг авторизации
 * @var string $user_name Имя пользователя
 * @var string[] $categories Список категорий
 * @var array<int,array{name: string, category: string, price: int, img: string} $lots Список лотов
 */

declare(strict_types=1);

require_once 'helpers.php';
require_once 'init.php';
require_once 'models/category.php';
require_once 'models/lot.php';
require_once 'validation.php';

if (!isset($_SESSION['user'])) {
    header('HTTP/1.0 403 Forbidden');
    die();
}

$title = 'Добавление лота';

$categories = get_categories($link);
$categories_ids = array_column($categories, 'id');

$page_content = include_template('add.php', ['categories' => $categories]);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    $layout_content = include_template('layout.php', [
        'title' => $title,
        'categories' => $categories,
        'page_content' => $page_content
    ]);

    print($layout_content);
    die();

}

$required = ['name', 'description', 'start_price', 'bet_step', 'date_end', 'category_id'];

$rules = [
    'category_id' => function ($value) use ($categories_ids) {
        return validate_category($value, $categories_ids);
    },
    'name' => function ($value) {
        return validate_length($value, 8, 128);
    },
    'description' => function ($value) {
        return validate_length($value, 8, 512);
    },
    'start_price' => function ($value) {
        return validate_integer($value);
    },
    'bet_step' => function ($value) {
        return validate_integer($value);
    },
    'date_end' => function ($value) {
        return validate_date($value);
    }
];

$lot = filter_input_array(INPUT_POST, [
    'name' => FILTER_DEFAULT,
    'description' => FILTER_DEFAULT,
    'start_price' => FILTER_DEFAULT,
    'bet_step' => FILTER_DEFAULT,
    'date_end' => FILTER_DEFAULT,
    'category_id' => FILTER_DEFAULT
], true);

$errors = [];

$errors = validate_value($required, $rules, $lot, $errors);

if (empty($_FILES['img']['name'])) {
    $errors['file'] = 'Вы не загрузили файл';
} else {
    $tmp_name = $_FILES['img']['tmp_name'];
    $file_type = mime_content_type($tmp_name);

    if ($file_type !== 'image/jpeg' && $file_type !== 'image/png') {
        $errors['file'] = 'Загрузите картинку в формате PNG, JPG или JPEG';
    }
}

if (!isset($errors['file'])) {
    $img = $_FILES['img']['name'];
    $extension = pathinfo($img, PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $extension;
    move_uploaded_file($tmp_name, 'uploads/' . $filename);
    $lot['img'] = 'uploads/' . $filename;
}

if (!count($errors)) {
    add_lot($link, $lot);
}

$page_content = include_template('add.php', ['lot' => $lot, 'errors' => $errors, 'categories' => $categories]);

$layout_content = include_template('layout.php', [
    'title' => $title,
    'categories' => $categories,
    'page_content' => $page_content
]);

print($layout_content);
die();

