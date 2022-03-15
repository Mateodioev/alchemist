<?php 

namespace Mateodioev\Alchemist\Plugins;

use Mateodioev\Request\Request;

class Apis
{
  const AXOLTL_API = 'https://axoltlapi.herokuapp.com/';
  const THECAT_API = 'https://api.thecatapi.com/v1/images/search';
  const THEDOG_API = 'https://api.thedogapi.com/v1/images/search';
  const THEFOX_API = 'https://randomfox.ca/floof/';
  const THEIP1_API = 'https://api.ipdata.co/';
  const THEIP2_API = 'https://ipinfo.io/';

  /**
   * Get Axoltl data from api
   */
  public function Axoltl()
  {
    $res = Request::get(self::AXOLTL_API);
    $res['response'] = json_decode($res['response']);
    return $res;
  }

  /**
   * Get random cat image
   */
  public function Cat()
  {
    $res = Request::get(self::THECAT_API);
    $res['response'] = json_decode($res['response']);
    return $res;
  }

  /**
   * Get random dog image
   */
  public function Dog()
  {
    $res = Request::get(self::THEDOG_API);
    $res['response'] = json_decode($res['response']);
    return $res;
  }

  /**
   * Random fox image
   */
  public function Fox()
  {
    $res = Request::get(self::THEFOX_API);
    $res['response'] = json_decode($res['response']);
    return $res;
  }

  /**
   * Get github user data and repos
   */
  public function GithubUser(string $user)
  {
    $res = GitHub::GetUser($user);
    if (!isset($res['message'])) {
      $res['repos'] = GitHub::GetRepos($user);
    }
    return $res;
  }
  
  /**
   * Search ip address
   */
  public function Ip(string $ip)
  { 
    $res = Request::Get(self::THEIP1_API . urlencode($ip) . '?api-key=' . $_ENV['IP1_TOKEN']);
    $res['response'] = json_decode($res['response']);

    if (!isset($res['response']->message) && $res['code'] == 200) {
      $res['response']->two = json_decode(Request::Get(self::THEIP2_API . urlencode($ip) . '?token=' . $_ENV['IP2_TOKEN'])['response']);
    }
    return $res; 
  }
}