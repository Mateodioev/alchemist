<?php 

namespace App\Models;

use App\Config\Utils;

class Cmd
{
  private array $separators;
  private $up;

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
      call_user_func_array($call['call'], $call['vars']);
    }
  }

  public function Run()
  {
    if (isset($this->up->callback_query)) {
      // Callback query
      $callback = $this->up->callback_query;
      $data = $callback->data;
      $cmd = Utils::MultiExplode([' ', '|'], $data)[0];
      $this->CallToFunc($cmd, 'callback');
      return;
    }

    if (isset($this->up->inline_query)) {
      // Inline query
      $inline = $this->up->inline_query;
      $query = $inline->query;
      $cmd = Utils::MultiExplode([' ', '|'], $query)[0];
      $this->CallToFunc($cmd, 'inline');
      return;
    }
    
    if (isset($this->up->message->text)) {
      // Message text
      $msg = $this->up->message;
      $txt = $msg->text;
      $cmd = self::Extract($txt);
      $this->CallToFunc($cmd, 'txt');
      return;
    }
    echo "No command found\n";
  }
}