<?php

use App\Controller\Start;

require __DIR__ . '/../../vendor/autoload.php';

define('APP_PATH', dirname(__FILE__, 3));

// If not found .env file, throws Exception
$alchemist = new Start(APP_PATH, false, false, true);

// App\Models\BotCoreInline or null
$coreInline = $alchemist->inline;

// App\Models\Chat
$chat = $alchemist->chat;
// App\Models\Cmd
$cmd  = $alchemist->cmd;
// App\Models\Bot
$bot  = $alchemist->bot;

// App\Cli\App or null
$cli  = $alchemist->cli;

// Mateodioev\Db\Query or null
$db = $alchemist->db;
// Webhook data, type: Object or null
$up = $alchemist->up;
