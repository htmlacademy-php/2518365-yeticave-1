<?php

/**
 * @var bool $is_auth Флаг авторизации
 * @var string $user_name Имя пользователя
 * @var string[] $categories Список категорий
 */

declare(strict_types=1);

require_once 'helpers.php';
require_once 'init.php';
require_once 'models/category.php';
require_once 'models/user.php';
require_once 'validation.php';

if(isset($_SESSION['user'])){
    header('HTTP/1.0 403 Forbidden');
    die();
}

$title = 'Регистрация';

$categories = get_categories($link);

$page_content = include_template('sign-up.php', ['categories' => $categories]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $required = ['email', 'password', 'name', 'message'];

    $rules = [
        'email' => function($value) {
            return validate_email($value);
        },
        'password' => function($value) {
            return validate_length($value, 8, 64);
        },
        'name' => function($value) {
            return validate_length($value, 8, 128);
        },
        'message' => function($value) {
            return validate_length($value, 8, 128);
        }
    ];

    $form = filter_input_array(INPUT_POST, [
        'email' => FILTER_DEFAULT,
        'password' => FILTER_DEFAULT,
        'name' => FILTER_DEFAULT,
        'message' => FILTER_DEFAULT], true);

    $errors = [];

    $errors = validate_value($required, $rules, $form, $errors);

    if (!count($errors)) {
        $email = mysqli_real_escape_string($link, $form['email']);
        $sql = "SELECT id FROM users WHERE email = '$email'";
        $res = mysqli_query($link, $sql);

        if (mysqli_num_rows($res) > 0) {
            $errors[] = 'Пользователь с этим email уже зарегистрирован';
        }

        if (empty($errors)) {
            $form['password'] = password_hash($form['password'], PASSWORD_DEFAULT);
            add_user($link, $form);
        }
    }

    $page_content = include_template('sign-up.php', ['form' => $form, 'errors' => $errors, 'categories' => $categories]);
}

$layout_content = include_template('layout.php', [
    'title' => $title,
    'categories' => $categories,
    'page_content' => $page_content
]);

print($layout_content);
