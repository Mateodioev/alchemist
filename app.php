<?php require __DIR__ . '/vendor/autoload.php';
define('APP_PATH', __DIR__);

use Mateodioev\Alchemist\Controller\Start;

$senku = new Start(APP_PATH, true, false, true);
$bot = $senku->bot;
$inline = $senku->inline;
$up = $senku->up;
$chat = $senku->chat;
$cmd = $senku->cmd;

$cmd->SetDebug(true);
$cmd->RegisterDefaultTxt($bot, $up, $chat);
$cmd->RegisterDefaultInline($bot, $inline, $up, $chat);
$cmd->Run();
