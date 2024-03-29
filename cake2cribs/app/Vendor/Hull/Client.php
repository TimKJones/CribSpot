<?php

require_once 'Connection.php';
require_once 'Cache.php';
require_once 'Event.php';

class Hull_Client {

  static $version = "0.1.0";
  static $dateFormat = "Y-m-d H:i:s O";

  public $debug = false;

  static $configKeys = array('host', 'appId', 'appSecret');

  static $defaultConfig = array('host' => 'api.hullapp.io', 'debug' => false);

  function Hull_Client($o_config=array()){

    $config = self::parseConfig($o_config['hull']);

    $this->config = $config;

    $this->host        = $config['host'];
    $this->appId       = $config['appId'];
    $this->appSecret   = $config['appSecret'];
    $this->userId      = false;
    if (isset($config['userId'])) {
      $this->userId      = $config['userId'];
    }

    $this->connection  = new Hull_Connection($config);

    if (isset($o_config['fb']) && $o_config['fb']['appId'] ) {
      $this->facebook = new Hull_Facebook($o_config['fb']);
    }

    if (isset($config['noHttpCache'])){
     $this->noHttpCache = $config['noHttpCache'];
    }

    if (isset($config['verbose'])){
      $this->verbose = (bool)$config['verbose'];
    } else {
      $this->verbose = false;
    }

    if (isset($config['debug']) && $config['debug']=='true') {
      $this->debug = (bool)$config['debug'];
      $this->debug_options = array();
    }
  }

  private static function decamelize($camel,$splitter="_") {
    $camel=preg_replace('/(?!^)[[:upper:]][[:lower:]]/', '$0', preg_replace('/(?!^)[[:upper:]]+/', $splitter.'$0', $camel));
    return strtolower($camel);
  }

  private static function parseConfig($config=array()) {

    foreach (self::$configKeys as $key) {
      $val = NULL;
      $envKey = "HULL_" . strtoupper(self::decamelize($key));

      if (isset($config[$key])) {
        $val = $config[$key];
      } elseif (getenv($envKey)) {
        $val = getenv($envKey);
      } elseif (isset(self::$defaultConfig[$key])) {
        $val = self::$defaultConfig[$key];
      }

      $config[$key] = $val;
    }
    return $config;
  }

  public function currentUserId() {
    $cookieName = 'hull_' . $this->appId;
    $rawCookie = isset($_COOKIE[$cookieName]) ? $_COOKIE[$cookieName] : false;

    if (!$rawCookie) {
      return;
    }
    $signedCookie = json_decode(base64_decode($rawCookie), true);
    $userId = $signedCookie['Hull-User-Id'];
    $sig    = explode(".", $signedCookie['Hull-User-Sig']);
    $time   = $sig[0];
    $signature = $sig[1];
    $data = $time . '-' . $userId;
    $check = hash_hmac("sha1", $data, $this->appSecret);
    if ($check == $signature) {
      return $userId;
    }
  }

  // View Helpers
  public function imageUrl($id, $size="small") {
    $url = $this->host . "/img/" . $id . "/" . $size;
    if (!preg_match('/^https?/', $url)) {
      $url = '//' . $url;
    }
    //Assets have their own subdomain
    return str_replace('//', '//assets.', $url);
  }

  public function userHash($userInfos) {
    if (!is_array($userInfos) || !isset($userInfos['email'])) {
      return false;
    }
    $message = base64_encode(json_encode($userInfos));
    $timestamp = time();
    $signature = hash_hmac("sha1", "$message $timestamp", $this->appSecret);
    return "$message $signature $timestamp";
  }

  public function getEvent() {
    return new Hull_Event(file_get_contents('php://input'), $this->appSecret, $this->appId);
  }

  // HTTP Plumbing...

  public function get($path, $params=array(), $options=array()) {
    return $this->_exec("GET", $path, $params, $options);
  }

  public function post($path, $data=array(), $options=array()) {
    return $this->_exec("POST", $path, $data, $options);
  }

  public function put($path, $data=array(), $options=array()) {
    return $this->_exec("PUT", $path, $data, $options);
  }

  public function delete($path, $data=array(), $options=array()) {
    return $this->_exec("DELETE", $path, $data, $options);
  }

  private function _exec($method, $path, $params=array(), $options=array()) {
    if (isset($options["headers"])) {
      $headers = $options['headers'];
    } else {
      $headers = array();
    }

    $res = $this->connection->exec(strtoupper($method), $path, $params, $headers);

    if (isset($options['raw'])) {
      return $res;
    } else {
      return $res['body'];
    }
  }
}
