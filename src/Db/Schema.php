<?php 

namespace Mateodioev\Alchemist\Db;

use Mateodioev\Db\Query;

class Schema
{
  protected $sql;

  public function __construct(Query $db)
  {
    $this->sql = $db;
  }

  /**
   * Return true if exist the table
   */
  final public function ExistTable(string $table_name):bool
  {
    return $this->sql->Exec("SHOW TABLES LIKE :table", [':table' => $table_name])['ok'];
  }

  /**
   * Delete table
   */
  final public function DropTable(string $table_name):bool
  {
    return $this->sql->Exec("DROP TABLE IF EXISTS {$table_name}")['ok']; // Not support prepared statement in this method
  }

  /**
   * Create new table
   */
  final public function NewTable(string $table_name, ?array $fields=null):bool
  {
    $schema = "CREATE TABLE $table_name (";
    foreach ($fields as $field) {
      $schema .= $field;
    }
    $schema .= ")";
    return $this->sql->Exec($schema)['ok'];
  }

  /**
   * Delete values from a table
   */
  final public function Truncate(string $table_name):bool
  {
    return $this->sql->Exec("TRUNCATE TABLE {$table_name}")['ok']; // Not support prepared statement in this method
  }
}