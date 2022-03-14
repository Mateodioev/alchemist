<?php

namespace App\Plugins;

use App\Config\Bin;
use App\Models\{Bot, BotCoreInline};
use function App\{BoolString, b, u, i, n, code};

class Inline {
  
  private function Down(bool $eval, Bot $bot, BotCoreInline $inline, string $title, string $msg): void
  {
    if (!$eval) return;
    $bot->answerInlineQuery([
      $inline->Article($title, $inline->InputMessageContent($msg), 'Fail')
    ], null, 0.1);
  }

  /**
   * Get bin info from bins.su
   */
  public function SearchBin(Bot $bot, BotCoreInline $inline, string $bin)
  {
    $this->Down(empty($bin), $bot, $inline, 'Please put one bin', 'Please put a bin number' . PHP_EOL . 'Example: <code>/bin 510805</code>');
    $fim = new Bin;
    $fim->Validate($bin);
    $this->Down(!$fim->validate->ok, $bot, $inline, $fim->validate->msg, '<b>❌ ' . $fim->validate->msg . '</b> (<i>'.$fim->validate->bin.'</i>)');

    $res = $fim->BinsSu($fim->validate->bin);
    $this->Down(!$res['ok'], $bot, $inline, 'Invalid bin', '<b>❌ ' . @$res['error'] . '</b> (<i>'.$res['bin'].'</i>)');
    $txt = b('Valid bin:').' '.u($res['bin']).n().b(i('Country:')).' '.$res['country'].' '.$res['flag'].n().b(i('Datas:')). ' ' . $res['vendor'].' - '.$res['type'].' - '.$res['level'].n().b(i('Bank:')).' '.$res['bank'];

    $bot->answerInlineQuery([
      $inline->Article(
        'Valid bin',
        $inline->InputMessageContent($txt),
        'Valid bin: '.$res['bin']
      )
    ], null, 0.1);
  }
}