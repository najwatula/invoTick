invoTick V. 0.2
=================
##(P.O.S) System sales, billing and ticketing
Multi-domain, multi-company, multi-selling, multi-user. System.  
With a single installation you can centrally control a network of dispersed online sales.  

Installation in Windows:
------------------------
Requirements: IIS, PHP, PDO Sqlite.  
Install IIS and PHP enabled PDO Sqlite.  
invoTick-master.zip unzip the contents in a folder on your Web server, for example; c:\inetpub\wwwroot\itick.  
Set permissions for read and write in the folder itick\invoTick\db\domains for all user files IIS_IUSRS.  

You can now access the browser http://servername/itick/invoTick/index.php  

Installation in Linux:
----------------------
Requirements: Apache, PHP, PDO Sqlite  
Installation example;
```
* sudo apt-get install apache2 php5 libapache2-mod-php5 php5-sqlite
* sudo /etc/init.d/apache2 restart
```
invoTick-master.zip unzip the contents of a folder on your Web server, for example; /var/www/html/itick.  
Set permissions for read and write in the directory invoTick/db recursive, for all user files;  
```
chown -R www-data: www-data /var/www/html/itick/db  
chmod -R 664 /var/www/html/itick/db  
```
You can now access the browser http://servername/itick/invoTick/index.php

System Access
-------------
By default access to the domain is established:  
User: invoTick\admin  
Password: admin  

Access to the control panel is:  
User: admin  
Password: admin

Important
---------
**default password is "admin".**  
The first time you access must change the password and access to the domain control panel.  
The definition of domain users set forth in the table after access agents.  
The password to access the control panel is in the file invoTick/login/mainPassword.php.  
should edit it and change the password.
