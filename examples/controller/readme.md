# Start controller

![The alchemist - Ed Cardone](../images/ed-cardone-gps.jpg)

- [Start controller](#start-controller)
  - [Namespace](#namespace)
  - [Usage](#usage)

This class allows you to use most of the other classes in the library, like the *[Bot Core](../bot/readme.md)*, the *[cli](../cli/readme.md)* and the *[DataBase](../db/readme.md)* driver.

Note:
 - Automatically parse the variables of the .env file


## Namespace

```php
use Mateodioev\Alchemist\Controller\Start;
```

## Usage

- `$app_path`: The path to the application, use to set logs dir and extract **.env** vars.
- `$use_db`: If you want to prepare mysql connection
- `$use_cli`: Return cli *[printer](../cli/readme.md)* and *[app](../cli/readme.md)*

```php
$alchemist = new Start($app_path, $use_db, $use_cli, $use_inline);
```