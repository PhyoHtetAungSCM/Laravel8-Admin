FROM php:8-fpm

RUN set -x \
    && php -r "copy('https://getcomposer.org/installer', '/tmp/composer-setup.php');" \
    && php -r "if (hash_file('SHA384', '/tmp/composer-setup.php') !== trim(file_get_contents('https://composer.github.io/installer.sig'))) { echo 'ERROR: Invalid installer signature' . PHP_EOL; unlink('/tmp/composer-setup.php'); exit(1); }" \
    && php /tmp/composer-setup.php --version=2.1.3 --install-dir=/usr/local/bin --filename=composer  \
    && rm -rf /tmp/composer-setup.php

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer
ENV PATH $PATH:/composer/vendor/bin
RUN apt-get update \
    && apt-get install -y \
    git \
    zip \
    libzip-dev \
    libpng-dev \
    unzip \
    vim \
    nano \
    # install nodejs and npm
    && curl -sL https://deb.nodesource.com/setup_15.x | bash - \
    && apt-get install -y nodejs \
    # install yarn
    && apt-get install -y yarn \
    && apt-get install -y libpq-dev \
    && apt-get -y autoremove \
    && apt-get clean \
    && docker-php-ext-install pdo_mysql pdo_pgsql fileinfo \
    && docker-php-ext-install zip \
    && docker-php-ext-install gd \
    && docker-php-ext-enable gd

WORKDIR /var/www/html