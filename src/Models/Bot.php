<?php 

namespace Mateodioev\Alchemist\Models;


/**
 * Bot methods
 */
class Bot extends BotCore {
  
  /**  Parameters */
  private array $payload = [];

  public function __construct(string $bot_token)
  {
    parent::__construct($bot_token);
  }

  public function SendAction(string $action, ?string $chat_id=null)
  {
    $this->payload = ['chat_id' => $chat_id ?? Chat::getChatId(), 'action' => $action];
    return $this->request('sendChatAction', $this->payload);
  }

  /**
   * Send message to user
   * @link https://core.telegram.org/bots/api#sendmessage
   */
  public function SendMsg(string $txt, ?string $chat_id=null, ?string $msg_id=null, $button = '', $parse_mode = 'HTML')
  {
    $this->payload = [
      'chat_id' => $chat_id ?? Chat::getChatId(),
      'reply_to_message_id' => $msg_id ?? Chat::getMsgId(),
      'text' => $txt,
      'parse_mode' => $parse_mode,
      'reply_markup' => json_encode($button),
    ];

    $this->SendAction('typing', $this->payload['chat_id']);

    return $this->request('sendMessage', $this->payload);
  }

  /**
   * Edit a message send from bot
   * @link https://core.telegram.org/bots/api#editmessagetext
   */
  public function EditMsg(string $txt, string $msg_id, ?string $chat_id=null, $button = '', $parse_mode = 'HTML')
  {
    $this->payload = [
      'chat_id' => $chat_id ?? Chat::getChatId(),
      'message_id' => $msg_id,
      'text' => $txt,
      'parse_mode' => $parse_mode,
      'reply_markup' => json_encode($button),
    ];

    return $this->request('editMessageText', $this->payload);
  }

  /**
   * Delete a message, including service messages
   * @link https://core.telegram.org/bots/api#deletemessage
   */
  public function DelMsg(string $chat_id, string $msg_id)
  {
    $this->payload = ['chat_id' => $chat_id, 'message_id' => $msg_id];
    return $this->request('deleteMessage', $this->payload);
  }

  /**
   * Send general files
   * @link https://core.telegram.org/bots/api#senddocument
   */
  public function Document(string|\CURLFile $document, ?string $caption=null, ?string $chat_id=null, ?string $msg_id=null, $button = '', $parse_mode = 'HTML')
  {
    if (file_exists($document)) $document = new \CURLFile(realpath($document));
    
    $this->payload = [
      'chat_id' => $chat_id ?? Chat::getChatId(),
      'reply_to_message_id' => $msg_id ?? Chat::getMsgId(),
      'caption' => $caption,
      'parse_mode' => $parse_mode,
      'reply_markup' => json_encode($button),
      'document' => $document,
    ];
    $this->SendAction('upload_document', $this->payload['chat_id']);
    return $this->request('sendDocument', $this->payload);
  }

  /**
   * Use this method to send photos. On success, the sent Message is returned.
   * @see https://core.telegram.org/bots/api#sendphoto
   */
  public function Photo(string|\CURLFile $document, ?string $caption=null, ?string $chat_id=null, ?string $msg_id=null, $button='', $parse_mode = 'HTML')
  {
    if (file_exists($document)) $document = new \CURLFile(realpath($document));

    $this->payload = [
      'chat_id' => $chat_id ?? Chat::getChatId(),
      'reply_to_message_id' => $msg_id ?? Chat::getMsgId(),
      'caption' => $caption,
      'parse_mode' => $parse_mode,
      'reply_markup' => json_encode($button),
      'photo' => $document,
      'allow_sending_without_reply' => true,
    ];
    $this->SendAction('upload_photo', $this->payload['chat_id']);
    return $this->request('sendPhoto', $this->payload);
  }
  
  /**
   * Use this method to send information about a venue.
   *
   * @param float $lat Latitude 
   * @param float $long Longitude 
   * @param string $title Name of the venue
   * @param string $addr Address of the venue
   * 
   * @see https://core.telegram.org/bots/api#sendvenue
   */
  public function sendVenue(float $lat, float $long, string $title, string $addr, ?string $chat_id=null, ?string $msg_id=null)
  {
    $this->payload = [
      'latitude' => $lat,
      'longitude' => $long,
      'title' => $title,
      'address' => $addr,
      'chat_id' => $chat_id ?? Chat::getChatId(),
      'reply_to_message_id' => $msg_id ?? Chat::getMsgId(),
    ];
    return $this->request('sendVenue', $this->payload);
  }
  
  /**
   * Returns basic information about the bot in form of a User object.
   * @link https://core.telegram.org/bots/api#getme
   */
  public function Me()
  {
    $this->payload = [];
    return $this->request('getMe', $this->payload);
  }

  /**
   * Use this method to get basic info about a file and prepare it for downloading.
   * @link https://core.telegram.org/bots/api#getfile
   */
  public function GetFile(string $file_id)
  {
    $this->payload = ['file_id' => $file_id];
    return $this->request('getFile', $this->payload);
  }

  /**
   * Use this method to send answers to an inline query. On success, True is returned. No more than 50 results per query are allowed.
   * @link https://core.telegram.org/bots/api#answerinlinequery
   * 
   * @param string $inline_query_id Unique identifier for the answered query
   * @param array $results A JSON-serialized array of results for the inline query
   */
  public function answerInlineQuery(?array $results=null, ?string $inline_query_id=null, ?int $cache_time=null, ?bool $is_personal=null)
  {
    if (is_array($results) && !is_null($results) && count($results) > 50) {
      throw new \Exception("No more than 50 results per query are allowed.");
    }
    $this->payload = [
      'inline_query_id' => $inline_query_id ?? Chat::getInlineId(),
      'results' => json_encode($results),
      'cache_time' => $cache_time,
      'is_personal' => $is_personal
    ];
    return $this->request('answerInlineQuery', $this->payload);
  }
}