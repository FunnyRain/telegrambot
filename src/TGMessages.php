<?php

class tgmessages {

  private $tgbot;
  public $message;

  public function __construct(tgbot $tgbot) {
    $this->tgbot = $tgbot;
  }

  public function get($object = []): string {
    if (!is_array($object)) {
      if (substr_count($object, '/') === 2) {
        return preg_match($object, (isset($this->message['text'])) ? $this->message['text'] : "");
      } else {
        if (isset($this->message['text']) and $this->message['text'] == $object)
          return true;
        else
          return false;
      }
    }

    if (empty($object)) $object = $this->message;
    if (isset($object['text']))
      return $object['text'];
    else
      return "";
  }

  public function getId() {
    return $this->message['message_id'];
  }

  public function getUserId(array $object = []) {
    if (empty($object)) $object = $this->message;
    return $object['from']['id'];
  }

  public function isBot(array $object = []) {
    if (empty($object)) $object = $this->message;
    return $object['from']['is_bot'];
  }

  public function getFirstName(array $object = []) {
    if (empty($object)) $object = $this->message;
    return $object['from']['first_name'];
  }

  public function getLastName(array $object = []) {
    if (empty($object)) $object = $this->message;
    return $object['from']['last_name'];
  }

  public function getUsername(array $object = []) {
    if (empty($object)) $object = $this->message;
    return $object['from']['username'];
  }

  public function getFullName(array $object = []) {
    if (empty($object)) $object = $this->message;
    return $this->getFirstName($object) . ' ' . $this->getLastName($object);
  }

  public function getLanguageCode(array $object = []) {
    if (empty($object)) $object = $this->message;
    return $object['from']['language_code'];
  }

  public function getChatId(array $object = []) {
    if (empty($object)) $object = $this->message;
    return $object['chat']['id'];
  }

  public function getTypeChat(array $object = []) {
    if (empty($object)) $object = $this->message;
    return $object['chat']['type'];
  }

  public function getDateMessage(array $object = []) {
    if (empty($object)) $object = $this->message;
    return $object['date'];
  }

  public function reply(string $text, array $args = []) {
    if (!isset($text))
      return $this->bot->getLog()->error('text is required to send a message!');

    $return = $this->tgbot->send()->api('sendMessage', [
      'chat_id' => $this->getChatId(),
      'text' => $text,
    ]);

    return $return;
  }

  public function setHandler(array $object = []) {
    $this->message = $object['message'];
    return $this;
  }
}
