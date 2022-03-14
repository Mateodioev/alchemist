<?php 

namespace App\Plugins;

use App\Config\Bin;
use function App\{BoolString, b, u, i, n, code};

class Senders extends Apis
{
  /**
   * Terminate the script if the $eval is true
   */
  private function Down(bool $eval, $bot, string $txt = '<i>Sorry, <b>I\'m not available right now</b>. Try again later.</i>')
  {
    if ($eval) {
      $bot->SendMsg($txt);
      exit;
    }
  }

  /**
   * Search Axoltls images
   * 
   * @param App\Models\Bot $bot
   */
  public function Ajolote($bot)
  {
    $res = $this->Axoltl($bot);
    $this->Down($res['code'] != 200, $bot);
    $bot->Photo($res['response']->url, '<i>'.$res['response']->facts.'</i>');
  }

  /**
   * Send cat images
   *
   * @param App\Models\Bot $bot
   */
  public function Gato($bot)
  {
    $res = $this->Cat($bot);
    $this->Down($res['code'] != 200, $bot);
    $bot->Photo($res['response'][0]->url, '<i>'.@$res['response'][0]->breeds[0]->description.'</i>');
  }

  /**
   * Send dog images
   *
   * @param App\Models\Bot $bot
   */
  public function Perro($bot)
  {
    $res = $this->Dog($bot);
    $this->Down($res['code'] != 200, $bot);

    $txt = '';
    if (isset($res['response'][0]->breeds[0]->name)) {
      $breed = $res['response'][0]->breeds[0];
      $txt = '<i>~ '.$breed->name.'</i> ' . PHP_EOL . "Bred for: <i>".$breed->bred_for."</i>\nBreed group: <i>".$breed->breed_group."</i>\nLife span: <i>".$breed->life_span."</i>\nTemperament: <i>".$breed->temperament."</i>";
    }
    $bot->Photo($res['response'][0]->url, $txt);
  }

  /**
   * Send fox images
   *
   * @param App\Models\Bot $bot
   */
  public function Zorro($bot)
  {
    $res = $this->Fox($bot);
    $this->Down($res['code'] != 200, $bot);

    $bot->Photo($res['response']->image, $res['response']->link);
  }

  /**
   * Get info from github user
   *
   * @param App\Models\Bot $bot
   */
  public function Github($bot, string $user = '')
  {
    $this->Down(empty($user), $bot, 'Please put a github username' . PHP_EOL . 'Example: <code>/git username</code>');
    $git = $this->GithubUser($user); // Search user
    $this->Down(isset($git['message']), $bot, '<i>'.@$git['message'].' :(</i>');
    $repos = $git['repos'];

    $button = array();
    $button['inline_keyboard'][0][0] = ['text' => 'Github Profile', 'url' => $git['html_url']];
    // Build button
    if (count($repos) > 1) {
      for ($i=0; $i < count($repos) && $i < 11; $i++) { 
        $button['inline_keyboard'][$i][0] = ['text' => $repos[$i]['full_name'], 'url' => $repos[$i]['html_url']];
      }
    }

    foreach ($git as $key => $value) {
      $git[$key] = (empty($git[$key]) || $git[$key] == NULL) ? 'Null' : $value; 
    }

    $caption = '<b>Username:</b> <i>'.$git['login'].'</i>[<code>'.$git['id']."</code>]\n<b>Bio:</b> <i>".$git['bio']."</i>\n<b>Website:</b> ".$git['blog']."\n<b>Company:</b> ".$git['company']."\n<b>Twitter:</b> <i>".$git['twitter_username']."</i>\n<b>Repos/Gits: <code>".$git['public_repos']."/".$git['public_gists']."</code>\nFollowers: <code>".$git['followers']."</code>\nFollowing:</b> <code>".$git['following']."</code>";

    if ($git['avatar_url'] != 'Null') {
      // Send photo profile
      $bot->Document($git['avatar_url'], $caption, null, null, $button);
      exit;
    }
    $bot->SendMsg($caption, null, null, $button);
  }

  /**
   * Send ip info
   *
   * @param \App\Models\Bot $bot
   * @param string $ipstr IP address
   */
  public function getIp($bot, string $ipstr ='')
  {
    $this->Down(empty($ipstr), $bot, 'Please put a ip address' . PHP_EOL . 'Example: <code>/ip ip</code>');
    $this->Down(!filter_var($ipstr, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6), $bot, '<i>'.$ipstr.'</i> is not a valid ip address');

    $ip = $this->Ip($ipstr);
    $d = $ip['response'];
    $this->Down(isset($d->message), $bot, '<b>❌ ' . str_replace($ipstr, '<code>'.$ipstr.'</code>', @$d->message . ' :(') . '</b>');
    $th = $d->threat;
    $fdata = $d->two;
    
    $txt = "<b>✅ Valid ip ➜ <i>%s</i> %s\nCountry:</b> <i>%s / %s</i>\n<b>Org:</b> <i>%s</i>\n<b>Type:</b> <i>%s</i>\n<b>Time/Zone:</b> <i>%s</i>\n<b>Zip-code</b> <i>%s</i>\n<b>Threat:\n - Known attacker:</b> <i>%s</i>\n <b>- Known abuser:</b> <i>%s</i>\n <b>- Anonymous:</b> <i>%s</i>\n <b>- Is threat:</b> <i>%s</i>\n <b>- Bogon:</b> <i>%s</i>\n <b>- Proxy:</b> <i>%s</i>\n <b>- Tor:</b> <i>%s</i>";
    $txt = sprintf($txt, $ipstr, $d->emoji_flag, $d->country_name, $d->continent_name, @$d->asn->name, ucfirst(@$d->asn->type), @$fdata->timezone, @$fdata->postal, BoolString($th->is_known_attacker), BoolString($th->is_known_abuser), BoolString($th->is_anonymous), BoolString($th->is_threat), BoolString($th->is_bogon), BoolString($th->is_proxy), BoolString($th->is_tor));
    $loc = explode(',', $fdata->loc);

    $bot->SendMsg($txt);
    $bot->sendVenue($loc[0], $loc[1], 'IP location ➜' . $ipstr, $fdata->city.' - '.$fdata->region.' - '.$d->country_name.' - '.$d->continent_code);
  }

  /**
   * Get bin info using bins.su
   */
  public function Bin($bot, string $bin)
  {
    $this->Down(empty($bin), $bot, 'Please put a bin number' . PHP_EOL . 'Example: <code>/bin 510805</code>');
    $fim = new Bin;
    $fim->Validate($bin);
    $this->Down(!$fim->validate->ok, $bot, '<b>❌ ' . $fim->validate->msg . '</b> (<i>'.$fim->validate->bin.'</i>)');

    $res = $fim->BinsSu($fim->validate->bin);
    $this->Down(!$res['ok'], $bot, '<b>❌ ' . @$res['error'] . '</b> (<i>'.$res['bin'].'</i>)');

    $txt = b('Valid bin:').' '.u($res['bin']).n().b(i('Country:')).' '.$res['country'].' '.$res['flag'].n().b(i('Datas:')). ' ' . $res['vendor'].' - '.$res['type'].' - '.$res['level'].n().b(i('Bank:')).' '.$res['bank'];
    $bot->SendMsg($txt);
  }

  /**
   * Translate text using google or yandex translate
   * @param string      $use google or yandex
   * @param string|null $api_key Yandex api key
   */
  public function Translate($bot, $up, string $msg, string $use = 'google', ?string $api_key=null)
  {
    $tr = new Tr;
    $tr->ParseStr($up, $msg);
    
    $this->Down(empty($tr->txt), $bot, b('λ '.i('Translate Messages with '.ucfirst($use)).n().'Format: ').code('/tr lang_code Text'));

    try {
      $res = $tr->Get($use, $api_key);
    } catch (\Exception $e) {
      $bot->SendMsg(b(i($e->getMessage()))); return;
    }
    if ($res->error) {$bot->SendMsg(b(i($res->error_msg))); return;}

    $bot->SendMsg(b(i('Translate: ').$res->getLangName('input').' → '.$res->getLangName()).n().'~ '.$res->getText());
  }
}