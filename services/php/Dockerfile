# ./docker/php/Dockerfile
FROM php:7.2-fpm

RUN docker-php-ext-install pdo_mysql

RUN pecl install apcu
#RUN pecl install xdebug

RUN apt-get update && \
    apt-get install -y \
    zlib1g-dev \
    sudo \
    iputils-ping \
    net-tools \
    zip \
    openssl

RUN docker-php-ext-install zip
RUN docker-php-ext-enable apcu

# Optimize symfony to not lagging
RUN echo "opcache.memory_consumption=256" >>/usr/local/etc/php/php.ini
RUN echo "opcache.max_accelerated_files=20000" >>/usr/local/etc/php/php.ini
RUN echo "opcache.validate_timestamps=0" >>/usr/local/etc/php/php.ini
RUN echo "realpath_cache_size=4096K" >>/usr/local/etc/php/php.ini
RUN echo "realpath_cache_ttl=600" >>/usr/local/etc/php/php.ini
RUN echo "memory_limit = 5600M" >>/usr/local/etc/php/php.ini


# Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" 
RUN php composer-setup.php --install-dir=/bin --filename=composer 
RUN php -r "unlink('composer-setup.php');"

WORKDIR /var/www/html

RUN echo 'alias ll="ls -l"' >> ~/.bashrc
# COPY src/ /var/www/html/

RUN PATH=$PATH:/var/www/html/vendor/bin:bin