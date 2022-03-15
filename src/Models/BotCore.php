<?php 

namespace Mateodioev\Alchemist\Models;

use Mateodioev\Alchemist\Config\Utils;
use Mateodioev\Request\Request;

class BotCore {
  const API_URL = 'https://api.telegram.org/';
  public string $token = '';
  public string $endpoint = '';
  public string $file_dwn = '';

  private $res;
  private $result;
  private $opt = []; // Optional config payload
  private bool $throw = false; // Throw exception on error

  public $content;
  public $update;

  /**
   * Add bot token and set api endpoint
   * 
   * @throws Exception
   */
  public function __construct(string $bot_token)
  {
    if (empty($bot_token)) {
      throw new \Exception('Bot token is empty');
    }
    $this->token = $bot_token;
    $this->endpoint = self::API_URL . 'bot' . $this->token . '/';
    $this->file_dwn = self::API_URL . 'file/bot' . $this->token . '/';
  }

  /**
   * Send request to telegram api
   */
  public function request(string $method, ?array $datas=[], bool $decode = true):mixed
  {
    $url = $this->endpoint . $method;
    $datas = array_merge($datas, $this->opt);

    Utils::DeleteKeyEmpty($datas);
    $this->res = Request::Post($url, null, $datas)['response'];

    $this->result = ($decode) ? json_decode($this->res) : $this->res;
    if (!$this->result->ok) {
      error_log('[bot] Method ' . $method . ' failed: ' . json_encode($datas));
      error_log('[bot] Response: ' . $this->res);
      error_log('[bot] Description: ' . $this->result->description);
      if ($this->throw) {
        throw new \Exception('Fail '.$method.': '.$this->result->description);
      }
    }
    return $this->result;
  }

  /**
   * Send any method
   */
  public function __call($name, $arguments)
  {
    $payload = array_merge($arguments[0] ?? [], $this->opt);
    return $this->request($name, $payload);
  }

  /**
   * Get input data from webhook
   */
  public function GetData(bool $decode = true)
  {
    $this->content = file_get_contents('php://input');
    $this->update  = ($decode) ? json_decode($this->content) : $this->content;

    return $this->update;
  }

  /**
   * Add optional config payload to request
   */
  public function AddOpt(array $opt)
  {
    $this->opt = $opt;
  }

  /**
   * Set throw exception on error in request
   */
  public function setThrow(bool $set = false): void
  {
    $this->throw = $set;
  }
}