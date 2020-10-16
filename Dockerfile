FROM php:7.4-apache
ADD https://raw.githubusercontent.com/mlocati/docker-php-extension-installer/master/install-php-extensions /usr/local/bin/
RUN chmod uga+x /usr/local/bin/install-php-extensions && sync
RUN apt-get update && apt-get install -y vim
RUN apt-get install --no-install-recommends --assume-yes --quiet ca-certificates msmtp libfreetype6-dev libjpeg-dev libpng-dev
RUN openssl req -x509 -nodes -days 365 \
    -subj  "/C=MA/ST=CASA/O=Camagru Inc/CN=camagru.app" \
     -newkey rsa:2048 -keyout /etc/ssl/private/apache-selfsigned.key \
     -out /etc/ssl/certs/apache-selfsigned.crt
ADD ssl-config/000-default.conf /etc/apache2/
ADD ssl-config/default-ssl.conf /etc/apache2/
ADD ssl-config/ssl-params.conf /etc/apache2/conf-available/ssl-params.conf
RUN sed -i -e "s/AllowOverride None/AllowOverride All/g" /etc/apache2/apache2.conf
RUN echo "LoadModule rewrite_module modules/mod_rewrite.so" >> /etc/apache2/apache2.conf
RUN cp /etc/apache2/000-default.conf /etc/apache2/default-ssl.conf /etc/apache2/sites-available 
RUN a2enmod ssl
RUN a2enmod headers
RUN a2ensite default-ssl
RUN a2enconf ssl-params
RUN a2enmod rewrite
RUN service apache2 restart
RUN docker-php-ext-configure gd --with-jpeg=/usr/include/ && docker-php-ext-install gd
RUN docker-php-ext-install pdo pdo_mysql
RUN echo "defaults" >> /etc/msmtprc
RUN echo "auth on" >> /etc/msmtprc
RUN echo "tls on" >> /etc/msmtprc
RUN echo "tls_trust_file /etc/ssl/certs/ca-certificates.crt" >> /etc/msmtprc
RUN echo "logfile ~/.msmtp.log" >> /etc/msmtprc
RUN echo "account gmail" >> /etc/msmtprc
RUN echo "host smtp.gmail.com" >> /etc/msmtprc
RUN echo "port 587" >> /etc/msmtprc
RUN echo "from camagru.application@gmail.com" >> /etc/msmtprc
RUN echo "user camagru.application@gmail.com" >> /etc/msmtprc
RUN echo "password trewq11221" >> /etc/msmtprc
RUN echo "account default : gmail" >> /etc/msmtprc
RUN chmod 600 /etc/msmtprc
RUN cp -p /etc/msmtprc /etc/.msmtp_php
RUN chown www-data:www-data /etc/.msmtp_php
RUN touch /var/log/msmtp.log
RUN chown www-data:www-data /var/log/msmtp.log

RUN mv /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini
RUN rm -rf /usr/local/etc/php/php.ini-development
RUN echo "sendmail_path = \"/usr/bin/msmtp -C /etc/.msmtp_php --logfile /var/log/msmtp.log -a gmail -t\"" >> /usr/local/etc/php/php.ini