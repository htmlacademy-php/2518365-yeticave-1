<?php

declare(strict_types=1);

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

require_once 'helpers.php';
require_once 'init.php';
require_once 'models/lot.php';
require_once 'models/bet.php';
require_once 'vendor/autoload.php';

$sql = "SELECT * FROM lots WHERE winner_id IS NULL && date_end <= NOW()";
$lots = get_arr($link, $sql);

foreach ($lots as $lot) {
    $id = (int)$lot['id'];
}
$bets = get_user_by_bet($link, $id);
$user_name = '';
$lot_name = '';
$lot_id = 0;
$email = '';
$winners = [];

if (!empty($bets)) {
    foreach ($bets as $bet) {
        $winner_id = intval($bet['user_id']);
        $lot_id = intval($bet['lot_id']);
        add_winner_on_db($link, $winner_id, $lot_id);
        $winners = get_winners($link, $lot_id, $winner_id);
    }
    if (isset($winners)) {
        foreach ($winners as $winner) {
            $lots_id = $winner['lot_id'];
            $lot_name = $winner['lot_name'];
            $user_name = $winner['user_name'];
            $email = $winner['email'];
        }
    }
    $msg_content = include_template('email.php', [
        'user_name' => $user_name,
        'lot_name' => $lot_name,
        'lot_id' => $lot_id
    ]);

    $dsn = 'smtp://75f3c8c888f4c0:d3bf00f9a2376d@smtp.mailtrap.io:2525?encryption=tls&auth_mode=login';
    $transport = Transport::fromDsn($dsn);

    $message = new Email();
    $message->to($email);
    $message->from("keks@phpdemo.ru");
    $message->subject("Ваша ставка победила");
    $message->html($msg_content);

    $mailer = new Mailer($transport);
    $result = $mailer->send($message);

}
