<?php
require 'path/to/vendor/autoload.php';

use Mateodioev\Alchemist\Cli\Color;

if (php_sapi_name() != 'cli') {
  exit('This script can only be run from the command line.');
}

echo Color::Fg(50, 'Hello World') . PHP_EOL; // Foreground
echo Color::Bg(1, 'Hello World') . PHP_EOL; // Background

echo Color::Bg(120, Color::Fg(232, 'Hello World')) . PHP_EOL; // Background + Foreground

echo Color::Apply('bold', 'Hello World') . PHP_EOL; // Custom