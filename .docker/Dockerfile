# Use alpine to keep the image small
FROM php:7.4-fpm-alpine

# unzip is required for composer install
#
RUN apk update && apk add unzip git
# remove the apt cache (can save MBs on image size)
#    && rm -rf /var/lib/apt/lists/*

# All Laravel requirements (except bcmath and pdo) are met
# by the above image, and bcmath doesn't seem to be necessary...
RUN docker-php-ext-install pdo_mysql

# Xdebug (comment out if necessary)
RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug-3.0.3 \
    && docker-php-ext-enable xdebug

# Is it better to have composer as a different container instead of here?
COPY --from=composer:2.0 /usr/bin/composer /usr/bin/composer

# Same question about node

# If we create any files within this image, they will be owned by the root user
# This causes problems when trying to write to them from outside the image (I kept
# getting permission not granted errors in PhpStorm).
# Any other reasons for doing this?
RUN adduser -DG www-data www

WORKDIR /var/www
USER www

# Pros and cons of running composer install in the Dockerfile?
# Images (or is it containers?) are supposed to be ephemeral, so no
#COPY /composer.json composer.json
#RUN composer install

#
#RUN chmod -R 775 /var/www/storage
