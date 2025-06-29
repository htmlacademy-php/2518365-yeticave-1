<?php

declare(strict_types=1);

require_once 'helpers.php';
require_once 'init.php';

/**
 * Возвращает список категорий
 *
 * @param $link mysqli Ресурс соединения
 * @return array Список категорий в формате ассоциативного массива
 */
function get_categories($link): array
{
    $sql = 'SELECT * FROM categories';
    return get_arr($link, $sql);
}
