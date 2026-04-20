FROM php:8.3-fpm-alpine

ARG SERVER_ENVIRONMENT
ARG APP_DIR=/var/www/app

# Set working directory
WORKDIR $APP_DIR

# Update APK repositories for Alpine 3.19
RUN rm -f /etc/apk/repositories && \
    echo "http://dl-cdn.alpinelinux.org/alpine/v3.19/main" >> /etc/apk/repositories && \
    echo "http://dl-cdn.alpinelinux.org/alpine/v3.19/community" >> /etc/apk/repositories

# Install system packages (split ffmpeg/postgresql-dev to avoid conflict)
RUN apk update && apk add --no-cache ffmpeg
RUN apk add --no-cache postgresql-dev make

# Fix iconv issues with Alpine
RUN apk add --no-cache --repository http://dl-cdn.alpinelinux.org/alpine/edge/community/ --allow-untrusted gnu-libiconv
ENV LD_PRELOAD /usr/lib/preloadable_libiconv.so php

# Install required build & runtime dependencies
RUN apk add --no-cache \
    php82-pear \
    libwebp-dev \
    libzip-dev \
    libjpeg-turbo-dev \
    libjpeg-turbo \
    libpng-dev \
    libxpm-dev \
    php82-dev \
    gcc \
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
    build-base \
    git

# Install PHP extensions
RUN docker-php-ext-install -j"$(nproc)" \
    curl \
    pgsql \
    pdo \
    pdo_pgsql \
    bcmath \
    zip

# Configure intl extension
RUN docker-php-ext-configure intl && \
    docker-php-ext-install intl && \
    docker-php-ext-configure pgsql --with-pgsql=/usr/local/pgsql

# Configure and install GD extension
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp && \
    docker-php-ext-install -j"$(nproc)" gd

# Install pcntl
RUN docker-php-ext-configure pcntl --enable-pcntl && \
    docker-php-ext-install pcntl

# Install and enable Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Install Imagick PHP extension manually
RUN git clone https://github.com/Imagick/imagick.git --depth 1 /tmp/imagick && \
    cd /tmp/imagick && \
    phpize && \
    ./configure && \
    make && \
    make install && \
    docker-php-ext-enable imagick

# Enable OPcache
RUN docker-php-ext-configure opcache --enable-opcache && \
    docker-php-ext-install opcache

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

# Copy PHP configuration files
COPY .docker/app/8.3-fpm/opcache.ini $PHP_INI_DIR/conf.d/
COPY .docker/app/8.3-fpm/php.ini $PHP_INI_DIR/conf.d/

# Set up permissions and app directory structure
RUN mkdir -p /var/www/app/bootstrap/cache /var/www/app/storage/logs && \
    touch /var/www/app/storage/logs/worker.log && \
    chown -R www-data:www-data /var/www/app && \
    chmod -R 775 /var/www/app/bootstrap/cache

# Copy startup script
COPY .docker/app/8.3-fpm/init.sh /usr/bin/startx.sh
RUN chmod +x /usr/bin/startx.sh

ENTRYPOINT ["/usr/bin/startx.sh"]
