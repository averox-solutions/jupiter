# !/bin/bash
# Copyright 2021 RTC Labs - All Rights Reserved
# Author: RTC Labs (Ishan Shah)
# You should have received a copy of JupiterMeet license with
# this file. If not, please write to: hello@jupiters.tech, or visit: jupiters.tech

# colors
NOCOLOR='\033[0m'
LIGHTRED='\033[1;31m'
LIGHTGREEN='\033[1;32m'
LIGHTBLUE='\033[1;34m'
LIGHTCYAN='\033[1;36m'
YELLOW='\033[1;33m'

# jupitermeet_pro installation for ubuntu 18.04
function ubuntu_18_04 {

    # welcome message
    echo ""
    echo -e "${LIGHTCYAN}WELCOME TO THE JUPITERMEET PRO INSTALLATION!${NOCOLOR}"
    echo ""
    echo "Please provide the following information to start the installation."
    echo ""

    # variables
    a=$(tr -dc 'A-Z0-9a-z!@#$%^&*()' </dev/urandom | head -c 30)
    b=$(tr -dc 'A-Z0-9a-z' </dev/urandom | head -c 10)
    c=$(tr -dc 'A-Z0-9a-z!@#$%^&*()' </dev/urandom | head -c 16)

    read -p "Your Domain: " domain
    read -p "Your Email: " email

    # update system
    export DEBIAN_FRONTEND=noninteractive

    apt update -y

    add-apt-repository ppa:deadsnakes/ppa -y

    apt update -y

    apt install python3.9 -y

    apt install expect -y

    upgrade_system=$(expect -c "

    set timeout 10

    spawn apt full-upgrade -y

    expect \"*** resolved (Y/I/N/O/D/Z) [default=N] ?\"
    send \"N\r\"

    expect eof
    ")

    echo "$upgrade_system"

    # install php7.3
    apt install software-properties-common -y

    add-apt-repository ppa:ondrej/php -y
    apt install php7.3 php7.3-mbstring php7.3-mysqli php7.3-curl php7.3-dom php7.3-xml php7.3-xmlwriter php7.3-common php7.3-json php7.3-zip php7.3-bcmath php7.3-gettext -y

    # install apache with certbot
    apt install python-certbot-apache -y

    # install mysql
    apt install mysql-server -y

    secure_mysql=$(expect -c "

    set timeout 10

    spawn mysql_secure_installation

    expect \"Would you like to setup VALIDATE PASSWORD plugin?\"
    send \"y\r\"

    expect \"Please enter 0 = LOW, 1 = MEDIUM and 2 = STRONG:\"
    send \"1\r\"

    expect \"New password:\"
    send \"$a\r\"

    expect \"Re-enter new password:\"
    send \"$a\r\"

    expect \"Do you wish to continue with the password provided?\"
    send \"y\r\"

    expect \"Remove anonymous users?\"
    send \"y\r\"

    expect \"Disallow root login remotely?\"
    send \"y\r\"

    expect \"Remove test database and access to it?\"
    send \"y\r\"

    expect \"Reload privilege tables now?\"
    send \"y\r\"

    expect eof
    ")

    echo "$secure_mysql"

    # install phpmyadmin
    echo "phpmyadmin phpmyadmin/dbconfig-install boolean false" | debconf-set-selections
    echo "phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2" | debconf-set-selections

    apt-get install phpmyadmin -y

    phpenmod mcrypt
    phpenmod mbstring

    ln -s /usr/share/phpmyadmin /var/www/html

    systemctl restart apache2

    # install nodejs and npm
    apt install curl -y

    curl -sL https://deb.nodesource.com/setup_16.x | bash -
    apt install nodejs -y
    npm i -g pm2

    # create database
    mysql -e "create database jupitermeet_pro;"

    mysql -e "alter database jupitermeet_pro character set utf8 collate utf8_general_ci;"

    mysql -e "use mysql; update user set authentication_string=null where User='root'; flush privileges;"
    mysql -e "use mysql; alter user 'root'@'localhost' identified with mysql_native_password BY '${a}'; flush privileges;"

    # configure apache
    touch /etc/apache2/sites-available/$domain.conf

    echo "<VirtualHost *:80>

ServerAdmin webmaster@localhost

ServerName $domain
ServerAlias www.$domain

DocumentRoot /var/www/html/jupitermeet_pro/public

<Directory /var/www/html>
    AllowOverride All
    Require all granted
</Directory>

</VirtualHost>" > /etc/apache2/sites-available/$domain.conf

    a2ensite $domain.conf
    a2dissite 000-default.conf

    a2enmod rewrite

    systemctl restart apache2

    # setting up ssl
    certbot --apache --non-interactive --agree-tos -d $domain -m $email

    head -n -1 /etc/apache2/sites-available/$domain.conf > /etc/apache2/sites-available/$domain.conf.temp
    mv /etc/apache2/sites-available/$domain.conf.temp /etc/apache2/sites-available/$domain.conf

    echo "Redirect permanent / https://$domain/

</VirtualHost>" >> /etc/apache2/sites-available/$domain.conf

    systemctl restart apache2

    # setting up installation folder
    mkdir /var/www/html/jupitermeet_pro
    chown $USER:www-data /var/www/html/jupitermeet_pro

    apt install unzip -y

    unzip jupitermeet_pro.zip -d /var/www/html/jupitermeet_pro

    cd /var/www/html/jupitermeet_pro

    chmod -R 775 .
    chown -R $USER:www-data .

    cd server
    npm i

    # installation log
    echo ""
    echo -e "${LIGHTGREEN}INSTALLATION HAS BEEN SUCCESSFULLY COMPLETED!${NOCOLOR}"
    echo ""
    echo -e "${LIGHTCYAN}Installation Log${NOCOLOR}"
    echo ""
    if dpkg -s php7.3 &> /dev/null
    then
    echo -e "PHP7.3    : ${LIGHTGREEN}Installed${NOCOLOR}"
    else
    echo -e "PHP7.3    : ${LIGHTRED}Not installed${NOCOLOR}"
    fi
    echo ""
    if dpkg -s apache2 &> /dev/null
    then
    echo -e "Apache    : ${LIGHTGREEN}Installed${NOCOLOR}"
    else
    echo -e "Apache    : ${LIGHTRED}Not installed${NOCOLOR}"
    fi
    echo ""
    if dpkg -s certbot &> /dev/null
    then
    echo -e "Certbot   : ${LIGHTGREEN}Installed${NOCOLOR}"
    else
    echo -e "Certbot   : ${LIGHTRED}Not installed${NOCOLOR}"
    fi
    echo ""
    if dpkg -s mysql-server &> /dev/null
    then
    echo -e "MySQL     : ${LIGHTGREEN}Installed${NOCOLOR}"
    else
    echo -e "MySQL     : ${LIGHTRED}Not installed${NOCOLOR}"
    fi
    echo ""
    if dpkg -s phpmyadmin &> /dev/null
    then
    echo -e "phpMyAdmin: ${LIGHTGREEN}Installed${NOCOLOR}"
    else
    echo -e "phpMyAdmin: ${LIGHTRED}Not installed${NOCOLOR}"
    fi
    echo ""
    if dpkg -s nodejs &> /dev/null
    then
    echo -e "NodeJS    : ${LIGHTGREEN}Installed${NOCOLOR}"
    else
    echo -e "NodeJS    : ${LIGHTRED}Not installed${NOCOLOR}"
    fi
    echo ""
    if npm -v &> /dev/null
    then
    echo -e "NPM       : ${LIGHTGREEN}Installed${NOCOLOR}"
    else
    echo -e "NPM       : ${LIGHTRED}Not installed${NOCOLOR}"
    fi

    # print paths and credentials
    echo ""
    echo -e "${LIGHTRED}IMPORTANT: ${NOCOLOR}Please copy and save following paths and credentials for your future reference."
    echo ""
    echo -e "${LIGHTCYAN}JupiterMeet${NOCOLOR}
URL     : ${YELLOW}$domain/install${NOCOLOR}"
    echo ""
    echo -e "${LIGHTCYAN}MySQL${NOCOLOR}
Database: ${YELLOW}jupitermeet_pro${NOCOLOR}
User    : ${YELLOW}root${NOCOLOR}
Password: ${YELLOW}$a${NOCOLOR}"
    echo ""
    echo -e "${LIGHTCYAN}phpMyAdmin${NOCOLOR}
URL     : ${YELLOW}$domain/phpmyadmin${NOCOLOR}
Username: ${YELLOW}root${NOCOLOR}
Password: ${YELLOW}$a${NOCOLOR}"
    echo ""
    echo -e "${LIGHTCYAN}SSL${NOCOLOR}
Cert    : ${YELLOW}/etc/letsencrypt/live/$domain/fullchain.pem${NOCOLOR}
Pkey    : ${YELLOW}/etc/letsencrypt/live/$domain/privkey.pem${NOCOLOR}"
    echo ""
}

# jupitermeet_pro installation for centos 7
function centos_7 {

    # welcome message
    echo ""
    echo -e "${LIGHTCYAN}WELCOME TO THE JUPITERMEET INSTALLATION!${NOCOLOR}"
    echo ""
    echo "Please provide the following information to start the installation."
    echo ""

    # variables
    a=$(tr -dc 'A-Z0-9a-z!@#$%^&*()' </dev/urandom | head -c 30)
    b=$(tr -dc 'A-Z0-9a-z' </dev/urandom | head -c 10)
    c=$(tr -dc 'A-Z0-9a-z!@#$%^&*()' </dev/urandom | head -c 16)

    read -p "Your Domain: " domain
    read -p "Your Email: " email

    # update system
    export DEBIAN_FRONTEND=noninteractive

    yum update -y
    
    yum install python3-pip -y

    #yum groupinstall "Development Tools"
    yum install centos-release-scl -y
    yum install devtoolset-8 -y
    source /opt/rh/devtoolset-8/enable

    yum install expect -y

    # install php7.3
    yum install https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm -y
    yum install http://rpms.remirepo.net/enterprise/remi-release-7.rpm -y

    yum install yum-utils -y

    yum-config-manager --enable remi-php73

    yum install php php-mbstring php-mysqli php-curl php-dom php-xml php-xmlwriter php-common php-json php-zip php-bcmath php-gettext -y

    # install apache with certbot
    yum install python-certbot-apache -y

    systemctl enable httpd.service

    systemctl start httpd.service

    # install mysql
    yum install mariadb-server mariadb -y

    systemctl enable mariadb.service

    systemctl start mariadb.service

    # install phpmyadmin
    yum install phpmyadmin -y

    # install nodejs and npm
    yum install curl -y

    curl -sL https://rpm.nodesource.com/setup_16.x | bash -

    yum install nodejs -y

    npm i -g pm2

    #yum install npm -y

    # create database
    mysql -e "create database jupitermeet_pro;"

    mysql -e "alter database jupitermeet_pro character set utf8 collate utf8_general_ci;"

    mysql -e "use mysql; update user set authentication_string=null where User='root'; flush privileges;"
    mysql -e "set password for 'root'@'localhost' = password('${a}'); flush privileges;"

    # configure apache
    mkdir /etc/httpd/sites-available
    mkdir /etc/httpd/sites-enabled

    echo "IncludeOptional /etc/httpd/sites-enabled/*.conf" >> /etc/httpd/conf/httpd.conf

    echo "<VirtualHost *:80>

    ServerName $domain
    ServerAlias www.$domain
    DocumentRoot /var/www/html/jupitermeet_pro/public

<Directory /var/www/html>
    AllowOverride All
    Require all granted
</Directory>

</VirtualHost>"> /etc/httpd/sites-available/$domain.conf

    sudo ln -s /etc/httpd/sites-available/$domain.conf /etc/httpd/sites-enabled/$domain.conf

    mv /etc/httpd/conf.d/welcome.conf /etc/httpd/conf.d/welcome.conf_backup

    systemctl restart httpd.service

    # configure phpmyadmin
    rm -rf /etc/httpd/conf.d/phpMyAdmin.conf

    echo "# phpMyAdmin - Web based MySQL browser written in php
#
# Allows only localhost by default
#
# But allowing phpMyAdmin to anyone other than localhost should be considered
# dangerous unless properly secured by SSL

Alias /phpMyAdmin /usr/share/phpMyAdmin
Alias /phpmyadmin /usr/share/phpMyAdmin

<Directory /usr/share/phpMyAdmin/>
   AddDefaultCharset UTF-8

   <IfModule mod_authz_core.c>
     # Apache 2.4
     <RequireAny>
       Require ip 127.0.0.1
       Require ip ::1
       Require all granted
     </RequireAny>
   </IfModule>
   <IfModule !mod_authz_core.c>
     # Apache 2.2
     Order Deny,Allow
     Deny from All
     Allow from 127.0.0.1
     Allow from ::1
   </IfModule>
</Directory>

<Directory /usr/share/phpMyAdmin/setup/>
   <IfModule mod_authz_core.c>
     # Apache 2.4
     <RequireAny>
       Require ip 127.0.0.1
       Require ip ::1
     </RequireAny>
   </IfModule>
   <IfModule !mod_authz_core.c>
     # Apache 2.2
     Order Deny,Allow
     Deny from All
     Allow from 127.0.0.1
     Allow from ::1
   </IfModule>
</Directory>

# These directories do not require access over HTTP - taken from the original
# phpMyAdmin upstream tarball
#
<Directory /usr/share/phpMyAdmin/libraries/>
   <IfModule mod_authz_core.c>
     # Apache 2.4
     Require all denied
   </IfModule>
   <IfModule !mod_authz_core.c>
     # Apache 2.2
     Order Deny,Allow
     Deny from All
     Allow from None
   </IfModule>
</Directory>

<Directory /usr/share/phpMyAdmin/setup/lib/>
   <IfModule mod_authz_core.c>
     # Apache 2.4
     Require all denied
   </IfModule>
   <IfModule !mod_authz_core.c>
     # Apache 2.2
     Order Deny,Allow
     Deny from All
     Allow from None
   </IfModule>
</Directory>

<Directory /usr/share/phpMyAdmin/setup/frames/>
   <IfModule mod_authz_core.c>
     # Apache 2.4
     Require all denied
   </IfModule>
   <IfModule !mod_authz_core.c>
     # Apache 2.2
     Order Deny,Allow
     Deny from All
     Allow from None
   </IfModule>
</Directory>

# This configuration prevents mod_security at phpMyAdmin directories from
# filtering SQL etc.  This may break your mod_security implementation.
#
#<IfModule mod_security.c>
#    <Directory /usr/share/phpMyAdmin/>
#        SecRuleInheritance Off
#    </Directory>
#</IfModule>" > /etc/httpd/conf.d/phpMyAdmin.conf

    ln -s /usr/share/phpMyAdmin /var/www/html

    systemctl restart httpd.service

    # setting up ssl
    certbot --apache --non-interactive --agree-tos -d $domain -m $email

    sudo ln -s /etc/httpd/sites-available/$domain-le-ssl.conf /etc/httpd/sites-enabled/$domain-le-ssl.conf

    systemctl stop firewalld

    systemctl restart httpd

    # renew ssl
    crontab -l | { cat; echo "0 0 */60 * * certbot renew >> /run/log/certbot-cron.log 2>&1"; } | crontab -

    # setting up installation folder
    mkdir /var/www/html/jupitermeet_pro
    chown $USER:apache /var/www/html/jupitermeet_pro

    yum install unzip -y

    unzip jupitermeet_pro.zip -d /var/www/html/jupitermeet_pro

    cd /var/www/html/jupitermeet_pro

    chmod -R 775 .
    chown -R $USER:apache .

    cd server
    npm i

    # installation log
    echo ""
    echo -e "${LIGHTGREEN}INSTALLATION HAS BEEN SUCCESSFULLY COMPLETED!${NOCOLOR}"
    echo ""
    echo -e "${LIGHTCYAN}Installation Log${NOCOLOR}"
    echo ""
    if rpm -q php &> /dev/null
    then
    echo -e "PHP7.3    : ${LIGHTGREEN}Installed${NOCOLOR}"
    else
    echo -e "PHP7.3    : ${LIGHTRED}Not installed${NOCOLOR}"
    fi
    echo ""
    if rpm -q httpd &> /dev/null
    then
    echo -e "Apache    : ${LIGHTGREEN}Installed${NOCOLOR}"
    else
    echo -e "Apache    : ${LIGHTRED}Not installed${NOCOLOR}"
    fi
    echo ""
    if rpm -q certbot &> /dev/null
    then
    echo -e "Certbot   : ${LIGHTGREEN}Installed${NOCOLOR}"
    else
    echo -e "Certbot   : ${LIGHTRED}Not installed${NOCOLOR}"
    fi
    echo ""
    if rpm -q mariadb &> /dev/null
    then
    echo -e "MySQL     : ${LIGHTGREEN}Installed${NOCOLOR}"
    else
    echo -e "MySQL     : ${LIGHTRED}Not installed${NOCOLOR}"
    fi
    echo ""
    if rpm -q phpMyAdmin &> /dev/null
    then
    echo -e "phpMyAdmin: ${LIGHTGREEN}Installed${NOCOLOR}"
    else
    echo -e "phpMyAdmin: ${LIGHTRED}Not installed${NOCOLOR}"
    fi
    echo ""
    if rpm -q nodejs &> /dev/null
    then
    echo -e "NodeJS    : ${LIGHTGREEN}Installed${NOCOLOR}"
    else
    echo -e "NodeJS    : ${LIGHTRED}Not installed${NOCOLOR}"
    fi
    echo ""
    if npm --version &> /dev/null
    then
    echo -e "NPM       : ${LIGHTGREEN}Installed${NOCOLOR}"
    else
    echo -e "NPM       : ${LIGHTRED}Not installed${NOCOLOR}"
    fi

    # print paths and credentials
    echo ""
    echo -e "${LIGHTRED}IMPORTANT: ${NOCOLOR}Please copy and save following paths and credentials for your future reference."
    echo ""
    echo -e "${LIGHTCYAN}JupiterMeet${NOCOLOR}
URL     : ${YELLOW}$domain/install${NOCOLOR}"
    echo ""
    echo -e "${LIGHTCYAN}MySQL${NOCOLOR}
Database: ${YELLOW}jupitermeet_pro${NOCOLOR}
User    : ${YELLOW}root${NOCOLOR}
Password: ${YELLOW}$a${NOCOLOR}"
    echo ""
    echo -e "${LIGHTCYAN}phpMyAdmin${NOCOLOR}
URL     : ${YELLOW}$domain/phpmyadmin${NOCOLOR}
Username: ${YELLOW}root${NOCOLOR}
Password: ${YELLOW}$a${NOCOLOR}"
    echo ""
    echo -e "${LIGHTCYAN}SSL${NOCOLOR}
Cert    : ${YELLOW}/etc/letsencrypt/live/$domain/fullchain.pem${NOCOLOR}
Pkey    : ${YELLOW}/etc/letsencrypt/live/$domain/privkey.pem${NOCOLOR}"
    echo ""
}

function print_error_01 {

    echo ""
            echo -e "${LIGHTRED}ERROR: ${NOCOLOR}exiting the installer..."
            echo ""
            echo -e "${YELLOW}NOTE: ${NOCOLOR}Please check the followings;

1) This script and the ${LIGHTRED}jupitermeet_pro.zip${NOCOLOR} file must be in the same directory
2) The zip file must be named as ${LIGHTRED}jupitermeet_pro.zip${NOCOLOR} (case sensitive)"
            echo ""

}

function print_error_02 {

     echo ""
        echo -e "${LIGHTRED}ERROR: ${NOCOLOR}exiting the installer..."
        echo ""
        echo -e "${YELLOW}NOTE: ${NOCOLOR}This automated installer currently supports only the following Linux versions;

> Ubuntu 18.04
> CentOS 7

To install JupiterMeet on other Linux versions, please continue with manual installation."
        echo ""

}

function clear {

    rm -rf jupitermeet_pro.zip
    rm -- "$0"

}

# check server os
if [ -f /etc/lsb-release ]; then

    release=$(lsb_release -r)
    vernum=$(cut -f2 <<< "$release")

    # check is it ubuntu 18.04
    if [ $vernum == 18.04 ]; then

        # check zip folder existence
        if [ -f jupitermeet_pro.zip ]; then

            # install jupitermeet_pro on ubuntu 18.04
            ubuntu_18_04

            # remove installation files
            clear

        else

            print_error_01

        fi

    else

        print_error_02

    fi

elif [ -f /etc/redhat-release ]; then

    vernum=$(rpm -E %{rhel})

    # check is it centos 7
    if [ $vernum == 7 ]; then

        # check zip folder existence
        if [ -f jupitermeet_pro.zip ]; then

            # install jupitermeet_pro on centos 7
            centos_7

            # remove installation files
            clear

        else

            print_error_01

        fi

    else

        print_error_02

    fi

else

    print_error_02

fi