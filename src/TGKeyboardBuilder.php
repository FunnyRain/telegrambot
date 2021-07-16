<?php

class tgkeyboardbuilder {

  public $tgbot;

  public $keyboard = [];
  public $buttons = [];
  public $isSendKeyboard = false;

  public function __construct(tgbot $tgbot) {
    $this->tgbot = $tgbot;
  }

  public function create(array $keyboard = []) {
    $this->buttons = [];
    foreach ($keyboard as $kfd => $kv) {
      $this->buttons[] = $kv;
    }
    $this->keyboard = ['resize_keyboard' => false, 'one_time_keyboard' => false, 'keyboard' => $this->buttons];
    return $this;
  }

  public function button(string $text = 'unknown button', string $callback_data = 'example', string $url = 'site.ru') {
    if ($callback_data !== 'example') {
      $addCallback = ['callback_data' => $callback_data];
    } else $addCallback = [];

    if ($url !== 'site.ru') {
      $addUrl = ['url' => $url];
    } else $addUrl = [];

    return ['text' => $text] + $addUrl + $addCallback;
  }

  public function remove() {
    $this->keyboard = ['remove_keyboard' => true];
    return $this;
  }

  public function sendKeyboardWithReply() {
    $this->isSendKeyboard = true;
    return $this;
  }

  public function resize() {
    $this->keyboard['resize_keyboard'] = true;
    return $this;
  }

  public function onetime() {
    $this->keyboard['one_time_keyboard'] = true;
    return $this;
  }

  public function inline() {
    $save_buttons = $this->keyboard['keyboard'];
    unset($this->keyboard['keyboard']);
    $this->keyboard['inline_keyboard'] = $save_buttons;
    return $this;
  }
}
