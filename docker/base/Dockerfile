FROM php:8.2.27-apache

# パッケージインストールを1つのレイヤーに統合
RUN apt-get update && apt-get install -y \
    build-essential \
    curl \
    gifsicle \
    git \
    jpegoptim \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmariadb-dev \
    libmariadb-dev-compat \
    libonig-dev \
    libpng-dev \
    libwebp-dev \
    libzip-dev \
    locales \
    optipng \
    pkg-config \
    pngquant \
    unzip \
    vim \
    zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# PHP拡張を1つのRUNコマンドに統合
RUN set -ex; \
    # mbstring拡張
    CFLAGS="-O0" docker-php-ext-configure mbstring; \
    docker-php-ext-install -j1 mbstring; \
    # gd拡張
    CFLAGS="-O1" docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    --with-webp; \
    docker-php-ext-install -j1 gd; \
    # その他の拡張
    docker-php-ext-install pdo_mysql exif pcntl bcmath zip; \
    # PCOV for code coverage
    pecl install pcov; \
    docker-php-ext-enable pcov;

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Node.jsとnpmのインストールを統合
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \
    && a2enmod rewrite

WORKDIR /var/www/html

# 依存関係ファイルのコピーを統合
COPY app/laravel/composer.* app/laravel/package*.json ./

# 依存関係インストールを1つのレイヤーに統合
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN set -ex \
    && composer install --no-dev --optimize-autoloader --no-scripts --no-autoloader --no-cache \
    && npm install \
    && composer dump-autoload --optimize --no-scripts

# アプリケーションコードのコピー（依存関係インストール後に実行）
COPY app/laravel .

EXPOSE 80
