<?php

require_once INCLUDE_DIR . 'class.plugin.php';

class MailPluginConfig extends PluginConfig {

    // Provide compatibility function for versions of osTicket prior to
    // translation support (v1.9.4)
    function translate() {
        if (!method_exists('Plugin', 'translate')) {
            return array(
                function($x) { return $x; },
                function($x, $y, $n) { return $n != 1 ? $y : $x; },
            );
        }
        return Plugin::translate('auth-mail');
    }

    function getOptions() {
        list($__, $_N) = self::translate();
        $modes = new ChoiceField(array(
            'label' => $__('Authentication'),
            'default' => "disabled",
            'choices' => array(
                'disabled' => $__('Disabled'),
                'staff' => $__('Agents Only'),
                'client' => $__('Clients Only'),
                'all' => $__('Agents and Clients'),
            ),
        ));
        return array(
            'mail' => new SectionBreakField(array(
                'label' => $__('Mail Server Authentication'),
            )),
            'mail-hostname' => new TextboxField(array(
                'label' => $__('Mail Server Hostname'),
                'configuration' => array('size'=>60, 'length'=>100),
            )),
            'mail-port' => new TextboxField(array(
                'label' => $__('Mail Server Port'),
                'configuration' => array('size'=>10, 'length'=>8),
            )),
            'mail-use-ssl' => new BooleanField(array(
                'label' => $__('Use SSL'),
                'configuration' => array(
                  'description' => $__("Use SSL to communicate with the server")
                ),
            )),
            'mail-verify-cert' => new BooleanField(array(
                'label' => $__('Verify SSL certificate'),
                'configuration' => array(
                  'description' => $__("Verify that the mail server's SSL
                      certificate is valid")
                ),
            )),
            'mail-server-type' => new ChoiceField(array(
              'label' => $__('Server Type'),
              'default' => 'imap',
              'choices' => array(
                  'imap' => $__('imap'),
                  'pop3' => $__('pop3'),
              ),
            )),
            'mail-enabled' => clone $modes,
        );
    }

    function pre_save(&$config, &$errors) {
      global $ost;
      list($__, $_N) = self::translate();

      if (!extension_loaded('imap')) {
        if ($ost) {
          $ost->setWarning($__('IMAP extension is not available'));
        }
        $errors['err'] = $__('IMAP extension is not available. Please
            install or enable the `php-imap` extension on your web
            server');
        return;
      }
      
      global $msg;
      if (!$errors) {
        $msg = $__('Mail Authentication configuration updated successfully');
      }

      return !$errors;
    }
}
