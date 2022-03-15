<?php 

namespace Mateodioev\Alchemist\Config;

use Mateodioev\Request\Request;
use function Mateodioev\Alchemist\xQuit;
class Bin
{
  private $lastHtml;
  private $lastXml;
  public $validate;

  const BINS_SU = 'http://bins.su/';

  /**
   * Analize xpath
   *
   * @param string $html HTML page
   * @param string $xpath XPath
   */
  private function Xpath(string $xpath, ?string $html=null)
  {
    libxml_use_internal_errors(true); // Ignore errors
    if ($html) { // New html document
      $this->lastHtml = new \DOMDocument();
      $this->lastHtml->loadHTML($html);
      $this->lastXml  = new \DOMXPath($this->lastHtml);
    }

    $res = $this->lastXml->evaluate($xpath);
    $response = [];
    foreach ($res as $item) {
      $response[] = $item->textContent;
      $response[] = $item->nodeName;
    }
    return $response;
  }

  /**
   * Get bin from input
   */
  public function getBin($bin)
  {
    return substr(@preg_replace('/[^0-9]/', '', $bin), 0, 6);
  }

  /**
   * Validate bin
   */
  public function Validate(string|int $bin)
  {
    $bin = $this->getBin($bin);
    if (strlen($bin) < 6) {
      $this->validate = (object) ['ok' => false, 'msg' => 'Bin is too short', 'bin' => $bin];
    } elseif ($bin[0] == '0') {
      $this->validate = (object) ['ok' => false, 'msg' => 'Invalid bin', 'bin' => $bin];
    } else {
      $this->validate = (object) ['ok' => true, 'msg' => 'Valid bin', 'bin' => $bin];
    }
  }

  /**
   * Get bin info from bins.su
   * @link http://bins.su/
   */
  public function BinsSu(string $bin): array
  {
    $res = Request::Post(self::BINS_SU, null, http_build_query(['bins' => $bin, 'bank' => '', 'country' => '']));

    $response = trim($this->Xpath('//*[@id="result"]', $res['response'])[0]);
    if ($response == 'No bins found!') {return ['ok' => false, 'error' => 'No bins found!', 'bin' => $bin];}

    $response = trim(explode('BIN', $response)[0]);

    $res = explode('</center>', explode('<div id="result">', $res['response'])[1])[0];
    $span = 'Total found 1 bins<table><tr><td>BIN</td><td>Country</td><td>Vendor</td><td>Type</td><td>Level</td><td>Bank</td></tr><tr>';
    $body = str_replace([$span, '</tr></table>', '</td>', '</div>'], '', $res);
    $fim  = explode('<td>', $body);

    return [
      'ok'       => true,
      'response' => xQuit($response),
      'bin'      => xQuit($bin),
      'country'  => xQuit($fim[2]) ?? '',
      'flag'     => Flag::Emoji(xQuit($fim[2]) ?? ''),
      'vendor'   => xQuit($fim[3]) ?? '',
      'type'     => xQuit($fim[4]) ?? '',
      'level'    => xQuit($fim[5]) ?? '',
      'bank'     => xQuit($fim[6]) ?? ''
    ];
  }
}