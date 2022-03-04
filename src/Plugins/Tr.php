<?php 

namespace App\Plugins;

use App\Config\Utils;
use Mateodioev\Translate;

class Tr {
  
  public string $txt = '';
  public string $lang_input = '';
  public string $lang_output = '';

  public $res;

  /**
   * Get text, lang input and land output
   */
  public function ParseStr($up, string $msg)
  {
    if (empty($msg) && !isset($up->message->reply_to_message->text)) return;

    $lang_co = Utils::MultiExplode([' ', "\n"], $msg)[0];
    $lang_code = explode('|', $lang_co);

    $lang_input = $lang_code[0] ?? 'auto';
    $lang_output = $lang_code[1] ?? 'es';

    if (!isset($lang_code[1])) {
      $lang_input = 'auto';
      $lang_output = $lang_code[0];
    }
    $lang_output = (empty($lang_output)) ? 'es' : $lang_output;
    $textTr = $up->message->reply_to_message->caption ?? $up->message->reply_to_message->text ?? trim(substr($msg, strlen($lang_co))); // Reply message o message simple

    $this->txt = $textTr;
    $this->lang_input = $lang_input;
    $this->lang_output = $lang_output;
  }

  public function Get(string $use, ?string $api_key=null)
  {
    $tr = new Translate;
    $tr->setText($this->txt)->setInputLang($this->lang_input)->setOutputLang($this->lang_output);

    if ($use == 'google') {
      $this->res = $tr->google();
    } else {
      $this->res = $tr->yandex($api_key);
    }

    return $tr;
  }
}