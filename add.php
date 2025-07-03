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

$title = 'Добавление лота';
$is_auth = rand(0, 1);
$user_name = 'Алексей';

$categories = get_categories($link);
$categories_ids = [];
$categories_ids = array_column($categories, 'id');
var_dump($categories_ids);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $required = ['name', 'description', 'start_price', 'bet_step', 'date_end', 'category_id'];
	$errors = [];

    $rules = [
        'category_id' => function($value) use ($categories_ids) {
            return validate_category($value, $categories_ids);
        },
        'name' => function($value) {
            return validate_length($value, 8, 128);
        },
        'description' => function($value) {
            return validate_length($value, 8, 512);
        },
        'start_price' => function($value) {
            return validate_integer($value);
        },
        'bet_step' => function($value) {
            return validate_integer($value);
        },
        'date_end' => function($value) {
            return validate_date($value);
        }
    ];

    $lot = filter_input_array(INPUT_POST, [
        'name' => FILTER_DEFAULT,
        'description' => FILTER_DEFAULT,
        'start_price' => FILTER_DEFAULT,
        'bet_step' => FILTER_DEFAULT,
        'date_end' => FILTER_DEFAULT,
        'category_id' => FILTER_DEFAULT], true);

    foreach ($lot as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }

        if (in_array($key, $required) && empty($value)) {
            $errors[$key] = "Поле $key надо заполнить";
        }
    }

    $errors = array_filter($errors);

    if (!empty($_FILES['img']['name'])) {
		$tmp_name = $_FILES['img']['tmp_name'];
        $file_type = mime_content_type($tmp_name);

        if ($file_type === 'image/jpeg' || $file_type === 'image/png') {
			$img = $_FILES['img']['name'];
            $extension = pathinfo($img, PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $extension;
            move_uploaded_file($tmp_name, 'uploads/' . $filename);
			$lot['img'] = $filename;
		}
        else {
			$errors['file'] = 'Загрузите картинку в формате PNG, IPG или JPEG';
		}
	}
    	else {
		$errors['file'] = 'Вы не загрузили файл';
	}
    if (count($errors)) {
		$page_content = include_template('add.php', ['lot' => $lot, 'errors' => $errors, 'categories' => $categories]);
	}
    else {
        $sql = 'INSERT INTO lots (name, category_id, description, img, start_price, date_end, bet_step, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, 1)';
        var_dump($lot);
        $stmt = db_get_prepare_stmt($link, $sql, $lot);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            $lot_id = mysqli_insert_id($link);

            header("Location: lot.php?id=" . $lot_id);
        }
        else {
            die (mysqli_error($link));
        }
	}
}
else {
	$page_content = include_template('add.php', ['categories' => $categories]);
}

$layout_content = include_template('layout.php', [
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'page_content' => $page_content
]);

print($layout_content);
