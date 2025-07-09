<?php

declare(strict_types=1);

session_start();

if (session_start() === true) {

define('CACHE_DIR', basename(__DIR__ . DIRECTORY_SEPARATOR . 'cache'));
define('UPLOAD_PATH', basename(__DIR__ . DIRECTORY_SEPARATOR . 'uploads'));

$db_cfg = require_once 'config.php';
$db_cfg = array_values($db_cfg);
require_once 'helpers.php';

$link = mysqli_connect(...$db_cfg);
mysqli_set_charset($link, 'utf8mb4');
if (!$link) {
    $error = mysqli_connect_error();
    print($error);
    die();
}

$categories = [];
$page_content = '';

}
