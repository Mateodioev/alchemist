<?php

namespace App\Models;

use App\Config\{Utils, UUID};

/**
 * Crate objects from inline results
 */
class BotCoreInline {
  
  private array $temp = [];
  private array $opt = [];

  /**
   * Utils::DeleteKeyEmpty($datas);
   */
  private function DeleteEmpty(array $arr): array
  {
    Utils::DeleteKeyEmpty($arr);
    return $arr;
  }

  /**
   * Represents the content of a text message to be sent as the result of an inline query.
   * @link https://core.telegram.org/bots/api#inputmessagecontent
   */
  public function InputMessageContent(string $msg, string $parse_mode = 'HTML', ?array $entities=null, bool $web_page_preview = true): array
  {
    return $this->DeleteEmpty([
      'message_text' => $msg,
      'parse_mode' => $parse_mode,
      'entities' => $entities,
      'disable_web_page_preview' => !$web_page_preview,
    ]);
  }

  /**
   * Gen uuid
   */
  public function GenId()
  {
    return UUID::v5(UUID::v4(), UUID::v4());
  }

  /**
   * Represents a link to an article or web page.
   * @link https://core.telegram.org/bots/api#inlinequeryresultarticle
   * @param string $title Title of the result
   * @param array $message input_message_content - Content of the message to be sent
   * @param string|null $desc Optional. Description of the result
   * @param string|null $thumb Optional. Url of the thumbnail for the result
   * @param string|null $url Optional. URL of the result
   * @param boolean $hide_url Optional. Pass True, if you don't want the URL to be shown in the message
   * @param string|null $id Unique identifier for this result, 1-64 Bytes
   */
  public function Article(string $title, array $message, string $desc=null, string $thumb=null, string $url=null, bool $hide_url = true, string $id=null): array
  {
    $this->temp = $this->DeleteEmpty([
      'type' => 'article',
      'id' => $id ?? $this->GenId(),
      'title' => $title,
      'input_message_content' => $message,
      'thumb_url' => Utils::AddUrl($thumb),
      'description' => $desc,
      'url' => Utils::AddUrl($url),
      'hide_url' => $hide_url,
    ]);
    return $this->AddOpt();
  }

  /**
   * Represents a link to a photo. By default, this photo will be sent by the user with optional caption. Alternatively, you can use input_message_content to send a message with the specified content instead of the photo.
   * @link https://core.telegram.org/bots/api#inlinequeryresultphoto
   * @param string $photo A valid URL of the photo. Photo must be in JPEG format. Photo size must not exceed 5MB
   * @param string $thumb URL of the thumbnail for the photo
   * @param string|null $title Optional. Title for the result
   * @param string|null $desc Optional. Short description of the result
   * @param string|null $caption Optional. Caption of the photo to be sent, 0-1024 characters after entities parsing
   */
  public function Photo(string $photo, string $thumb, string $title=null, string $desc=null, string $caption=null, ?string $id=null): array
  {
    $this->temp = $this->DeleteEmpty([
      'type' => 'photo',
      'id' => $id ?? $this->GenId(),
      'photo_url' => Utils::AddUrl($photo),
      'thumb_url' => Utils::AddUrl($thumb),
      'title' => $title,
      'description' => $desc,
      'caption' => $caption,
    ]);
    return $this->AddOpt($this->opt);
  }

  /**
   * Represents a link to an animated GIF file. By default, this animated GIF file will be sent by the user with optional caption. Alternatively, you can use input_message_content to send a message with the specified content instead of the animation
   * @link https://core.telegram.org/bots/api#inlinequeryresultgif
   * @return array
   */
  public function Gif(string $gif, string $thumb, string $title, ?string $id=null): array
  {
    $this->temp = $this->DeleteEmpty([
      'type' => 'gif',
      'id' => $id ?? $this->GenId(),
      'gif_url' => Utils::AddUrl($gif),
      'thumb_url' => Utils::AddUrl($thumb),
      'title' => $title,
    ]);
    return $this->AddOpt($this->opt);
  }

   /**
    * Represents a link to a page containing an embedded video player or a video file. By default, this video file will be sent by the user with an optional caption. Alternatively, you can use input_message_content to send a message with the specified content instead of the video.
    * @link https://core.telegram.org/bots/api#inlinequeryresultvideo
    * @param string $video A valid URL for the embedded video player or video file
    * @param string $thumb URL of the thumbnail (JPEG only) for the video
    * @param string $title Title for the result
    * @param string $desc Optional. Short description of the result
    * @param string|null $mime Mime type of the content of video url, "text/html" or "video/mp4"
    */
  public function Video(string $video, string $thumb, string $title, string $desc, string $mime=null, ?string $id=null): array
  {
    $this->temp = $this->DeleteEmpty([
      'type' => 'video',
      'id' => $id ?? $this->GenId(),
      'video_url' => Utils::AddUrl($video),
      'mime_type' => $mime ?? 'video/mp4',
      'thumb_url' => Utils::AddUrl($thumb),
      'title' => $title,
      'description' => $desc
    ]);
    return $this->AddOpt($this->opt);
  }

  /**
   * Represents a link to an MP3 audio file. By default, this audio file will be sent by the user. Alternatively, you can use input_message_content to send a message with the specified content instead of the audio.
   * @link https://core.telegram.org/bots/api#inlinequeryresultaudio
   * @param string $audio A valid URL for the audio file
   * @param string $title Title
   * @param string|null $caption Optional. Caption, 0-1024 characters after entities parsing
   * @param string|null $duration Optional. Audio duration in seconds
   */
  public function Audio(string $audio, string $title, string $caption=null, string $duration=null, ?string $id=null): array
  {
    $this->temp = $this->DeleteEmpty([
      'type' => 'audio',
      'id' => $id ?? $this->GenId(),
      'audio_url' => Utils::AddUrl($audio),
      'title' => $title,
      'caption' => $caption,
      'audio_duration' => $duration
    ]);
    return $this->AddOpt($this->opt);
  }

  /**
   * Represents a link to a voice recording in an .OGG container encoded with OPUS. By default, this voice recording will be sent by the user. Alternatively, you can use input_message_content to send a message with the specified content instead of the the voice message.
   * @link https://core.telegram.org/bots/api#inlinequeryresultvoice
   * @param string $voice A valid URL for the voice recording
   * @param string $title Recording title
   * @param string|null $caption Optional. Caption, 0-1024 characters after entities parsing
   * @param string|null $duration Optional. Recording duration in seconds
   */
  public function Voice(string $voice, string $title, string $caption=null, string $duration=null, ?string $id=null): array
  {
    $this->temp = $this->DeleteEmpty([
      'type' => 'voice',
      'id' => $id ?? $this->GenId(),
      'voice_url' => Utils::AddUrl($voice),
      'title' => $title,
      'caption' => $caption,
      'voice_duration' => $duration
    ]);
    return $this->AddOpt($this->opt);
  }

  /**
   * Add options to last method
   */
  public function AddOpt(array $opt = [], bool $clear_last = true): array
  {
    $this->opt = $opt;
    $this->temp = array_merge($this->temp, $this->opt);
    if ($clear_last) $this->opt = [];
    return $this->DeleteEmpty($this->temp);
  }

}