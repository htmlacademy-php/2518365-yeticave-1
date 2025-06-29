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
        WHERE l.id = $id
    QUERY;

    return get_arr($link, $sql);
}
