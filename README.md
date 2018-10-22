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

Building
========

Run `make`.

Local Dev Environment
=====================

Run `docker-compose up -d`. You should have osticket running on `localhost:8080`.

osTicket admin login is user `demo` password `hunter5`. The imap login is `admin@example.com` with password `hunter5!`.

Whenever you want to deploy the plugin run `make deploy-docker`.

The first time uou'll need to sign-in as an agent and add/configure the plugin. The imap server will be running on `imap:993` with SSL enabled.
