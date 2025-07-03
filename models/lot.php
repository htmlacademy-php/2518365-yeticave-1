<?php

declare(strict_types=1);

require_once 'helpers.php';
require_once 'init.php';

/**
 * Возвращает список лотов
 *
 * @param $link mysqli Ресурс соединения
 * @return array Список новых лотов в формате ассоциативного массива
 */
function get_new_lots($link): array
{
    $sql = <<<QUERY
        SELECT l.id, l.name, l.start_price, l.img, l.date_end, b.price, c.name as category_name FROM lots l
        JOIN categories c ON l.category_id = c.id
        LEFT JOIN bets b ON l.id = b.lot_id
        ORDER BY l.created_at DESC LIMIT 6
    QUERY;

    return get_arr($link, $sql);
}

/**
 * Возвращает список лотов по ID
 *
 * @param $link mysqli Ресурс соединения
 * @return array Список лотов по ID в формате ассоциативного массива
 */
function get_lot_by_id($link, $id): array
{
    $sql = <<<QUERY
        SELECT l.*, c.name as category_name FROM lots l
        JOIN categories c ON l.category_id = c.id
        WHERE l.id = ?
    QUERY;
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

/**
 * Добавляет лот и ID к нему
 *
 * @param $link mysqli Ресурс соединения
 * @param array $lot Лот
 * @return $lot_id ID для нового лота
 */
function add_lot($link, $lot) {
    $sql = <<<QUERY
        INSERT INTO lots (name, description, start_price, bet_step, date_end, category_id, img, user_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, 1)
    QUERY;

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
