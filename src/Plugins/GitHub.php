<?php 

namespace App\Plugins;

use Mateodioev\Request\Request;

class GitHub
{
  const API = 'https://api.github.com/';

  /**
   * Get user info
   */
  public static function Endpoint(string $endpoint)
  {
    Request::Init(self::API . $endpoint);
    Request::addHeaders([
      "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
      "Accept-Language: es-ES,es;q=0.8,en-US;q=0.5,en;q=0.3",
      "Host: api.github.com",
      "User-Agent:Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:85.0) Gecko/20100101 Firefox/85.0"
    ]);
    
    return Request::Run();
  }

  public static function Send(string $endpoint): array
  {
    $res = self::Endpoint($endpoint);
    return json_decode($res['response'], true);
  }
  /**
   * Get Github user info
   */
  public static function GetUser(string $user): array
  {
    return self::Send('users/' . urlencode($user));
  }

  /**
   * Get Github user public repositories
   */
  public static function GetRepos(string $user)
  {
    return self::Send('users/' . urlencode($user) . '/repos');
  }
}