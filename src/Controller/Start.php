<?php 

namespace App\Controller;

use App\Cli\App;
use App\Config\Logger;
use App\Models\{Bot, Chat, Cmd};

use Mateodioev\Db\{Connection, Query};

class Start {

  public $chat;
  public $bot;
  public $cmd;
  public $cli;
  public $up;
  public $db;

  private string $path;
  public string $path_log;

  public function __construct(string  $app_path, bool $use_db = true, bool $use_cli = false)
  {
    $dotenv = \Dotenv\Dotenv::createImmutable($app_path);
    $dotenv->load();

    $this->path = $app_path;
    $this->path_log = $this->path . '/logs/php-error.log';
    $this->Server($_ENV['TIME_ZONE']);

    $this->bot = new Bot($_ENV['BOT_TOKEN']);
    $this->up = $this->bot->GetData();
    $this->chat = new Chat($this->up);
    $this->cmd = new Cmd($this->up);
    
    if ($use_db) $this->prepareDb();
    if ($use_cli) $this->Cli();
  }

  /**
   * Set time zone and log errors
   */
  private function Server(string $time_zone)
  {
    date_default_timezone_set($time_zone);
    Logger::Activate($this->path_log, $_ENV['MAX_EXECUTION_TIME']);
  }

  /**
   * Prepare Db connection
   */
  private function prepareDb()
  {
    Connection::PrepareFromEnv($this->path);
    $this->db = new Query;
  }

  /**
   * Get cli
   */
  public function Cli()
  {
    if ($this->cli != null) return $this->cli;
    if (php_sapi_name() !== 'cli') {
      die('This script must be run from the command line.');
    }
    $this->cli = new App;
    return $this->cli;
  }
}