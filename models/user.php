<?php

declare(strict_types=1);

require_once 'helpers.php';
require_once 'init.php';

/**
 * Добавляет нового пользователя
 *
 * @param $link mysqli Ресурс соединения
 * @param array $user Данные пользователя
 * @return string Переадресация на страницу входа
 */
function add_user($link, $user)
{
    $sql = <<<QUERY
        INSERT INTO users (email, password, name, message)
        VALUES (?, ?, ?, ?)
    QUERY;

    $stmt = db_get_prepare_stmt($link, $sql, $user);
    $res = mysqli_stmt_execute($stmt);

    if ($res) {
        header("Location: /login.php");
        exit;
    }
    die (mysqli_error($link));
}
