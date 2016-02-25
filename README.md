## Overview

phpTourney is a PHP script collection consisting of user management,news, match reports and of course the tourney logic for single- and double-elimination brackets with or without qualification. It is released under the terms of the MIT license. Have a look at LICENSE.txt for details.

## Getting started

### Requirements
- Apache Web Server (Tested: 2.4.10)
- PHP (Tested: 5.6.14)
- MySQL Server (Tested: 5.5.46)
- Optional: ImageMagick (Tested: 6.8.9)

### Installation
- Extract the phpTourney archive to a directory on your webserver
- Make sure that the directories /data/screenshots and /data/serverlists and the file /VERSION are writable for the www-user
- Execute the SQL-script /sql/phptourney.sql (i.e. with phpMyAdmin, or the mysql command line utility)
- Copy the file /inc/config-default.inc.php to /inc/config.inc.php and edit its content to your needs
- Log in with admin/password and start a tournament

### Updating
- To do an update just extract the new version over the old installation (don't forget to make backups first)
- That's only applicable to minor or micro version updates. If the major version number changed, you have to make a fresh installation.
