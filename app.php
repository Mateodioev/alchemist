<?php require __DIR__ . '/vendor/autoload.php';
define('APP_PATH', __DIR__);

use App\Controller\Start;

$senku = new Start(APP_PATH);
$bot = $senku->bot;
$up = $senku->up;
$cmd = $senku->cmd;
$chat = $senku->chat;

$cmd->HearTxt(['help', 'start'], function () use ($bot, $chat) {
  $bot->SendMsg('Hola ' . $chat::getUser() . ' ! I\'m a bot for help in choosing commands for your bot. To get help, enter /help');
});

$cmd->HearTxt(['axoltl', 'ajolote'], '\App\Plugins\Senders@Ajolote', [$bot]);
$cmd->HearTxt(['cat', 'gato'], '\App\Plugins\Senders@Gato', [$bot]);
$cmd->HearTxt(['dog', 'perro'], '\App\Plugins\Senders@Perro', [$bot]);
$cmd->HearTxt(['fox', 'zorro'], '\App\Plugins\Senders@Zorro', [$bot]);

$cmd->HearTxt('ty', '\App\Plugins\Senders@Translate', [$bot, $up, $chat::getContent(4), 'yandex', $_ENV['YANDEX_TR']]);
$cmd->HearTxt('tr', '\App\Plugins\Senders@Translate', [$bot, $up, $chat::getContent(4)]);
$cmd->HearTxt('ip', '\App\Plugins\Senders@getIp', [$bot, $chat::getContent(4)]);
$cmd->HearTxt('git', '\App\Plugins\Senders@Github', [$bot, $chat::getContent(5)]);
$cmd->HearTxt('bin', '\App\Plugins\Senders@Bin', [$bot, $chat::getContent(5)]);

$cmd->Run();