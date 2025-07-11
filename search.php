<?php

/**
 * @var string[] $categories Список категорий
 * @var array<int,array{name: string, category: string, price: int, img: string} $lots Список лотов
 */

declare(strict_types=1);

require_once 'helpers.php';
require_once 'init.php';
require_once 'models/category.php';
require_once 'models/lot.php';

$title = 'Результаты поиска';

$categories = get_categories($link);

if (empty($_GET['search'])) {
    header('HTTP/1.0 400 Bad Request');
    die();
}

$search = trim($_GET['search']);

if ($search === '') {
    header('HTTP/1.0 400 Bad Request');
    die();
}

$cur_page = 1;
if (isset($_GET['page'])){
    $cur_page = $_GET['page'];
}

$page_items = 9;

$result = mysqli_query($link, "SELECT COUNT(*) as cnt FROM lots");
$items_count = mysqli_fetch_assoc($result)['cnt'];
$pages_count = ceil($items_count / $page_items);
$offset = ($cur_page - 1) * $page_items;
$pages = range(1, $pages_count);

$lots = search_lot($link, $search, $page_items, $offset);

$page_content = include_template('search.php', [
    'search' => $search,
    'categories' => $categories,
    'lots' => $lots,
    'pages' => $pages,
    'pages_count' => $pages_count,
    'cur_page' => $cur_page
    ]);

$layout_content = include_template('layout.php', [
    'title' => $title,
    'categories' => $categories,
    'page_content' => $page_content
    ]);

print($layout_content);
