kohana-sabredav
===============

Basic Kohana (3.3.1) module wrapping sabredav (1.8.7)

* This is a work-in-progress
* Only testing Webdav (for now) involving file transfer 
* TODO: Test CardDav, CalDAV, etc.
* TODO: Set up submodule for fruux/sabre-dav (current this is checked in)

This module does the following basic things:

* Creates an init.php for Kohana to load up the proper classes
* Creates a demo Route and Controller (not secure) 

Install
----

* Download or clone and put into the /modules folder

    `
    application/
    modules/
      ...
      kohana-sabredav/ (contents of this project)
        init.php
        classes/
        config/
        vendor/
          SabreDAV-1.8.7/ (contents of sabre-dav project above)
            bin/
            build.xml
            docs/
            examples/
            lib/
              Sabre/
                autoload.php
                CalDAV/
                CardDAV/
                DAV/
                DAVACL/
                HTTP/
            vendor/
              autoload.php
              composer/
              sabre/
                 vobject/
        views/
      ...
    system/
    `

Configure
----

* Enable in bootstrap.php

   `
   ...
   Kohana::modules(array(
     ...
     'sabredav' => MODPATH.'kohana-sabredav',
     ...
     ));
   `

* Copy /modules/kohana-sabredav/config/sabredav.php to /application/config/sabredav.php
* Change some of the settings to point to a suitable folder

Test
----

Per the Sabre documentation Getting Started (https://code.google.com/p/sabredav/wiki/GettingStarted#Client_Setup)
pull up in a browser **http://example.com/webdav** (demo Route and Controller) and you should see the error message per the documentation.

* You can then use Cyberduck Webdav client to test further

  * Anonymous login
  * Host: http://example.com
  * Path: /webdav
  











