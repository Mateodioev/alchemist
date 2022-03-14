<?php
require 'path/to/vendor/autoload.php';

use App\Models\Bot;
use App\Models\BotCoreInline;

$bot = new Bot('YourBotToken');
$coreInline = new BotCoreInline;

// Send answer to an inline query.

$cache_time = 1; // 300 - Default
$res = $bot->answerInlineQuery([
  $coreInline->Article(
    'Title',
    $coreInline->InputMessageContent('Hello World!'),
    'Description',
    'https://www.google.com/images/branding/googlelogo/2x/googlelogo_color_272x92dp.png',
    'https://www.google.com/',
    true
  ),
], 'inline_query_id', $cache_time, false);
