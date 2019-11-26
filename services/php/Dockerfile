# ./docker/php/Dockerfile
FROM php:7.2-fpm

RUN docker-php-ext-install pdo_mysql

RUN pecl install apcu

RUN apt-get update && \
    apt-get install -y \
    zlib1g-dev \
    sudo \
    iputils-ping \
    net-tools

RUN docker-php-ext-install zip
RUN docker-php-ext-enable apcu

# Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" 
RUN php composer-setup.php --install-dir=/bin --filename=composer 
RUN php -r "unlink('composer-setup.php');"

WORKDIR /var/www/html

RUN echo 'alias ll="ls -l"' >> ~/.bashrc
# COPY src/ /var/www/html/

RUN PATH=$PATH:/var/www/html/vendor/bin:bin