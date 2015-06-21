<?php

require_once(INCLUDE_DIR.'class.plugin.php');
require_once('config.php');

class MailAuthPlugin extends Plugin {
    var $config_class = "MailPluginConfig";

    function bootstrap() {
      global $ost;
      $config = $this->getConfig();

      // Don't initialize the plugin if the IMAP extension is not available.
      if (!extension_loaded('imap')) {
        if ($ost) {
          $ost->setWarning($this->__('IMAP extension is not available'));
        }
        return;
      }

      $enabled = $config->get('mail-enabled');
      if (in_array($enabled, array('all', 'staff'))) {
        require_once('mailauth.php');
        StaffAuthenticationBackend::register(
            new MailStaffAuthBackend($this->getConfig()));
      }
      if (in_array($enabled, array('all', 'client'))) {
        require_once('mailauth.php');
        UserAuthenticationBackend::register(
            new MailClientAuthBackend($this->getConfig()));
      }
    }

    function enable() {
      global $ost;

      if (!extension_loaded('imap')) {
        if ($ost) {
          $ost->setWarning(
              $this->__('Plugin not enabled: IMAP extension is not available'));
        }

        return false;
      }

      return parent::enable();
    }
}
