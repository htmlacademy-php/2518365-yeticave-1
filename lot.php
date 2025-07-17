<?php

/**
 * @var string $title Заголовок страницы
 * @var string[] $categories Список категорий
 * @var array<int,array{name: string, category: string, price: int, img: string} $lots Список лотов
 */

declare(strict_types=1);

require_once 'helpers.php';
require_once 'init.php';
require_once 'models/category.php';
require_once 'models/lot.php';
require_once 'models/user.php';
require_once 'models/bet.php';
require_once 'validation.php';

$title = 'Лот';

$categories = get_categories($link);

if (!isset($_GET['id'])) {
    $page_content = include_template('404.php', ['categories' => $categories]);
    $layout_content = include_template('layout.php', [
        'title' => $title,
        'categories' => $categories,
        'page_content' => $page_content
    ]);
    print($layout_content);
    die();
}

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$lots = get_lot_by_id($link, $id);
if (empty($lots)) {
    $page_content = include_template('404.php', ['categories' => $categories]);
    $layout_content = include_template('layout.php', [
        'title' => $title,
        'categories' => $categories,
        'page_content' => $page_content
    ]);
    print($layout_content);
    die();
}

$user_id = (int)($_SESSION['user']['id'] ?? 0);

foreach ($lots as $lot) {
    $start_price = $lot['start_price'];
    $bet_step = $lot['bet_step'];
    $lot_id = $lot['id'];
    $user_lot_id = $lot['user_id'];
};

$user_id_last_bet = get_user_by_bet($link, $lot_id);

$count_bets = count_bets($link, $lot_id);

$bets = show_bets($link, $lot_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['price'])) {
        $price = $_POST['price'];
    }
    $required = ['price'];

    $rules = [
        'price' => function ($value) use ($start_price, $bet_step) {
            return validate_bet($value, $start_price, $bet_step);
        }
    ];

    $form = filter_input_array(INPUT_POST, [
        'price' => FILTER_DEFAULT
    ], true);

    $errors = [];

    $errors = validate_value($required, $rules, $form, $errors);

    if (!count($errors)) {
        add_bet($link, $price, $user_id, $lot_id);
        update_price($link, $price, $lot_id);
    }

    $page_content = include_template('lot.php', [
        'lots' => $lots,
        'form' => $form,
        'errors' => $errors,
        'user_id' => $user_id,
        'user_lot_id' => $user_lot_id,
        'user_id_last_bet' => $user_id_last_bet,
        'count_bets' => $count_bets,
        'bets' => $bets,
        'categories' => $categories
    ]);
    $layout_content = include_template('layout.php', [
        'title' => $title,
        'categories' => $categories,
        'page_content' => $page_content
    ]);
    print($layout_content);

}

$page_content = include_template('lot.php', [
    'count_bets' => $count_bets,
    'bets' => $bets,
    'user_id' => $user_id,
    'user_lot_id' => $user_lot_id,
    'user_id_last_bet' => $user_id_last_bet,
    'categories' => $categories,
    'lots' => $lots
]);
$layout_content = include_template('layout.php', [
    'title' => $title,
    'categories' => $categories,
    'page_content' => $page_content
]);
print($layout_content);

