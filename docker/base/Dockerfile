FROM php:8.2.27-apache

RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libonig-dev \
    libmariadb-dev-compat \
    libmariadb-dev \
    libzip-dev \
    pkg-config \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# PHP拡張を個別にインストール
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install exif
RUN docker-php-ext-install pcntl
RUN docker-php-ext-install bcmath

RUN CFLAGS="-O0" docker-php-ext-configure mbstring
RUN CFLAGS="-O0" docker-php-ext-install -j1 mbstring

# GD拡張の設定とインストール（特に問題が起きやすい）
# GD関連の依存関係インストール
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libwebp-dev \
    && rm -rf /var/lib/apt/lists/*

# GDのインストール（メモリ制限緩和とコンパイルオプション調整）
RUN CFLAGS="$CFLAGS -O1" docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    --with-webp

RUN CFLAGS="$CFLAGS -O1" docker-php-ext-install -j1 gd

# ZIP拡張のインストール
RUN docker-php-ext-install zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

WORKDIR /var/www/html

# Copy composer files
COPY ../../app/laravel/composer.json ../../app/laravel/composer.lock ./

# Composerにroot権限での実行を許可
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-autoloader

# Copy package.json and package-lock.json
COPY ../../app/laravel/package.json ../../app/laravel/package-lock.json ./

# Install npm dependencies
#RUN npm ci --only=production
RUN npm install

# Copy the rest of the application code
COPY ../../app/laravel .

# Generate autoloader
RUN composer dump-autoload --optimize

EXPOSE 80