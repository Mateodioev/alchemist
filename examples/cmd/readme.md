# Commands and chat

![The alchemist - Ed Cardone](../images/ed-cardone-gps.jpg)

- [Commands and chat](#commands-and-chat)
  - [Commands](#commands)
      - [Namespace](#namespace)
      - [$callable](#callable)
      - [$vars](#vars)
    - [Text](#text)
    - [Callback](#callback)
    - [Inline](#inline)
    - [Default Commands](#default-commands)
    - [Debug mode](#debug-mode)
    - [Run](#run)
  - [Chat](#chat)
      - [Namespace](#namespace-1)
    - [Set webhook update](#set-webhook-update)
    - [Methods](#methods)


## Commands

#### Namespace

```php
use Mateodioev\Alchemist\Models\Cmd;
```

Adding a new command

#### $callable

 - `$callable` It can be a function or method of a class
    - If it is a method of a class, the name of the class and the name of the method must be indicated
      - `$callable = [ClassName::class, 'MethodName'];`
      - `$callable = 'ClassName@MethodName';`
      - `$callable = 'ClassName::MethodName';` Static methods are supported
 - `$callable = function() {};` Anonymous functions are supported 

#### $vars

- Refers to variables used by a method or function
- They are passed in order, and the name of the variable does not matter

```php

$callable = function(string $name, int $age) {
  echo 'Hello ' . $name . ' you are ' . $age . ' years old';
}
$vars = ['Alex', 25];
$cmd->HearTxt('hi', $callable, $vars);

```

### Text

```php
$cmd->HearTxt('comand_name', $callable, $vars);
```

### Callback

```php
$cmd->HearCallback('comand_name', $callable, $vars);
```

### Inline

```php
$cmd->HearInline('comand_name', $callable, $vars);
```

### Default Commands

```php
use Mateodioev\Alchemist\Controller\Start;

$senku = new Start(APP_PATH, true, false, true);
$bot    = $senku->bot;
$inline = $senku->inline;
$up     = $senku->up;
$chat   = $senku->chat;
$cmd    = $senku->cmd;

# Cmds: axoltl, ajolote, cat, gato, dog, perro, fox, zorro, ty, tr, ip, git, bin
$cmd->RegisterDefaultTxt($bot, $up, $chat);
# Cmds: bin
$cmd->RegisterDefaultInline($bot, $inline, $up, $chat);
```

### Debug mode

Print basic info about commands and called commands

```php
$cmd->SetDebug(true);
```

### Run

Evaluate webhook object and call to command

```php
$cmd->Run();
```

## Chat

Getting chat datas

#### Namespace

```php
use Mateodioev\Alchemist\Models\Chat;
```

### Set webhook update

```php
$up = $bot->GetData();
# OR
$alchemist = new \Mateodioev\Alchemist\Controller\Start(APP_PATH, false, false, true);
$up = $alchemist->up;


Chat::SetUp($up);
```

### Methods

The methods are avaliable in message text, callbacks and inline querys

- Get User ID

```php
Chat::getUserId();
```

- Get UserName

```php
Chat::getUser();
```

- Get Chat ID

```php
Chat::getChatId();
```

- Get Chat Type

```php
Chat::getMsgId();
```

- Get Message ID

```php
Chat::getChatType();
```

- Get Text

```php
Chat::getTxt();
```

- Get Content

```php
Chat::getContent();
# User input: "/start 1234567890"
echo Chat::getContent(6);
# Output: "1234567890"
```

- Get Inline ID (only in inline querys)

```php
Chat::getInlineId();
```
