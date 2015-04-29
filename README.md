# invoTick
System sales, billing and ticketing

Installation in Windows:
------------------------
Requirements: IIS, PHP, PDO Sqlite
Install IIS and PHP enabled PDO Sqlite.
itick.zip unzip the contents in a folder on your Web server, for example; c:\inetpub\wwwroot\itick.

Set permissions for read and write in the folder itick\invoTick\db\domains for all user files IIS_IUSRS.

You can now access the browser http: //localhost/itick/invoTick/index.php

Installation in Linux:
----------------------
Requirements: Apache, PHP, PDO Sqlite
Installation example;
- Sudo apt-get install apache2 php5 libapache2-mod-php5 php5-sqlite
- Sudo /etc/init.d/apache2 restart

itick.zip unzip the contents of a folder on your Web server, for example; /var/www/html/itick.

Set permissions for read and write in the directory invoTick/db recursive, for all user files;
chown -R www-data: www-data /var/www/html/itick/db
chmod -R 664 /var/www/html/itick/db

You can now access the browser http: //localhost/itick/invoTick/index.php
