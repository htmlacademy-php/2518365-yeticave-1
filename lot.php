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

$title = 'Лот';
$is_auth = rand(0, 1);
$user_name = 'Алексей';

$categories = get_categories($link);

if (!isset($_GET['id'])) {
  $page_content = include_template('404.php', ['categories' => $categories]);
  $layout_content = include_template('layout.php', [
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'page_content' => $page_content
]);
    print($layout_content);
    die();
}
else {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        if ($id !== null && $id !== false) {
            $lots = get_lot_by_id($link, $id);
            if (empty($lots)) {
                $page_content = include_template('404.php', ['categories' => $categories]);
                $layout_content = include_template('layout.php', [
                'title' => $title,
                'is_auth' => $is_auth,
                'user_name' => $user_name,
                'categories' => $categories,
                'page_content' => $page_content
                ]);
                print($layout_content);
                die();
            }
            else {
                $page_content = include_template('lot.php', ['categories' => $categories, 'lots' => $lots]);
                $layout_content = include_template('layout.php', [
                'title' => $title,
                'is_auth' => $is_auth,
                'user_name' => $user_name,
                'categories' => $categories,
                'page_content' => $page_content
                ]);
                print($layout_content);
                die();
            }
    }
}
