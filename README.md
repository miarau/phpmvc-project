Allt om universum
=================

This project is the final assignment in the phpmvc course held by Blekinge Institute of Technology (BTH). It is a discussion forum where you can join to discuss everything concerning universe. The forum is built with the [Anax-MVC](https://github.com/mosbth/Anax-MVC) framework, but with a modified name, MIAX. The language on the site is swedish but code is written in english.


Install Package
---------------
* Clone the project from GitHub or download it as a zipfile.
* Run composer and use the composer update command to install the dependencies and 'composer install --no-dev'. The composer could be installed from https://getcomposer.org/
* Go to the file app/config/config_mysql.php and enter your database connection details.
* Go to the file webroot/.htaccess and enter the base url in Rewrite to your own base URL.



License
------------------

This software is free software and carries a MIT license.



Use of external libraries
-----------------------------------

The following external modules are included and subject to its own license


### Modernizr
* Website: http://modernizr.com/
* Version: 2.6.2
* License: MIT license
* Path: included in `webroot/js/modernizr.js`



### PHP Markdown
* Website: http://michelf.ca/projects/php-markdown/
* Version: 1.4.0, November 29, 2013
* License: PHP Markdown Lib Copyright Â© 2004-2013 Michel Fortin http://michelf.ca/
* Path: included in `3pp/php-markdown`
