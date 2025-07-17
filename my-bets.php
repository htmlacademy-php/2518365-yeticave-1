<?php

/**
 * @var string $title Заголовок страницы
 * @var string[] $categories Список категорий
 */

declare(strict_types=1);

require_once 'helpers.php';
require_once 'init.php';
require_once 'models/category.php';
require_once 'models/lot.php';
require_once 'models/user.php';
require_once 'models/bet.php';

if (!isset($_SESSION['user'])) {
    header('HTTP/1.0 403 Forbidden');
    die();
}

$user_id = (int)($_SESSION['user']['id'] ?? 0);

$title = 'Мои ставки';

$categories = get_categories($link);

$bets = get_bets($link, $user_id);

$page_content = include_template('my-bets.php', ['user_id' => $user_id, 'bets' => $bets, 'categories' => $categories]);


$layout_content = include_template('layout.php', [
    'title' => $title,
    'categories' => $categories,
    'page_content' => $page_content
]);

print($layout_content);
