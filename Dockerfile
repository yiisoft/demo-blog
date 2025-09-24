FROM php:8.4-cli

RUN apt -y update && apt -y upgrade

# Required tools
RUN apt -y install \
    unzip \
    libicu-dev

# PHP extensions
RUN docker-php-ext-install -j$(nproc) \
    intl

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Locale
ENV LC_ALL=C.UTF-8

# Xdebug
RUN pecl install xdebug-3.4.1 \
    && docker-php-ext-enable xdebug

# PHP configuration
RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

WORKDIR /app
