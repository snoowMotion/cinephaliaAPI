FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git unzip curl libpq-dev libxml2-dev libonig-dev \
    && docker-php-ext-install pdo pdo_pgsql xml

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["entrypoint.sh"]
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb \
RUN echo "upload_max_filesize=50M\post_max_size=50M" > /usr/local/etc/php/conf.d/uploads.ini