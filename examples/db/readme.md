## Schema

![The alchemist - Ed Cardone](../images/ed-cardone-gps.jpg)

See [Mateodioev/Db](https://github.com/Mateodioev/db)

```php

use App\Db\Schema;
use Mateodioev\Db\Query;

$db = new Query;
$schema = new Schema($db);

// Return bool
$schema->ExistTable('table_name');
$schema->DropTable('table_name');
$schema->NewTable('table_name', ['ID int(11) NOT NULL AUTO_INCREMENT,', 'name varchar(255) NOT NULL,', 'PRIMARY KEY (ID)';
$schema->Truncate('table_name');

```