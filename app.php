<?php

require_once 'autoload.php';

$bot = new tgbot('1835:ly6mJ8gv9mJG8');

$bot->listenAction(function ($data) use ($bot) {
  $msg = $bot->getMessage()->setHandler($data);
  $kb = $bot->KeyboardBuilder();
  $bot->getLog()->log('From: ' . $msg->getFirstName() . ' - ' . $msg->get());

  $menu = [
      $kb->button('ананас', 'ananas'),
      $kb->button('арбуз', 'arbyz'),
      $kb->button('тыква', 'tikva')
    ]
  ;

  if ($msg->get('/start')) {
    $kb->create([$menu])->inline()->sendKeyboardWithReply();
    $msg->reply('привет нажми любую кнопку');
  }

  if ($msg->getCallback('ananas')) {
    $kb->create([$menu, [$kb->button('назад', 'menu')]])->inline()->sendKeyboardWithReply();
    $msg->editMessageCallback('ты выбрал ананас');

  } elseif ($msg->getCallback('arbyz')) {
    $kb->create([$menu, [$kb->button('назад', 'menu')]])->inline()->sendKeyboardWithReply();
    $msg->editMessageCallback('ты выбрал арбуз');

  } elseif ($msg->getCallback('tikva')) {
    $kb->create([$menu, [$kb->button('назад', 'menu')]])->inline()->sendKeyboardWithReply();
    $msg->editMessageCallback('ты выбрал тыква');

  } elseif ($msg->getCallback('menu')) {
    $kb->create([$menu])->inline()->sendKeyboardWithReply();
    $msg->editMessageCallback('вы вернулись в меню');

  } 
});
