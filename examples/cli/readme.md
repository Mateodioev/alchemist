# How to use CLI

![The alchemist - Ed Cardone](../images/ed-cardone-gps.jpg)

- [How to use CLI](#how-to-use-cli)
  - [Namespaces](#namespaces)
  - [App](#app)
  - [Color](#color)
  - [Printer](#printer)

## Namespaces

```php
use App\Cli\App;
use App\Cli\Color;
use App\Cli\Printer;
```

## App

  Register cli commands and execute them.
   - See more examples in [example_app.php](example_app.php)
  ```php
  use App\Cli\App;
  
  $app = new App();

  // Register new command
  $app->Register('test', function() {
    echo 'Hello world!';
  });

  // Execute command
  $app->Run($argv);
  ```

## Color

  This class is used to colorize text in CLI.
   - Note: This class use [PHP Console Color library](https://github.com/php-parallel-lint/PHP-Console-Color).
   - See more examples in [example_color.php](example_color.php)

  ```php
  use App\Cli\Color;

  $color_code_to_use = 1;

  // Foreground
  echo Color::Fg($color_code_to_use, 'Your text Here').PHP_EOL;
  // Background
  echo Color::Bg($color_code_to_use, 'Your text Here').PHP_EOL;
  ```

## Printer

  Display text in CLI.
   - See more example in [example_printer.php](example_printer.php)

  ```php
  use App\Cli\Printer;

  $printer = new Printer;

  // Display text
  $printer->Out('Hello world!');
  // Print PHP_EOL
  $printer->NewLine();
  // Clear screen
  $printer->Clear();
  // Read user input
  $continue = $printer->Read('Continue? (y/n)');
  // Show text with a line break
  $printer->Display('Text');
  ```