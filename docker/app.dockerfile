FROM php:8.1-cli-alpine

WORKDIR /var/www
COPY . .
COPY ./.env.example ./.env

RUN apk add --no-cache mc bash npm && \
    apk add --no-cache $PHPIZE_DEPS && \
    pecl install redis && \
    pecl install xdebug && \
    docker-php-ext-install pdo_mysql && \
    docker-php-ext-enable redis pdo_mysql xdebug && \
    echo -e "\nxdebug.mode=debug\nxdebug.client_host=host.docker.internal\nxdebug.start_with_request=yes\n" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    php ./composer.phar install && \
    npm install && \
    npm run dev

CMD ["./artisan", "serve", "--host=0.0.0.0"]



