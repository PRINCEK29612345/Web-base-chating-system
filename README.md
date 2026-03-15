# Web-base-chating-system
Web based chatting system runs on a local apache server and can access it by locally connected PCs.

Installation on Linux

Install and configure database (mysql)

Installation Steps:

Open your terminal and run the following command to update your local package list: >> sudo apt update
Install MySQL server:  >> sudo apt install mysql-server
The MySQL service starts automatically after installation. You can verify its status with: >> sudo systemctl status mysql.service

Configuration Steps:

Access the MySQL shell as the root user: >> sudo mysql
In the MySQL prompt, run: ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'yourpswd';

Exit the MySQL prompt: >> exit;

Run the security script: >> sudo mysql_secure_installation

This script removes insecure defaults and sets up essential security options.

Create a new dedicated user : mysql -u root -p (enter the password used above)
In the MySQL prompt, run:

CREATE USER 'super'@'localhost' IDENTIFIED BY 'jamesBond@07';
GRANT ALL PRIVILEGES ON *.* TO 'super'@'localhost' WITH GRANT OPTION;
FLUSH PRIVILEGES;
exit;

If you want you can choose your own username and password but you need to change it in the source files.

You can now log in with your new user: >> mysql -u sammy -p

mysql -u super -p

In the MySQL prompt, run:

CREATE DATABASE RECORD;
USE RECORD;
CREATE TABLE USERS(ID INT,Uname VARCHAR(100),DOB VARCHAR(20),PSSWD VARCHAR(20),DP VARCHAR(50));
exit;

It is strongly recomented to follow above structure.You can change it after understanding the source codes.

Install and configure apache2

Install MySQL server :  >> sudo apt install apache2
Verify the Installation : >> sudo systemctl status apache2
Alluw firefall : >> sudo ufw allow 'Apache Full'

Install and setup PHP

Install PHP and Common Modules : >> sudo apt install php libapache2-mod-php php-mysql

Now copy all php files to /var/www/html/ by running >> sudo cp *.php /var/www/html/  (open terminal on the cloned folder)

Now its almost done. Open any browser and type the url (localhost/Register.php)
It will open the Registeraion page now you can create user accounts. You should create an account with user name as 'GROUP' where you can do the group chats. (It is necessory)

After clicking 'Register' you will redirect to the login page where you can login using the username and password.
After clicking login you will redirect to the chat page ui.Where you can see your name and 'GROUP' as users.

Now explore the UI.






 
