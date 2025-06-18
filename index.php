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

$title = 'Главная';
$is_auth = rand(0, 1);
$user_name = 'Алексей';

if (!$link) {
    $page_content = include_template('error.php', ['error' => mysqli_connect_error()]);
}
$sql = 'SELECT * FROM categories';
if (!(mysqli_query($link, $sql))) {
    $page_content = include_template('error.php', ['error' => mysqli_error($link)]);
}
$categories = mysqli_fetch_all(mysqli_query($link, $sql), MYSQLI_ASSOC);
$sql = 'SELECT l.name, l.start_price, l.img, l.date_end, b.price, c.name as category_name FROM lots l '
     . 'JOIN categories c ON l.category_id = c.id '
     . 'LEFT JOIN bets b ON l.id = b.lot_id '
     . 'ORDER BY l.created_at DESC LIMIT 6';
if (!(mysqli_query($link, $sql))) {
    $page_content = include_template('error.php', ['error' => mysqli_error($link)]);
}
$lots = mysqli_fetch_all(mysqli_query($link, $sql), MYSQLI_ASSOC);

$page_content = include_template('main.php', [
    'categories' => $categories,
    'lots' => $lots
]);

$layout_content = include_template('layout.php', [
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'page_content' => $page_content
]);

print($layout_content);
