<?php

class tgrequests {

  private $tgbot;

  private $method;

  public $countRequests = 0;

  public function __construct(tgbot $tgbot) {
    $this->tgbot = $tgbot;
  }

  public function getRequest(): array {
    $result = $this->getData();
    return $result;
  }

  public function getData(int $offset = 0): array {
    sleep(0.5);
    try {
      $result = $this->api('getUpdates', [
        'offset' => $offset,
        'limit' => 100,
        'timeout' => 0
      ]);
      
      if (!is_array($result))
        while (true) {
          $result = $this->api('getUpdates', [
            'offset' => $offset,
            'limit' => 100,
            'timeout' => 0
          ]);
  
          if (is_array($result)) {
            return $result;
          }
        }
        return $result;
    } catch (tgexception $e) {
      throw $e;
    }
  }

  public function call(string $url) {
  
    if ($this->countRequests >= 25) {
      sleep(1.5);
      $this->countRequests = 0;
    } else $this->countRequests++;
    $this->tgbot->getLog()->debug('Count: ' . $this->countRequests);

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

  private function http_build_query(string $method, string $params = ''): string {
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
      curl_setopt($curl, CURLOPT_TIMEOUT, 10);
      curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
      $out = curl_exec($curl);

      if (curl_errno($curl)) {
        $this->tgbot->getLog()->debug('HTTP code:' . curl_getinfo($curl, CURLINFO_HTTP_CODE), 'Curl error: ' . curl_error($curl));
        curl_close($curl);
        $this->curl_post($url);
      } else {
        curl_close($curl);
      }

      return $out;
    }

    return false;
  }
}
