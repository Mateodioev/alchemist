<?php require 'path/to/vendor/autoload.php';

use App\Cli\Printer;

$printer = new Printer();

do {
  $printer->Clear();
  
  $number = $printer->Read('Enter a number: ');
  $printer->Display('You entered: ' . $number);

  $continue = $printer->Read('Do you want to continue? (y/n)');
} while ($continue == 'y');

$printer->Display('Bye!');