<?php

use App\Models\Bot;

require 'path/to/vendor/autoload.php';

$bot = new Bot('YourBotToken');

$chat_id = 'YourChatId';

$bot->AddOpt(['parse_mode' => 'Markdown']); // Parse mode default is HTML
$res = $bot->SendMsg('*Hello World!*', $chat_id);

if ($res->ok) {
  sleep(2);
  $ida = $res->result->message_id;
  $bot->EditMsg('_Edited message_', $chat_id, $ida);
}
