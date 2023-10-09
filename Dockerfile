FROM php:8.2-apache

COPY . /var/www/html
WORKDIR /
EXPOSE 80
SHELL ["/bin/bash", "-c"]
RUN ln -s ../mods-available/{expires,headers,rewrite}.load /etc/apache2/mods-enabled/
RUN sed -e '/<Directory \/var\/www\/>/,/<\/Directory>/s/AllowOverride None/AllowOverride All/' -i /etc/apache2/apache2.conf

RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    && docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && apt-get -y update \
    && apt-get -y install git \
    && apt-get install -y libzip-dev \
    && docker-php-ext-install zip

RUN a2enmod rewrite

RUN docker-php-ext-install pdo pdo_mysql

RUN chmod -R 777 /var/www/html 
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Set timezone
ENV TZ=Europe/Bucharest
