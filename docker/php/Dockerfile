FROM php:7.3-fpm

ENV COMPOSER_VERSION=1.10.6 \
    PECL_XDEBUG_VERSION=2.9.2

# Args for custom user
ARG USER_ID
ARG GROUP_ID

# Installing system packages
RUN apt-get update && apt-get install --no-install-recommends --no-install-suggests -y \
    zip \
    unzip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Installing additional PHP extensions
RUN docker-php-ext-install \
    bcmath \
    pdo_mysql \
    pcntl \
    exif \
    opcache \
    && pecl install xdebug-${PECL_XDEBUG_VERSION} \
    && docker-php-ext-enable xdebug

# Installing composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --version=${COMPOSER_VERSION} \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer

# Copying starting script
COPY ./docker/php/start.sh /usr/local/bin/start.sh
# Making script executable
RUN chmod +x /usr/local/bin/start.sh

# Custom PHP configs
COPY ./docker/php/config/uploads.ini $PHP_INI_DIR/conf.d/

# Change user for proper permissions and composer caches
RUN  userdel -f www-data \
    && if getent group www-data ; then groupdel www-data; fi\
    && groupadd -g ${GROUP_ID:-1000} www-data \
    && useradd -l -u ${USER_ID:-1000} -g www-data www-data \
    && install -d -m 0755 -o www-data -g www-data /home/www-data

# Copying files required for composer install
COPY composer* ./
COPY database/ database/

# Installing project dependencies
RUN composer install --no-scripts

RUN chown -R www-data:www-data .

USER www-data

EXPOSE 9000
EXPOSE 8000

ENTRYPOINT ["/usr/local/bin/start.sh"]

