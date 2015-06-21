<?php
return array(
    'id' =>             'auth:mail', # notrans
    'version' =>        '0.1',
    'name' =>           /* trans */ 'Mail Server Authentication',
    'author' =>         'Kevin O\'Connor',
    'description' =>    /* trans */ 'Provides a configurable authentication
        backend for authenticating staff and clients using IMAP or POP3.',
    'url' =>            'https://www.github.com/kevinoconnor7/osTicket-auth-mail',
    'plugin' =>         'authentication.php:MailAuthPlugin',
    'requires' => array(
        "ext-imap" => array(
            "version" => "*",
        ),
    ),
);
