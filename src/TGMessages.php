<?php

class tgmessages {

  private $tgbot;
  public $message;
  public $callback;

  public function __construct(tgbot $tgbot) {
    $this->tgbot = $tgbot;
  }

  public function get($object = []): string {
    if (!empty($this->callback)) return "";
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

  public function getCallback($object = []): string {
    if (!is_array($object)) {
      if (substr_count($object, '/') === 2) {
        return preg_match($object, (isset($this->callback['data'])) ? $this->callback['data'] : "");
      } else {
        if (isset($this->callback['data']) and $this->callback['data'] == $object)
          return true;
        else
          return false;
      }
    }

    if (empty($object)) $object = $this->callback;
    if (isset($object['data']))
      return $object['data'];
    else
      return "";
  }

  public function editMessageCallback(string $text = '', array $args = []) {
    if (!isset($text))
      return $this->bot->getLog()->warning('text is required to edit a message!');

    if ($this->tgbot->KeyboardBuilder()->isSendKeyboard === true) {
      $keyboard = ['reply_markup' => json_encode($this->tgbot->KeyboardBuilder()->keyboard, JSON_UNESCAPED_UNICODE)];
    } else $keyboard = [];

    $return = $this->tgbot->send()->api('editMessageText', [
      'chat_id' => $this->callback['from']['id'],
      'message_id' => $this->callback['message']['message_id'],
      'text' => $text,
    ] + $keyboard + $args);
    $this->tgbot->KeyboardBuilder()->isSendKeyboard = false;

    return $return;
  }

  public function getId(): int {
    return $this->message['message_id'];
  }

  public function getUserId(array $object = []): int {
    if (empty($object)) $object = $this->message;
    return $object['from']['id'];
  }

  public function isBot(array $object = []): bool {
    if (empty($object)) $object = $this->message;
    return $object['from']['is_bot'];
  }

  public function getFirstName(array $object = []): string {
    if (empty($object)) $object = $this->message;
    return $object['from']['first_name'];
  }

  public function getLastName(array $object = []): string {
    if (empty($object)) $object = $this->message;
    return $object['from']['last_name'];
  }

  public function getUsername(array $object = []): string {
    if (empty($object)) $object = $this->message;
    return $object['from']['username'];
  }

  public function getFullName(array $object = []): string {
    if (empty($object)) $object = $this->message;
    return $this->getFirstName($object) . ' ' . $this->getLastName($object);
  }

  public function getLanguageCode(array $object = []): string {
    if (empty($object)) $object = $this->message;
    return $object['from']['language_code'];
  }

  public function getChatId(array $object = []): int {
    if (empty($object)) $object = $this->message;
    return $object['chat']['id'];
  }

  public function getTypeChat(array $object = []): string {
    if (empty($object)) $object = $this->message;
    return $object['chat']['type'];
  }

  public function getDateMessage(array $object = []): int {
    if (empty($object)) $object = $this->message;
    return $object['date'];
  }

  public function reply(string $text, array $args = []) {
    if (!isset($text))
      return $this->bot->getLog()->warning('text is required to send a message!');

    if ($this->tgbot->KeyboardBuilder()->isSendKeyboard === true) {
      $keyboard = ['reply_markup' => json_encode($this->tgbot->KeyboardBuilder()->keyboard, JSON_UNESCAPED_UNICODE)];
    } else $keyboard = [];

    $return = $this->tgbot->send()->api('sendMessage', [
      'chat_id' => $this->getChatId(),
      'text' => $text,
    ] + $keyboard + $args);
    $this->tgbot->KeyboardBuilder()->isSendKeyboard = false;

    return $return;
  }

  public function setHandler(array $object = []): tgmessages {
    $this->message = (isset($object['message'])) ? $object['message'] : $object['callback_query']['message'];
    $this->callback = (isset($object['callback_query'])) ? $object['callback_query'] : [];
    return $this;
  }
}
