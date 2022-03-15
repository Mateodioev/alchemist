<?php 
require 'path/to/vendor/autoload.php';

use Mateodioev\Alchemist\Cli\App;

$app = new App();

// New command
$app->Register('test', function () {
    echo 'Hello World' . PHP_EOL;
});

$app->Register('external', 'external');

// Default command "Help"
$app->Register('help', function() use ($app) {
  echo 'Available commands:'. PHP_EOL;

  foreach ($app->GetAllCmds() as $i => $item) {
    echo "\t -".$i . "\n";
    unset($item);
  }

  echo 'Usage: php ' . $_SERVER['SCRIPT_NAME'] . ' [command] [options]'. PHP_EOL;
});

// Run command
$app->Run($argv);


function external() {
  echo 'Hello from external function' . PHP_EOL;
}