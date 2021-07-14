<?php

require_once 'autoload.php';

$bot = new tgbot('183******25:AAHnJ****************J8gv9mJG8');

$bot->listenAction(function ($data) use ($bot) {
  $msg = $bot->getMessage()->setHandler($data);
  $bot->getLog()->log('From: ' . $msg->getFirstName() . ' - ' . $msg->get());

  if ($msg->get('/test/ui')) {
    $msg->reply('Message id: ' . $msg->getId());
    $msg->reply('Full name: ' . $msg->getFullName());
    $msg->reply('Username: @' . $msg->getUsername());
    $msg->reply('Is bot: ' . $msg->isBot());
    $msg->reply('language code: ' . $msg->getLanguageCode());
    $msg->reply('User id: ' . $msg->getUserId());
    $msg->reply('Type chat: ' . $msg->getTypeChat());
    $msg->reply('Date message: ' . $msg->getDateMessage());
  }
});
