# Bot core 🤖

## Namespaces

```php
use App\Models\Bot; // Extends BotCore
use App\Models\BotCore;
use App\Models\BotCoreInline; // Only for inline mode

# use App\Models\{Bot, BotCore, BotCoreInline}; PHP 8
```

## Usage

Throw a new exception if the token is empty
```php
$bot = new Bot('YOUR_BOT_TOKEN'); // Create bot instance
```

## General format

See available methods in https://core.telegram.org/bots/api#available-methods

```php
$method = 'Any method';
$parameters = []; // ['chat_id' => '111111, 'text' => 'Your text'];
$bot->$method($parameters);
```

### Add aditional parameters

```php
$bot->AddOpt(['parse_mode' => 'html']); // Example
$bot->$method_name();
```

## Predefinide method

 - Note: It is not necessary to pass the *chat_id* and *msg_id*, the bot autocompletes it
 - The button can be an array or object, then the method converts it to json


All methods that are not defined should be called as follows:

```php
$bot->method_name($parameters);
```

Being `$parameters` an array with the necessary data for the method


### SendAction

```php
$bot->SendAction('action_type', 'chat_id');
```


### Send message

```php
$bot->SendMsg('Your text');
```

### Edit message

```php
$bot->EditMsg('Text', 'msg_id', 'chat_id');
```

### Delete message

Include service message

```php
$bot->DelMsg('chat_id', 'message_id')
```

### Send Document

Send any type of file

```php
$bot->Document('document', 'caption', 'chat_id', 'message_id');
```
   - Note: _document_ Can be an external link or a valid local filename

### Send Photo

```php
$bot->Photo('photo', 'caption', 'chat_id', 'message_id');
```

 - Note: _photo_ Can be an external link or a valid local filename

### Send Venue

```php
$lat = 1.1111; // Latitude
$log = 1.1111; // Longitude
$bot->sendVenue($lat, $log, 'Title', 'Addres');
```

### Me

Return bot info

```php
$bot->Me();
```

### GetFile

```php
$bot->GetFile('file_id');
```

### Answer inline query

```php
$bot->answerInlineQuery($results=[], 'inline_query_id', 'cache_time', 'is_personal');
```