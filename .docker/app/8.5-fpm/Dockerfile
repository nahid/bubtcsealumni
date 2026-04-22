FROM php:8.5-fpm-alpine

ARG APP_DIR=/var/www/app

# Setup Working Dir
WORKDIR $APP_DIR

RUN set -ex \
  && for i in 1 2 3; do apk update && break || (echo "apk update failed, retry $i/3" && sleep 3); done \
  && for i in 1 2 3; do apk --no-cache add \
    postgresql-dev \
    ffmpeg \
    make \
    libwebp-dev \
    libzip-dev \
    libjpeg-turbo-dev \
    libjpeg-turbo \
    libpng-dev \
    libxpm-dev \
    zlib-dev \
    curl-dev \
    imagemagick \
    imagemagick-dev \
    freetype-dev \
    icu-dev \
    g++ \
    npm \
    zip \
    vim \
    nano \
    git \
    linux-headers \
    autoconf \
    build-base \
    && break || (echo "apk add failed, retry $i/3" && sleep 5); done

# Configure & Install Extension
RUN set -ex \
  && for i in 1 2 3; do docker-php-ext-install -j "$(nproc)" \
    curl \
    pgsql \
    pdo \
    pdo_pgsql \
    bcmath \
    zip \
    && break || (echo "docker-php-ext-install base deps failed, retry $i/3" && sleep 5); done

RUN set -ex \
  && docker-php-ext-configure intl \
  && for i in 1 2 3; do docker-php-ext-install intl \
    && break || (echo "docker-php-ext-install intl failed, retry $i/3" && sleep 5); done \
  && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql

RUN set -ex \
  && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
  && for i in 1 2 3; do docker-php-ext-install -j "$(nproc)" gd \
    && break || (echo "docker-php-ext-install gd failed, retry $i/3" && sleep 5); done

RUN set -ex \
  && docker-php-ext-configure pcntl --enable-pcntl \
  && for i in 1 2 3; do docker-php-ext-install pcntl \
    && break || (echo "docker-php-ext-install pcntl failed, retry $i/3" && sleep 5); done

# Install Redis
RUN set -ex \
  && for i in 1 2 3; do pecl install redis \
    && break || (echo "pecl install redis failed, retry $i/3" && sleep 5); done \
  && docker-php-ext-enable redis

# Install Imagick PHP extension
RUN set -ex \
  && for i in 1 2 3; do git clone https://github.com/Imagick/imagick.git --depth 1 /tmp/imagick \
    && break || (echo "git clone imagick failed, retry $i/3" && sleep 5); done \
  && cd /tmp/imagick \
  && git fetch origin master \
  && git switch master \
  && phpize \
  && ./configure \
  && make \
  && make install \
  && docker-php-ext-enable imagick \
  && rm -rf /tmp/imagick

# OPCache is built-in for PHP 8.5, configured via opcache.ini

# Add Composer latest version
RUN set -ex \
  && for i in 1 2 3; do curl -fsSL https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer \
    && break || (echo "composer installer download failed, retry $i/3" && sleep 5); done

COPY .docker/app/8.5-fpm/opcache.ini $PHP_INI_DIR/conf.d/
COPY .docker/app/8.5-fpm/php.ini $PHP_INI_DIR/conf.d/

# Copy configuration files
RUN cd $APP_DIR
RUN chown www-data:www-data $APP_DIR
RUN mkdir -p /var/www/app/bootstrap/cache
RUN chmod -R 775 /var/www/app/bootstrap/cache

COPY .docker/app/8.5-fpm/init.sh /usr/bin/startx.sh
RUN chmod +x /usr/bin/startx.sh

RUN mkdir -p storage/logs
RUN touch storage/logs/worker.log

RUN chown -R www-data:www-data /var/www/app
RUN chown -R www-data:www-data /var/www/app/storage

ENTRYPOINT ["/usr/bin/startx.sh"]
