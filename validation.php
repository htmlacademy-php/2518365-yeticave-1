<?php

declare(strict_types=1);

/**
 * Валидирует ID категории
 *
 * @param string $name Поле ввода
 *
 * @return string Отфильтрованное поле ввода;
 */
function get_post_value($name)
{
    return filter_input(INPUT_POST, $name);
}

/**
 * Валидирует ID категории
 *
 * @param int $id ID категории
 * @param int $allowed_list Список ID категорий
 *
 * @return "Указана несуществующая категория" | null;
 */
function validate_category($id, $allowed_list)
{
    if (!in_array($id, $allowed_list)) {
        return "Указана несуществующая категория";
    }
    return null;
}

/**
 * Валидирует email
 *
 * @param string $value Полученное значение
 *
 * @return string "Введите корректный email" | null;
 */
function validate_email($value)
{
    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
        return "Введите корректный email";
    }
    return null;
}


/**
 * Валидирует вводимую длину символов
 *
 * @param string $value Полученное значение
 * @param int $min Минимальное значение
 * @param int $max Максимальное значение
 *
 * @return "Значение должно быть от $min до $max символов" | null;
 */
function validate_length($value, $min, $max)
{
    if ($value) {
        $len = strlen($value);
        if ($len < $min or $len > $max) {
            return "Значение должно быть от $min до $max символов";
        }
    }
    return null;
}

/**
 * Валидирует является ли числом больше нуля
 *
 * @param string $value Полученное значение
 *
 * @return string "Значение $value должно быть числом больше нуля" | null;
 */

function validate_integer($value)
{
    if (filter_var($value, FILTER_VALIDATE_INT) === false) {
        return "Значение $value должно быть числом";
    }
    if (intval($value) <= 0) {
        return "Значение $value должно быть числом больше нуля";
    }
    return null;
}

/**
 * Проверяет, что переданная дата в формате 'ГГГГ-ММ-ДД' больше текущей даты, хотя бы на один день
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true, если указанная дата больше текущей даты хотя бы на один день, иначе false
 */
function validate_date(string $date)
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);
    $today = new DateTime('today');

    if ($dateTimeObj === false) {
        return "Указанная дата быть в формате «ГГГГ-ММ-ДД»;";
    }
    if ($dateTimeObj < $today) {
        return "Указанная дата должна быть больше текущей даты, хотя бы на один день";
    }
    return null;
}

/**
 * Проверяет незаполненные поля
 *
 * @param array $required Обязательные поля к заполнению
 * @param array $rules Массив из функции-помощников для валидации отдельных полей
 * @param array $array Список полей
 * @param array $errors Пустой массив для списка ошибок
 *
 * @return array $errors Список ошибок
 */
function validate_value($required, $rules, $array, $errors)
{
    foreach ($array as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }

        if (in_array($key, $required) && empty($value)) {
            $errors[$key] = "Поле $key надо заполнить";
        }
    }

    return array_filter($errors);
}

/**
 * Валидирует является ли значение ставки целым положительным числом
 * и больше или равно, чем текущая цена лота + шаг ставки.
 *
 * @param int $value Полученное значение
 * @param int $start_price Текущая цена
 * @param int $bet_step Шаг ставки
 *
 * @return "Значение $value должно быть числом больше нуля" | null;
 */

function validate_bet($value, $start_price, $bet_step)
{
    if (filter_var($value, FILTER_VALIDATE_INT) === false || intval($value) <= 0) {
        return "Значение должно быть целым положительным числом.";
    }
    if (intval($value) <= $start_price + $bet_step) {
        return "Значение должно быть больше или равно текущей цене лота + шаг ставки.";
    }
    return null;
}
