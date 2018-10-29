<?php

class MailAuth {
  private $config;
  private $actualConnectionStr;
  private $type;

  // hostname, port, server_type, use_ssl, verify_cert
  const CONNECTION_STR = "{%s:%s/%s%s%s}INBOX";

  function __construct($config, $type) {
    $this->type = $type;
    $this->config = $config;
    $ssl = $this->config->get('mail-use-ssl') ? "/ssl" : "";
    $verify_cert = "";

    if ($this->config->get('mail-use-ssl')
        && !$this->config->get('mail-verify-cert')) {
      $verify_cert = "/novalidate-cert";
    }

    $server_type = $this->config->get('mail-server-type')
        ? $this->config->get('mail-server-type') : "imap";

    $this->actualConnectionStr = sprintf(
        self::CONNECTION_STR,
        $this->config->get('mail-hostname'),
        $this->config->get('mail-port'),
        $server_type,
        $ssl,
        $verify_cert);
  }

  private function tryAuthentication($username, $password) {
    $mbox = @imap_open($this->actualConnectionStr, $username, $password);
    if(!$mbox) {
      return false;
    }
    imap_close($mbox);
    return true;
  }

  private function lookupOrCreate($username) {
    switch($this->type) {
      case 'staff':
        if (($user = StaffSession::lookup($username)) && $user->getId()) {
          if (!$user instanceof StaffSession) {
            // osTicket <= v1.9.7 or so
            $user = new StaffSession($user->getId());
          }
          return $user;
        }
        break;
      case 'client':
        $acct = ClientAccount::lookupByUsername($username);
        if ($acct && $acct->getID()) {
          return new ClientSession(new EndUser($acct->getUser()));
        }
        return new ClientCreateRequest(
            $this, $username, array(
              'email' => $username,
              'name' => $username,
            ));
        break;
    }
    return null;
  }

  function authenticate($username, $password) {
    if(!$password || !$this->tryAuthentication($username, $password)) {
      return null;
    }

    return $this->lookupOrCreate($username);
  }
}

class MailStaffAuthBackend extends StaffAuthenticationBackend {
  static $id = "mail";
  static $name = /* trans */ "Mail Authentication";

  private $config;
  private $mailauth;

  function __construct($config) {
    $this->config = $config;
    $this->mailauth = new MailAuth($config, 'staff');
  }

  function getName() {
    $config = $this->config;
    list($__, $_N) = $config::translate();
    return $__(static::$name);
  }

  function authenticate($username, $password = false, $errors = array()) {
    return $this->mailauth->authenticate($username, $password);
  }
}

class MailClientAuthBackend extends UserAuthenticationBackend {
  static $id = "mail.client";
  static $name = /* trans */ "Mail Authentication";

  private $config;
  private $mailauth;

  function __construct($config) {
    $this->config = $config;
    $this->mailauth = new MailAuth($config, 'client');
  }

  function getName() {
    $config = $this->config;
    list($__, $_N) = $config::translate();
    return $__(static::$name);
  }

  function authenticate($username, $password = false, $errors = array()) {
    $result = $this->mailauth->authenticate($username, $password);
    if ($result instanceof ClientCreateRequest) {
      $result->setBackend($this);
    }

    return $result;
  }
}
