<?php 

namespace App\Models;


/**
 * Bot methods
 */
class Bot extends BotCore {
  
  public function __construct(string $bot_token)
  {
    parent::__construct($bot_token);
  }

  public function SendAction(string $action, ?string $chat_id=null)
  {
    return $this->request('sendChatAction', [
      'chat_id' => $chat_id ?? Chat::getChatId(),
      'action' => $action
    ]);
  }

  /**
   * Send message to user
   * @link https://core.telegram.org/bots/api#sendmessage
   */
  public function SendMsg(string $txt, ?string $chat_id=null, ?string $msg_id=null, $button = '', $parse_mode = 'HTML')
  {
    $payload = [
      'chat_id' => $chat_id ?? Chat::getChatId(),
      'reply_to_message_id' => $msg_id ?? Chat::getMsgId(),
      'text' => $txt,
      'parse_mode' => $parse_mode,
      'reply_markup' => json_encode($button),
    ];

    $this->SendAction('typing', $payload['chat_id']);

    return $this->request('sendMessage', $payload);
  }

  /**
   * Edit a message send from bot
   * @link https://core.telegram.org/bots/api#editmessagetext
   */
  public function EditMsg(string $txt, string $msg_id, ?string $chat_id=null, $button = '', $parse_mode = 'HTML')
  {
    $payload = [
      'chat_id' => $chat_id ?? Chat::getChatId(),
      'message_id' => $msg_id,
      'text' => $txt,
      'parse_mode' => $parse_mode,
      'reply_markup' => json_encode($button),
    ];

    return $this->request('editMessageText', $payload);
  }

  /**
   * Delete a message, including service messages
   * @link https://core.telegram.org/bots/api#deletemessage
   */
  public function DelMsg(string $chat_id, string $msg_id)
  {
    return $this->request('deleteMessage', [
      'chat_id' => $chat_id,
      'message_id' => $msg_id
    ]);
  }

  /**
   * Send general files
   * @link https://core.telegram.org/bots/api#senddocument
   */
  public function Document(string|\CURLFile $document, ?string $caption=null, ?string $chat_id=null, ?string $msg_id=null, $button = '', $parse_mode = 'HTML')
  {
    if (file_exists($document)) $document = new \CURLFile(realpath($document));
    
    $payload = [
      'chat_id' => $chat_id ?? Chat::getChatId(),
      'reply_to_message_id' => $msg_id ?? Chat::getMsgId(),
      'caption' => $caption,
      'parse_mode' => $parse_mode,
      'reply_markup' => json_encode($button),
      'document' => $document,
    ];
    $this->SendAction('upload_document', $payload['chat_id']);
    return $this->request('sendDocument', $payload);
  }

  /**
   * Use this method to send photos. On success, the sent Message is returned.
   * @see https://core.telegram.org/bots/api#sendphoto
   */
  public function Photo(string|\CURLFile $document, ?string $caption=null, ?string $chat_id=null, ?string $msg_id=null, $button='', $parse_mode = 'HTML')
  {
    if (file_exists($document)) $document = new \CURLFile(realpath($document));

    $payload = [
      'chat_id' => $chat_id ?? Chat::getChatId(),
      'reply_to_message_id' => $msg_id ?? Chat::getMsgId(),
      'caption' => $caption,
      'parse_mode' => $parse_mode,
      'reply_markup' => json_encode($button),
      'photo' => $document,
      'allow_sending_without_reply' => true,
    ];
    $this->SendAction('upload_photo', $payload['chat_id']);
    return $this->request('sendPhoto', $payload);
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
    $payload = [
      'latitude' => $lat,
      'longitude' => $long,
      'title' => $title,
      'address' => $addr,
      'chat_id' => $chat_id ?? Chat::getChatId(),
      'reply_to_message_id' => $msg_id ?? Chat::getMsgId(),
    ];
    return $this->request('sendVenue', $payload);
  }
  
  /**
   * Returns basic information about the bot in form of a User object.
   * @link https://core.telegram.org/bots/api#getme
   */
  public function Me()
  {
    return $this->request('getMe');
  }

  /**
   * Use this method to get basic info about a file and prepare it for downloading.
   * @link https://core.telegram.org/bots/api#getfile
   */
  public function GetFile(string $file_id)
  {
    return $this->request('getFile', ['file_id' => $file_id]);
  }

}