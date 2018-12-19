<?php
require_once('vendor/autoload.php');
require_once('functions.php');

$lots_close = [];
$result = get_lots_without_winner($connect);

if ($result) {
    $lots_close = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $transport = new Swift_SmtpTransport('phpdemo.ru', 25);
    $transport->setUsername('keks@phpdemo.ru');
    $transport->setPassword('htmlacademy');

    foreach ($lots_close as $lot) {
        $current_lot = $lot['id'];
        $lot_end = strtotime($lot['completion_date']);
        $bet_result = get_winner_bet($connect, $current_lot, $lot_end);
        if ($bet_result) {
            $max_bet = mysqli_fetch_all($bet_result, MYSQLI_ASSOC);
            if (!empty($max_bet)) {
                $max_bet = $max_bet[0];
                $update = update_winner($connect, $max_bet['user_id'], $lot['id']);
                $message = new Swift_Message();
                $message->setSubject('Ваша ставка выиграла');
                $message->setFrom(['keks@phpdemo.ru' => 'Yeticave']);
                $message->setBcc([$max_bet['email'] => $max_bet['username']]);
                $username = $max_bet['username'];
                $lot_name = $lot['title'];
                $lot_id = $max_bet['lot_id'];
                $msg_content = include_template('email.php', [
                    'username' => $username,
                    'lot_name' => $lot_name,
                    'lot_id' => $lot_id
                ]);
                $message->setBody($msg_content, 'text/html');
                $mailer = new Swift_Mailer($transport);
                $mailer->send($message);
            }  else {
                update_winner($connect, 'NULL', $lot['id']);
            }
        }
    }
}

