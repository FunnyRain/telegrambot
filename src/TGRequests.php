<?php

class tgrequests {

  private $tgbot;

  private $method;

  public function __construct(tgbot $tgbot) {
    $this->tgbot = $tgbot;
  }

  public function getRequest(): array {
    $result = $this->getData();
    return $result;
  }

  public function getData(int $offset = 0): array {
    return $this->api('getUpdates', [
      'offset' => $offset,
      'limit' => 100,
      'timeout' => 0
    ]);
  }

  public function call(string $url) {
    $sendRequest = json_decode(
      (function_exists('curl_init')) ? $this->curl_post($url) : file_get_contents($url),
      true
    );

    if (isset($sendRequest['error_code'])) {
      $error_code = $sendRequest['error_code'];
      $error_description = $sendRequest['description'];
      $this->tgbot->getLog()->error('[#' . $error_code . ']: ' . $error_description, 'Method: ' . $this->method);
      if ($error_code == 409) die;
    }

    return $sendRequest;
  }

  public function api(string $method, array $params = []) {
    $this->method = $method;
    return $this->call($this->http_build_query($method, http_build_query($params)));
  }

  private function http_build_query(string $method, string $params = '') {
    return "https://api.telegram.org/bot" . $this->tgbot->token . "/" . $method . "?" . $params;
  }

  private function curl_post(string $url) {
    if (!function_exists('curl_init')) return false;
    $param = parse_url($url);
    if ($curl = curl_init()) {
      curl_setopt($curl, CURLOPT_URL, $param['scheme'] . '://' . $param['host'] . $param['path']);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_POST, true);
      curl_setopt($curl, CURLOPT_POSTFIELDS, @$param['query']);
      curl_setopt($curl, CURLOPT_TIMEOUT, 20);
      $out = curl_exec($curl);
      curl_close($curl);

      return $out;
    }

    return false;
  }
}
