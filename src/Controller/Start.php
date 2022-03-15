<?php 

namespace App\Controller;

use App\Cli\App;
use App\Config\Logger;
use App\Models\{Bot, BotCoreInline, Chat, Cmd};
use Mateodioev\Db\{Connection, Query};

class Start {

  public ?BotCoreInline $inline=null;
  public Chat $chat;
  public Bot $bot;
  public Cmd $cmd;
  public ?App $cli=null;
  public ?object $up=null;
  public ?Query $db=null;

  private string $path;
  public string $path_log;

  public function __construct(string $app_path, bool $use_db = true, bool $use_cli = false, bool $use_inline = false)
  {
    $dotenv = \Dotenv\Dotenv::createImmutable($app_path);
    $dotenv->load();

    $this->path = $app_path;
    $this->path_log = $this->path . '/logs/'.date('d-m-y ').'php-error.log';
    $this->Server($_ENV['TIME_ZONE']);

    $this->bot = new Bot($_ENV['BOT_TOKEN']);
    $this->up = $this->bot->GetData();
    $this->chat = new Chat($this->up);
    $this->cmd = new Cmd($this->up);
    
    if ($use_db) $this->prepareDb();
    if ($use_cli) $this->Cli();
    if ($use_inline) $this->inline = new BotCoreInline;
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
  private function Cli()
  {
    if (php_sapi_name() === 'cli') {
      $this->cli = new App;
    }
  }
}