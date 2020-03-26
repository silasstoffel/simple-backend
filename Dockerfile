FROM php:7.4-apache
RUN docker-php-ext-install pdo_mysql
RUN a2enmod rewrite
RUN a2enmod headers
RUN service apache2 restart
RUN chown -R www-data:www-data /var/www/html
RUN usermod -u 1000 www-data
RUN groupmod -g 1000 www-data
WORKDIR /var/www/html
