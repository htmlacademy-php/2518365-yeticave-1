<?php

declare(strict_types=1);

/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date) : bool {
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form (int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = []) {
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * Форматирует сумму и добавлениет к ней знака рубля
 *
 * @param int $price Неотформатированная сумма
 * @return string Отформатированная сумма со знаком рубля
 */
function get_price(int $price): string
{
    if ($price >= 1000){
    return number_format($price, 0, '.', ' ') . ' ₽';
    }
    return $price . ' ₽';
}

/**
 * Принимает дату в формате ГГГГ-ММ-ДД и возвращает массив,
 * где первый элемент — целое количество часов до даты, а второй — остаток в минутах
 * @param string $date Дата
 * @return array Массив из двух строковых переменных, часов и минут
 */
function get_dt_range(string $date): array
{
    date_default_timezone_set('Europe/Moscow');
    $expiryDate = DateTime::createFromFormat('Y-m-d', $date);
    $expiryDate->setTime(23, 59, 59);
    $currentDate = new DateTime();
    $dt_range = $currentDate->diff($expiryDate);

    $hours = 0;
    $minutes = 0;

    if (!$dt_range->invert) {
        $hours = $dt_range->days * 24 + $dt_range->h;
        $minutes = $dt_range->i;
    }

    $hours = str_pad("$hours", 2, "0", STR_PAD_LEFT);
    $minutes = str_pad("$minutes", 2, "0", STR_PAD_LEFT);

    return [$hours, $minutes];
}

/**
 * Выполняет SQL запрос
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 *
 * @return array Данные в формате ассоциативного массива
 */
function get_arr($link, $sql): array
{
    $result = mysqli_query($link, $sql);
    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    return mysqli_error($link);
}

/**
 * Возвращает человеческое представление времени с момента $datetime.
 *
 * @param string $datetime Время в формате 'Y-m-d H:i:s'
 * @return string
 */
function time_ago($datetime) {
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;

    if ($diff < 60) {
        // Меньше минуты
        $seconds = $diff;
        return $seconds . ' ' . get_noun_plural_form((int)$seconds, 'секунда', 'секунды', 'секунд') . ' назад';
    } elseif ($diff < 3600) {
        // Менее часа
        $minutes = floor($diff / 60);
        return $minutes . ' ' . get_noun_plural_form((int)$minutes, 'минута', 'минуты', 'минут') . ' назад';
    } elseif ($diff < 86400) {
        // Менее суток
        $hours = floor($diff / 3600);
        return $hours . ' ' . get_noun_plural_form((int)$hours, 'час', 'часа', 'часов') . ' назад';
    } else {
        // Больше суток
        $days = floor($diff / 86400);
        return $days . ' ' . get_noun_plural_form((int)$days, 'день', 'дня', 'дней') . ' назад';
    }
}
