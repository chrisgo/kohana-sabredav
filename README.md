kohana-sabredav
===============

Basic Kohana (3.3.1) module wrapping sabredav (1.8.7)

* This is a work-in-progress
* Only testing Webdav (for now) involving file transfer (using CyberDuck client)
  * Upload (PUT) - *.php files still problematic
  * Upload (POST) 
  * List (PROPFIND)
  * Rename (MOVE) 
  * Delete (DELETE)
* Use Kohana Auth as a Authentication plugin (enable in config/sabredav.php)
* Serve up one directory per user (appends username to root directory)
* TODO: Test CardDav, CalDAV, etc.
* TODO: Set up submodule for [fruux/sabre-dav](https://github.com/fruux/sabre-dav) (current this is checked in)
* TODO: Make stashing the Response HTTP Status into a plugin.  Currently, this is a **HACK** (edited Sabredav code) 
in Sabre\HTTP\Response class to stash the status code in a member variable so we can get it later from Kohana.  Also stashing the body the same way (not used)

This module does the following basic things:

* Creates an init.php for Kohana to load up the proper classes
* Creates a demo Route and Controller (not secure) 

Install
----

* Download or clone and put into the /modules folder

```
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
```

Configure
----

* Enable in bootstrap.php

```
...
Kohana::modules(array(
  ...
    'sabredav' => MODPATH.'kohana-sabredav',
    ...
  ));
  ...
```

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

Nginx Notes
----

A common optimization in Nginx is to cache static files using something like this

    location ~* \.(js|css|png|jpg|jpeg|gif|ico|woff)(\?ver=[0-9.]+)?$ {
        expires 30d;
    }

This will interfere with PUT (file upload) requests with those file extensions so you need to change it to specific folders only.  For example, all your static assets are under a folder called /media/

    location /media/ {
        location ~* \.(js|css|png|jpg|jpeg|gif|ico|woff)(\?ver=[0-9.]+)?$ {
            expires 30d;
        }
    }
    
Notes
----

* Had to create a Response class to include all the Webdav allowed HTTP Status codes
* Had to implement a Lock Plugin specific to Kohana due to the php://input issue (same with PUT)

    


  











