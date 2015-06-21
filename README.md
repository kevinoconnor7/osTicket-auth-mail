Mail Server Authentication for osTicket
=====================================

Provides mail server (IMAP/POP3) authentication for agents and/or clients on osTicket.

Features
========
 - IMAP/POP3 server support.
 - SSL connection support.
 - Login for both agents and clients (can be toggled for neither, either, or both).

Installing
==========

### Prebuilt

Download the auth-mail.phar from the [latest release](https://github.com/kevinoconnor7/osTicket-auth-mail/releases/latest)
and put it in your `includes/plugins` folder. From the admin panel go to
*Manage* --> *Plugins* --> *Add New Plugin* and select the plugin.

### From source

Follow the instructions to install [core-plugins](https://github.com/osTicket/core-plugins)
and then clone this repo into your `includes/plugns` folder. Then run
`php make.php hydrate` again.

Building
========

Make sure you have `make.php` from [core-plugins](https://github.com/osTicket/core-plugins)
and run `php make.php build auth-mail` to generate a phar package. This requires
that you have `phar.readonly = Off` in your php.ini file.
