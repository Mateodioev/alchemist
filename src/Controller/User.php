<?php 

namespace App\Controller;

use App\Db\Schema;

class User extends Schema {
  
  protected $table = 'users';
  /**
   * Get user from database
   *
   * @param string|integer $identifier
   * @param string $type identifier type (id, username)
   */
  private function getUser(string|int $identifier, string $from = 'id')
  {
    return $this->sql->Exec("SELECT * FROM {$this->table} WHERE $from = ?", [$identifier]);
  }

  public function getUserById($id)
  {
    return $this->getUser($id);
  }

  public function getUserByUsername(string $username)
  {
    return $this->getUser($username, 'username');
  }
  
}