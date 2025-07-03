<?php

/**
 * Валидирует ID категории
 *
 * @param $name Поле ввода
 *
 * @return Отфильтрованное поле ввода;
 */
function get_post_value($name) {
    return filter_input(INPUT_POST, $name);
}

/**
 * Валидирует ID категории
 *
 * @param $id ID категории
 * @param $allowed_list Список ID категорий
 *
 * @return "Указана несуществующая категория" | null;
 */
function validate_category($id, $allowed_list) {
    if (!in_array($id, $allowed_list)) {
        return "Указана несуществующая категория";
    }
    return null;
}

/**
 * Валидирует вводимую длину символов
 *
 * @param $value Полученное значение
 * @param $min Минимальное значение
 * @param $max Максимальное значение
 *
 * @return "Значение должно быть от $min до $max символов" | null;
 */
function validate_length($value, $min, $max) {
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
 * @param $value Полученное значение
 *
 * @return "Значение $value должно быть числом больше нуля" | null;
 */

function validate_integer($value) {
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
function validate_date(string $date) {
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
 * @param array $lot Список лотов
 *
 * @return array $errors Список ошибок
 */
function validate_value($required, $rules, $lot) {
    foreach ($lot as $key => $value) {
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
