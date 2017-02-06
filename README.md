<p align="center"><img src="/html/imgs/scheduleDisplay.png"></p>
<p align="center"><img src="/html/imgs/ScheduleEntryWindow.png"></p>
<p align="center"><img src="/html/imgs/schedulelist.png"></p>
<p align="center"><img src="/html/imgs/createuser.png"></p>
1. First we need to install the necessary packages 

	apt-get install apache2 libapache2-mod-php5 php5 php5-curl php5-intl php5-mcrypt php5-mysql php5-sqlite php5-xmlrpc php5-gd mysql-server mysql-client

2. Now proceed to configure Apache will use as working directory /var/www/CGScheduler

   first create the directory-  mkdir -p /var/www/CGScheduler

3.  Then create the file /etc/apache2/sites-available/scheduler with the following content. You must change the IP, domain through which they belong.

   
        <VirtualHost 192.168.10.10:82>
        DocumentRoot /var/www/CGScheduler/html
        ServerName CGScheduler
		    ServerAdmin localhost
        <Directory /var/www/CGScheduler/html/ >
                DirectoryIndex index.php
                AllowOverride all
                Order allow,deny
                allow from all
        </Directory></VirtualHost>
	
	here , 192.168.10.10 is pc/server ip address and 82 is listening port. To configure listening port ,
   
    open /etc/apache2/port.conf file and add :

    Listen 82 


4.  Then we disable the default site configured in apache, put a site we’ve added recently, stop that run the following commands


	a2dissite default

	a2ensite CGScheduler


5. We proceed to restart apache   

	/etc/init.d/apache2 restart
	
	if we need to start mysql
	
    /etc/init.d/mysql start	

6. Now provide this command :

   sudo chown -R www-data.www-data /var/www/CGScheduler/

7. Create a mysql database named "project" and import project.sql into project database.


	
	
	
