#!/bin/bash

apache_config_file="/etc/apache2/envvars"
apache_vhost_file="/etc/apache2/sites-available/vagrant_vhost.conf"
php_config_file="/etc/php/7.0/apache2/php.ini"
xdebug_config_file="/etc/php/7.0/mods-available/xdebug.ini"
mysql_config_file="/etc/mysql/my.cnf"
default_apache_index="/var/www/html/index.html"
project_web_root="src"

# This function is called at the very bottom of the file
main() {	
	update_go
	network_go
	tools_go
	apache_go
	mysql_go
	php_go
	repositories_go
	autoremove_go
}

repositories_go() {
	echo "	if [ -e /usr/local/bin/composer ]; then
    			/usr/local/bin/composer self-update
		else
    			curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
		fi

		# Reset home directory of vagrant user
		if ! grep -q 'cd /var/www' /home/vagrant/.profile; then
    			echo 'cd /var/www' >> /home/vagrant/.profile
		fi"

		echo "** [ZF] Run the following command to install dependencies, if you have not already:"
		echo "    vagrant ssh -c 'composer install'"
		echo "** [ZF] Visit http://localhost:8080 in your browser for to view the application **"
}

update_go() {
	# Update the server
	apt-get update
	# apt-get -y upgrade
}

autoremove_go() {
	apt-get -y autoremove
}

network_go() {
	IPADDR=$(/sbin/ifconfig eth0 | awk '/inet / { print $2 }' | sed 's/addr://')
	sed -i "s/^${IPADDR}.*//" /etc/hosts
	echo ${IPADDR} ubuntu.localhost >> /etc/hosts			# Just to quiet down some error messages
}

tools_go() {
	# Install basic tools
	apt-get -y install build-essential binutils-doc git
}

apache_go() {
	# Install Apache
	apt-get -y install apache2

	sed -i "s/^\(.*\)www-data/\1ubuntu/g" ${apache_config_file}
	chown -R ubuntu:ubuntu /var/log/apache2

	if [ ! -f "${apache_vhost_file}" ]; then
		cat << EOF > ${apache_vhost_file}
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /vagrant
    LogLevel debug

    ErrorLog /var/log/apache2/error.log
    CustomLog /var/log/apache2/access.log combined

    <Directory /vagrant>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
EOF
	fi

	a2dissite 000-default
	a2ensite vagrant_vhost

	a2enmod rewrite

	service apache2 reload
	update-rc.d apache2 enable
}

php_go() {
	apt-get install -y apache2 git curl php7.0 php7.0-bcmath php7.0-bz2 php7.0-cli php7.0-curl php7.0-intl php7.0-json php7.0-mbstring php7.0-opcache php7.0-soap php7.0-sqlite3 php7.0-xml php7.0-xsl php7.0-zip libapache2-mod-php7.0 php-pear
#php-xdebug 

	sed -i "s/display_startup_errors = Off/display_startup_errors = On/g" ${php_config_file}
	sed -i "s/display_errors = Off/display_errors = On/g" ${php_config_file}

	if [ ! -f "{$xdebug_config_file}" ]; then
		cat << EOF > ${xdebug_config_file}
zend_extension=xdebug.so
xdebug.remote_enable=1
xdebug.remote_connect_back=1
xdebug.remote_port=9000
xdebug.remote_host=10.0.2.2
EOF
	fi

	service apache2 reload

	# Install latest version of Composer globally
	if [ ! -f "/usr/local/bin/composer" ]; then
		curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
	fi

	# Install PHP Unit 4.8 globally
	if [ ! -f "/usr/local/bin/phpunit" ]; then
		curl -O -L https://phar.phpunit.de/phpunit-old.phar
		chmod +x phpunit-old.phar
		mv phpunit-old.phar /usr/local/bin/phpunit
	fi

		chmod 777 /var/lib/php/sessions
	cat << EOF >> /home/ubuntu/.bashrc
alias ll='ls -alFh'
alias la='ls -A'
alias l='ls -CF'
alias h='history'
alias cdw='cd /var/www/html'
alias mysql='mysql -u root -proot dashboard'
alias taillog='tail -f /var/log/apache2/error.log'
alias phpini='vim /etc/php/7.0/apache2/php.ini'
alias vilog='vim /var/log/apache2/error.log'
EOF

	source "/home/ubuntu/.bashrc"

}

mysql_go() {
	# Install MySQL
	echo "mysql-server mysql-server/root_password password root" | debconf-set-selections
	echo "mysql-server mysql-server/root_password_again password root" | debconf-set-selections
	apt-get -y install mysql-client mysql-server

	sed -i "s/bind-address\s*=\s*127.0.0.1/bind-address = 0.0.0.0/" ${mysql_config_file}

	# Allow root access from any host
	echo "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY 'root' WITH GRANT OPTION" | mysql -u root --password=root
	echo "GRANT PROXY ON ''@'' TO 'root'@'%' WITH GRANT OPTION" | mysql -u root --password=root

	if [ -d "/vagrant/data/sql" ]; then
		echo "Executing all SQL files in /data/sql folder ..."
		echo "-------------------------------------"
		for sql_file in /vagrant/data/sql/*.sql
		do
			echo "EXECUTING $sql_file..."
	  		time mysql -u root --password=root < $sql_file
	  		echo "FINISHED $sql_file"
	  		echo ""
		done
	fi

	service mysql restart
	update-rc.d apache2 enable
}

main
exit 0
