<?php 

namespace Mateodioev\Alchemist\Config;


class Utils {
    
  /**
   * Delete array key if is empty or null
   */
  public static function DeleteKeyEmpty(array &$arr)
  {
    if (!is_array($arr) && !is_object($arr)) return;

    foreach ($arr as $key => $item) {
      if (empty($arr[$key]) || $arr[$key] === null || $arr[$key] == '""' || $arr[$key] == "''") {
        unset($arr[$key]);
      }
    }
  }

  /**
   * MultiExplode
   */
  public static function MultiExplode(array $explodes, string $str):array
  {
    $str = str_replace($explodes, $explodes[0], $str);
    return explode($explodes[0], $str);
  }

  /**
   * Remove html entities
   */
  public static function QuitHtml(?string $str)
  {
    return @str_replace(
      ['<', '>', '≤', '≥'],
      ['&lt;', '&gt;', '&le;', '&ge;'],
      $str
    );
  }

  /**
   * Convertir un string a un array para que pueda ser llamado
   */
  public static function ExplodeCall(string $callable)
  {
    $callable = self::MultiExplode(['@', '->'], $callable);
    if (count($callable) == 2) {
      return [$callable[0], $callable[1]];
    }
    return implode('', $callable);
  }

  /**
   * Convertir un string a un array simple
   */
  public static function ToArray($var)
  {
    if (is_array($var)) return $var;

    return [$var];
  }

  /**
   * Valida una url y retorna la misma url, si es invalida lanza una excepción
   */
  public static function AddUrl(?string $url)
  {
    if (filter_var($url, FILTER_VALIDATE_URL) || is_null($url)) {
      return $url;
    }
    throw new \Exception("Invalid url: {$url}");
    
  }

  /**
   * Eliminar todos los caracteres no alfanuméricos y mantener los espacios
   */
  public static function RemoveNoAlpha(?string $str=null): ?string
  {
    if (is_null($str)) return null;
    return preg_replace('/[^a-zA-Z0-9\s]/', ' ', $str);
  }
}