<?php

use Mateodioev\Alchemist\Cli\{App, Color};

require 'path/to/vendor/autoload.php';

$cli = new App;

// $cli->GetPrinter return \App\Cli\Printer object
$cli->GetPrinter()->Clear();

$cli->Register('help', function () use ($cli) {
  $txt = Color::Fg(13, 'Usage: php '.$_SERVER['SCRIPT_NAME'].' [command] [options]'). PHP_EOL;
  $txt .= 'Available commands:'. PHP_EOL;

  foreach ($cli->GetAllCmds() as $i => $item) {
    $txt .= ' - ' . Color::Fg(9, $i) . PHP_EOL;
    unset($item);
  }
  $cli->GetPrinter()->Display(trim($txt));
});

$cli->Run($argv);