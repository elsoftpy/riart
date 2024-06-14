FROM php:5.6-fpm-alpine

RUN apk add && apk update && apk add composer gcc make g++ zlib-dev libmcrypt-dev \
    mysql-client imagemagick imagemagick-dev autoconf \
    libpng libpng-dev libjpeg-turbo libmcrypt-dev freetype  \
    libgcrypt-dev libxml2-dev libxslt-dev \
    jpeg-dev libjpeg libjpeg-turbo-dev libpng-dev freetype-dev \
    && pecl channel-update pecl.php.net && pecl install imagick \
    && docker-php-ext-enable imagick \
    && docker-php-ext-install mcrypt pdo pdo_mysql 

RUN docker-php-ext-install mbstring

RUN docker-php-ext-install gd

RUN docker-php-ext-install zip

RUN docker-php-ext-install xml

RUN docker-php-ext-install tokenizer
    
RUN chown -R www-data:www-data /var/www