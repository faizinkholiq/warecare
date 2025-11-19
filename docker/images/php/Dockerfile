# php
FROM php:7.4-apache

# timezone
RUN echo "Asia/Jakarta" > /etc/timezone
RUN rm -f /etc/localtime
RUN dpkg-reconfigure -f noninteractive tzdata

# update repo
RUN apt-get update -y --fix-missing
RUN apt-get upgrade -y

# install important lib
RUN apt-get install -y \
    libpng-dev \
    unzip \
    zip \
    libzip-dev

# composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# install php extension
RUN docker-php-ext-install -j$(nproc) \
    mysqli \
    gd \
    zip

# enable some module
RUN a2enmod \
    proxy \
    proxy_http \
    proxy_http2 \
    proxy_wstunnel \
    headers \
    rewrite

# restart apache
RUN service apache2 restart

# hide debian detail
ARG DEBIAN_FRONTEND=noninteractive