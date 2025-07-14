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

$title = 'Все лоты';

$categories = get_categories($link);

if (!isset($_GET['id'])) {
    header('HTTP/1.0 400 Bad Request');
    die();
}

$id = (int)$_GET['id'];

$cur_page = 1;
if (isset($_GET['page'])){
    $cur_page = $_GET['page'];
}

$page_items = 9;

$items_count = count_lots_by_category($link, $cur_page);
$pages_count = ceil($items_count / $page_items);
$offset = ($cur_page - 1) * $page_items;
$pages = range(1, $pages_count);

$lots = get_lots_by_category($link, $id, $page_items, $offset);

foreach ($lots as $lot) {
    $category_name = $lot['category_name'];
};

$page_content = include_template('all-lots.php', [
    'id' => $id,
    'category_name' => $category_name,
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
