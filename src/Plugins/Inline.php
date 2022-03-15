<?php

namespace Mateodioev\Alchemist\Plugins;

use Mateodioev\Alchemist\Config\Bin;
use Mateodioev\Alchemist\Models\{Bot, BotCoreInline};
use function Mateodioev\Alchemist\{b, u, i, n, code};

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
    $this->Down(empty($bin), $bot, $inline, 'Please put one bin', 'Please put a bin number' . PHP_EOL . 'Example: '.code('/bin 510805'));
    $fim = new Bin;
    $fim->Validate($bin);
    $this->Down(!$fim->validate->ok, $bot, $inline, $fim->validate->msg, b('❌ ' . $fim->validate->msg) . '(' . code($fim->validate->bin). ')');
    $bin = $fim->validate->bin;
    $res = $fim->BinsSu($bin);
    $this->Down(!$res['ok'], $bot, $inline, 'Invalid bin', b('❌ '.@$res['error']).'('.i($bin).')');
    $txt = b('Valid bin:').' '.u($bin).n().b(i('Country:')).' '.$res['country'].' '.$res['flag'].n().b(i('Datas:')). ' ' . $res['vendor'].' - '.$res['type'].' - '.$res['level'].n().b(i('Bank:')).' '.$res['bank'];

    $bot->answerInlineQuery([
      $inline->Article(
        'Valid bin',
        $inline->InputMessageContent($txt),
        'Valid bin: '.$res['bin']
      )
    ], null, 0.1);
  }
}