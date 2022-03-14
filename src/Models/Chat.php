<?php 

namespace App\Models;

/**
 * Get chat datas
 */
class Chat {
  
  private static $up;

  public function __construct($up)
  {
    self::$up = $up;
  }

  private static function SetUp($up=null)
  {
    self::$up = $up ?? self::$up;
  }

  /**
   * Get sender user id
   */
  public static function getUserId($up=null):string
  {
    self::SetUp($up);
    return self::$up->message->from->id
        ?? self::$up->callback_query->from->id
        ?? self::$up->inline_query->from->id
        ?? '';
  }

  /**
   * Return sender username
   */
  public static function getUser($up=null):string
  {
    self::SetUp($up);
    return self::$up->message->from->username
        ?? self::$up->callback_query->from->username
        ?? self::$up->inline_query->from->username
        ?? '';
  }
  
  /**
   * Get message chat id
   */
  public static function getChatId($up=null):string
  {
    self::SetUp($up);
    return self::$up->message->chat->id
        ?? self::$up->callback_query->message->chat->id
        ?? '';
  }

  /**
   * Get message id
   */
  public static function getMsgId($up=null):string
  {
    self::SetUp($up);
    return self::$up->message->message_id
        ?? self::$up->callback_query->message->message_id
        ?? '';
  }

  /**
   * Get chat type (private, group, supergroup, channel, etc)
   */
  public static function getChatType($up=null):string
  {
    self::SetUp($up);
    return self::$up->message->chat->type
        ?? self::$up->callback_query->message->chat->type
        ?? '';
  }

  /**
   * Get message text
   */
  public static function getTxt($up=null):string
  {
    self::SetUp($up);
    return self::$up->message->text
        ?? self::$up->callback_query->data
        ?? self::$up->inline_query->query
        ?? '';
  }

  public static function getContent(int $cut, ?string $txt=null)
  {
    $txt = $txt ?? self::getTxt();
    return substr(trim($txt), $cut);
  }

  /**
   * Get Inline query id for answer query
   */
  public static function getInlineId($up=null): string
  {
    self::SetUp($up);
    return self::$up->inline_query->id
      ?? '';
  }
}