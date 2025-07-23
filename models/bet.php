<?php

declare(strict_types=1);

require_once 'helpers.php';
require_once 'init.php';

/**
 * Добавляет ставку
 *
 * @param $link mysqli Ресурс соединения
 * @param int $price Значение ставки
 * @param int $user_id Значение ID, того кто сделал ставку
 * @param int $lot_id Значение ID лота
 * @return Добавленная ставка в БД
 */
function add_bet($link, $price, $user_id, $lot_id)
{
    $sql = <<<QUERY
        INSERT INTO bets (price, user_id, lot_id)
        VALUES (?, ?, ?)
    QUERY;

    $stmt = db_get_prepare_stmt($link, $sql, [$price, $user_id, $lot_id]);
    $res = mysqli_stmt_execute($stmt);

    if ($res) {
        header("Refresh: 0");
        exit;
    }
    die (mysqli_error($link));
}

/**
 * Подсчитывает кол-во ставок для лота
 *
 * @param $link mysqli Ресурс соединения
 * @param int $lot_id Значение ID лота
 * @return int Кол-во ставок
 */
function count_bets($link, $lot_id)
{
    $sql = <<<QUERY
        SELECT COUNT(*) as cnt FROM bets b
        JOIN lots l ON b.lot_id = l.id
        WHERE b.lot_id = ?
    QUERY;

    $stmt = db_get_prepare_stmt($link, $sql, [$lot_id]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($res)['cnt'];
}

/**
 * Обновляет текущую цену лота
 *
 * @param $link mysqli Ресурс соединения
 * @param int $price Значение ставки
 * @param int $lot_id Значение ID лота
 * @return int Обновленная цена лота
 */
function update_price($link, $price, $lot_id)
{
    $sql = <<<QUERY
        UPDATE lots SET start_price = ? WHERE id = ?;
    QUERY;

    $stmt = db_get_prepare_stmt($link, $sql, [$price, $lot_id]);
    $res = mysqli_stmt_execute($stmt);
    if ($res) {
        header("Refresh: 0");
        exit;
    }
    die (mysqli_error($link));
}

/**
 * Показывает историю ставок для лота
 *
 * @param $link mysqli Ресурс соединения
 * @param int $lot_id Значение ID лота
 * @return array Список историй ставок
 */
function show_bets($link, $lot_id)
{
    $sql = <<<QUERY
        SELECT u.name, b.* FROM bets b
        JOIN users u ON b.user_id = u.id
        JOIN lots l ON b.lot_id = l.id
        WHERE b.lot_id = ?
        ORDER BY b.created_at DESC
    QUERY;

    $stmt = db_get_prepare_stmt($link, $sql, [$lot_id]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

/**
 * Показывает историю ставок для конкретного пользователя
 *
 * @param $link mysqli Ресурс соединения
 * @param int $user_id Значение ID пользователя
 * @return array Список ставок пользователя
 */
function get_bets($link, $user_id)
{
    $sql = <<<QUERY
        SELECT l.name as lot_name, l.start_price, l.date_end, l.img, l.winner_id, u.message, c.name as category_name, b.created_at, b.lot_id FROM bets b
        JOIN lots l ON l.id = b.lot_id
        JOIN categories c ON l.category_id = c.id
        JOIN users u ON l.user_id = u.id
        WHERE b.user_id = ?
        ORDER BY b.created_at DESC
    QUERY;

    $stmt = db_get_prepare_stmt($link, $sql, [$user_id]);
    $res = mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

/**
 * Возвращает сделанные последние ставки
 *
 * @param $link mysqli Ресурс соединения
 * @param int $id ID лота
 * @return array $bets Массив ставок
 */
function get_user_by_bet($link, $id)
{
    $sql = <<<QUERY
        SELECT * FROM bets WHERE lot_id= ? ORDER BY created_at DESC LIMIT 1
    QUERY;
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $res = mysqli_stmt_execute($stmt);
    if ($res) {
        return $bets = get_arrow($res);
    }
    die (mysqli_error($link));
}

/**
 * Возвращает победителей
 *
 * @param $link mysqli Ресурс соединения
 * @param int $id ID лота
 * @return array $winners Массив победителей
 */
function get_winners($link, $lot_id, $winner_id)
{
    $sql = <<<QUERY
        SELECT l.id, l.name as lot_name, u.name as user_name, u.email FROM bets b
        JOIN lots l ON b.lot_id = l.id
        JOIN users u ON b.user_id = u.id
        WHERE l.id = ? AND users.id = ?
    QUERY;
    $stmt = db_get_prepare_stmt($link, $sql, [$lot_id, $winner_id]);
    $res = mysqli_stmt_execute($stmt);
    if ($res) {
        return $winners = get_arrow($res);
    }
    die (mysqli_error($link));
}
