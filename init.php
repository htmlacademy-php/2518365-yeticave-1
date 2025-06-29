<?php
declare(strict_types=1);

require_once 'helpers.php';
$db = require_once 'config.php';

$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($link, 'utf8mb4');
if (!$link) {
    $error = mysqli_connect_error();
    print($error);
    die();
}

$categories = [];
$page_content = '';


