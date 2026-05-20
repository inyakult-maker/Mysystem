FROM php:8.1-apache
WORKDIR /var/www/html
COPY . /var/www/html
RUN apt-get update && apt-get install -y libzip-dev zip unzip default-mysql-client git && docker-php-ext-install pdo_mysql zip
RUN chown -R www-data:www-data /var/www/html
EXPOSE 80
CMD ["apache2-foreground"]
