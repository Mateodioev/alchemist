<?php

use App\Cli\Color;
use App\Config\Request;

use App\Controller\Start;

require __DIR__ . '/vendor/autoload.php';

$app = new Start(__DIR__, false, true);
$cli = $app->cli;
$cli->GetPrinter()->Clear();

$cli->Register('setwebhook', function () use ($cli) {
    $url   = $cli->GetPrinter()->Read('Enter your webhook url: ');
    $url   = 'https://api.telegram.org/bot' . $_ENV['BOT_TOKEN'] . '/setWebhook?url=' . urlencode($url);
    $cli->GetPrinter()->Display(Color::Fg(82, "Setting webhook... "));
    $res = json_decode(Request::Get($url)['response'], true);

    if ($res['ok']) {
      $cli->GetPrinter()->Display(Color::Fg(82, "Response: " . $res['description']));
    } else {
      $cli->GetPrinter()->Display(Color::Fg(196, "Response: " . $res['description']));
    }
});

$cli->Register('help', function () use ($cli) {
  $txt = Color::Fg(13, 'Usage: php cli.php [command] [options]'). PHP_EOL;
  $txt .= 'Available commands:'. PHP_EOL;

  foreach ($cli->GetAllCmds() as $i => $item) {
    $txt .= ' - ' . Color::Fg(9, $i) . PHP_EOL;
    unset($item);
  }
  $cli->GetPrinter()->Display(trim($txt));
});

$cli->Run($argv);