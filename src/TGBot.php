<?php

class tgbot {

  public $token;
  public $object;

  public $_CLASS_tgrequests;
  public $_CLASS_tglogger;
  public $_CLASS_tgmessages;

  public function __construct(string $token) {
    $this->token = $token;
    $this->_CLASS_tgrequests = new tgrequests($this);
    $this->_CLASS_tglogger = new tglogger();
    $this->_CLASS_tgmessages = new tgmessages($this);
  }

  public function send() {
    return $this->_CLASS_tgrequests;
  }

  public function getLog() {
    return $this->_CLASS_tglogger;
  }

  public function getMessage() {
    return $this->_CLASS_tgmessages;
  }

  public function isValidateToken() {
    $testRequest = $this->send()->api('getMe', [])['result'];
    if ($testRequest['is_bot'] == 1)
      $this->getLog()->log('Bot launched successfully');
    else die($this->getLog()->error('Bot token required'));
  }

  public function listenAction($listen) {
    $this->isValidateToken();
    while ($data = $this->send()->getRequest()) {

      $updates = $data['result'];
      if (count($updates) == 0) continue;

      foreach ($updates as $key => $updates) {
        $this->send()->getData($updates['update_id'] + 1);
        $this->object = $updates;
        $listen($this->object);
      }
    }
  }
}
