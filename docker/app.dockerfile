FROM php:8.1-cli-alpine

WORKDIR /var/www
COPY . .

RUN apk add --no-cache mc bash npm && \
    apk add --no-cache $PHPIZE_DEPS && \
    pecl install redis && \
    docker-php-ext-install pdo_mysql && \
    docker-php-ext-enable redis pdo_mysql && \
    php ./composer.phar install && \
    npm install && \
    npm run dev

CMD ["./artisan", "serve", "--host=0.0.0.0"]



