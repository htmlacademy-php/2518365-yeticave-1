<?php

/**
 * @var string $title Заголовок страницы
 * @var string[] $categories Список категорий
 */

declare(strict_types=1);

require_once 'helpers.php';
require_once 'init.php';
require_once 'models/category.php';
require_once 'models/user.php';
require_once 'validation.php';

$title = 'Вход';

$categories = get_categories($link);

$page_content = include_template('login.php', ['categories' => $categories]);

if (isset($_SESSION['user'])) {
    header("Location: /index.php");
    die();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $required = ['email', 'password'];

    $rules = [
        'email' => function ($value) {
            return validate_email($value);
        },
        'password' => function ($value) {
            return validate_length($value, 8, 64);
        }
    ];

    $form = filter_input_array(INPUT_POST, [
        'email' => FILTER_DEFAULT,
        'password' => FILTER_DEFAULT
    ], true);

    $errors = [];

    $errors = validate_value($required, $rules, $form, $errors);

    $email = mysqli_real_escape_string($link, $form['email']);
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $res = mysqli_query($link, $sql);

    $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

    if (!count($errors) && $user && !password_verify($form['password'], $user['password'])) {
        $errors['password'] = 'Неверный пароль';
    }

    $_SESSION['user'] = $user;

    if (!$user) {
        $errors['email'] = 'Такой пользователь не найден';
    }

    if (!count($errors)) {
        header("Location: /index.php");
        die();
    }
    $page_content = include_template('login.php', ['form' => $form, 'errors' => $errors]);
}


$layout_content = include_template('layout.php', [
    'title' => $title,
    'categories' => $categories,
    'page_content' => $page_content
]);

print($layout_content);
