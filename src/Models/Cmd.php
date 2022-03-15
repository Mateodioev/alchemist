<?php 

namespace Mateodioev\Alchemist\Models;

use Mateodioev\Alchemist\Config\Utils;
use Mateodioev\Alchemist\Plugins\{Senders, Inline};

class Cmd
{
  private array $separators;
  private $up;
  private $debug_mode = false;

  protected $registry = [
    'txt' => [], // Text commands
    'callback' => [], // Callback commands (buttons)
    'inline' => [] // Inline commands
  ];

  public function __construct(?object $up, array $separators = ['/', '!', '.'])
  {
    $this->separators = $separators;
    $this->up = $up;
  }

  /**
   * Extract command from string
   * @example " !cmd arg1 arg2" => "cmd"
   */
  public function Extract(string $txt): string
  {
    $txt = Utils::MultiExplode(
      [' ', '@', PHP_EOL],
      trim(strtolower(str_replace(['@', $_ENV['BOT_USER']], '', $txt)))
    )[0];

    if (in_array($txt[0], $this->separators)) {
      return substr($txt, 1);
    } return "";
  }

  /**
   * Return bool if command is valid
   */
  public function IsCmd(string|array $cmd, string $str):bool
  {
    if (!is_array($cmd)) $cmd = [$cmd]; // To array
    return in_array($this->Extract($str), $cmd);
  }

  /**
   * Register a new command
   */
  private function Hear(string $type, string|array $cmd, $callable, $vars): void
  {
    $cmd  = Utils::ToArray($cmd); // To array
    $vars = Utils::ToArray($vars); // To array

    if (is_string($callable)) $callable = Utils::ExplodeCall($callable);
    if (is_array($callable)) $callable = [new $callable[0], $callable[1]];
    // Only register if callable is valid
    if (is_callable($callable)) {
      foreach ($cmd as $name) {
        $this->Debug("Registering $type command: $name\n");
        $this->registry[$type][$name] = ['call' => $callable, 'vars' => $vars];
      }
    } else {
      if (is_array($callable)) $callable = implode('::', $callable);
      throw new \Exception("Invalid callable " . $callable);
    }
  }

  /**
   * Register a new txt command
   * @param   $cmd       Commands name to register
   * @param   $callable  Valid Callable to execute
   * @param   $vars      Vars to use in callable
   */
  public function HearTxt(string|array $cmd, $callable, $vars=null): void
  {
    $this->Hear('txt', $cmd, $callable, $vars);
  }

  /**
   * Register a new callback query command
   * @param   $cmd       Commands name to register
   * @param   $callable  Valid Callable to execute
   * @param   $vars      Vars to use in callable
   */
  public function HearCallback(string|array $cmd, $callable, $vars=null): void
  {
    $this->Hear('callback', $cmd, $callable, $vars);
  }

  /**
   * Register a new inline query command
   * @param   $cmd       Commands name to register
   * @param   $callable  Valid Callable to execute
   * @param   $vars      Vars to use in callable
   */
  public function HearInline(string|array $cmd, $callable, $vars=null): void
  {
    $this->Hear('inline', $cmd, $callable, $vars);
  }


  /**
   * Call to a command
   * @param   $cmd       Commands name to call
   * @param   $type      Type of command to call (txt, callback, inline)
   */
  private function CallToFunc(string $cmd, string $type)
  {
    if (in_array($cmd, array_keys($this->registry[$type]))) {
      $call = $this->registry[$type][$cmd];
      $this->Debug("Calling " . $cmd . "\n");
      call_user_func_array($call['call'], $call['vars']);
    }
  }

  public function Run()
  {
    if (isset($this->up->callback_query)) {
      $this->Debug("\nCallback query\n");
      // Callback query
      $callback = $this->up->callback_query;
      $data = $callback->data;
      $cmd = Utils::MultiExplode([' ', '|'], $data)[0];
      $this->CallToFunc($cmd, 'callback');
      return;
    }

    if (isset($this->up->inline_query)) {
      $this->Debug("\nInline query\n");
      // Inline query
      $inline = $this->up->inline_query;
      $query = $inline->query;
      $cmd = Utils::MultiExplode([' ', '|'], $query)[0];
      $this->CallToFunc($cmd, 'inline');
      return;
    }
    
    if (isset($this->up->message->text)) {
      $this->Debug("\nMessage text\n");
      // Message text
      $msg = $this->up->message;
      $txt = $msg->text;
      $cmd = self::Extract($txt);
      $this->CallToFunc($cmd, 'txt');
      return;
    }
    $this->Debug("\nNo command found\n");
  }

  /**
   * Active/Desactive debug mode
   */
  public function SetDebug(bool $set = false):void
  {
    $this->debug_mode = $set;
  }

  /**
   * Print a message if debug mode is enabled
   */
  public function Debug($data):void
  {
    if (!$this->debug_mode) return;
    if (php_sapi_name() != 'cli') {
      $data = Utils::RemoveNoAlpha($data);
    }
    print_r($data);
  }

  /**
   * Default txt commands
   * - Cmds: axoltl, ajolote, cat, gato, dog, perro, fox, zorro, ty, tr, ip, git, bin
   */
  public function RegisterDefaultTxt(Bot $bot, $up, Chat $chat)
  {
    try {
      $this->HearTxt(['axoltl', 'ajolote'], [Senders::class, 'Ajolote'], [$bot]);
      $this->HearTxt(['cat', 'gato'], [Senders::class, 'Gato'], [$bot]);
      $this->HearTxt(['dog', 'perro'], [Senders::class, 'Perro'], [$bot]);
      $this->HearTxt(['fox', 'zorro'], [Senders::class, 'Zorro'], [$bot]);
      $this->HearTxt('ty', [Senders::class, 'Translate'], [$bot, $up, $chat::getContent(4), 'yandex', $_ENV['YANDEX_TR']]);
      $this->HearTxt('tr', [Senders::class, 'Translate'], [$bot, $up, $chat::getContent(4)]);
      $this->HearTxt('ip', [Senders::class, 'getIp'], [$bot, $chat::getContent(4)]);
      $this->HearTxt('git', [Senders::class, 'Github'], [$bot, $chat::getContent(5)]);
      $this->HearTxt('bin', [Senders::class, 'Bin'], [$bot, $chat::getContent(5)]);
    } catch (\Exception $e) {
      $bot->SendMsg(Utils::QuitHtml($e->getMessage()));
      die;
    }
  }

  /**
   * Default inline commands
   * - Cmds: bin
   */
  public function RegisterDefaultInline(Bot $bot, BotCoreInline $inline, $up, Chat $chat)
  {
    try {
      $this->HearInline('bin', [Inline::class, 'SearchBin'], [$bot, $inline, $chat::getContent(4)]);
    } catch (\Exception $e) {
      $bot->answerInlineQuery([
        $inline->Article('Unknow problem', $inline->InputMessageContent(Utils::QuitHtml($e->getMessage())), 'Problem')
      ]);
      die;
    }
  }
}