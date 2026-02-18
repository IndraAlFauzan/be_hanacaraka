# Multi-stage build for Laravel application
FROM php:8.4-fpm-alpine AS base

# Install system dependencies
RUN apk add --no-cache \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    oniguruma-dev \
    icu-dev \
    mysql-client \
    supervisor \
    nginx

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        opcache

# Install Redis extension
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps

# Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# ================================
# Development Stage
# ================================
FROM base AS development

# Install development tools
RUN apk add --no-cache \
    vim \
    nano

# Copy PHP development configuration
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Configure PHP for development
RUN echo "memory_limit = 512M" >> "$PHP_INI_DIR/php.ini" \
    && echo "upload_max_filesize = 50M" >> "$PHP_INI_DIR/php.ini" \
    && echo "post_max_size = 50M" >> "$PHP_INI_DIR/php.ini" \
    && echo "max_execution_time = 300" >> "$PHP_INI_DIR/php.ini"

# Create non-root user
RUN addgroup -g 1000 www && adduser -u 1000 -G www -s /bin/sh -D www

# Copy application files
COPY --chown=www:www . /var/www/html

# Install dependencies
RUN composer install --no-interaction --prefer-dist

# Set permissions
RUN chown -R www:www /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

USER www

EXPOSE 9000

CMD ["php-fpm"]

# ================================
# Production Stage
# ================================
FROM base AS production

# Copy PHP production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Configure PHP for production
RUN echo "memory_limit = 256M" >> "$PHP_INI_DIR/php.ini" \
    && echo "upload_max_filesize = 10M" >> "$PHP_INI_DIR/php.ini" \
    && echo "post_max_size = 12M" >> "$PHP_INI_DIR/php.ini" \
    && echo "max_execution_time = 60" >> "$PHP_INI_DIR/php.ini" \
    && echo "expose_php = Off" >> "$PHP_INI_DIR/php.ini"

# Configure OPcache for production
RUN echo "opcache.enable=1" >> "$PHP_INI_DIR/php.ini" \
    && echo "opcache.memory_consumption=256" >> "$PHP_INI_DIR/php.ini" \
    && echo "opcache.interned_strings_buffer=16" >> "$PHP_INI_DIR/php.ini" \
    && echo "opcache.max_accelerated_files=20000" >> "$PHP_INI_DIR/php.ini" \
    && echo "opcache.validate_timestamps=0" >> "$PHP_INI_DIR/php.ini" \
    && echo "opcache.save_comments=1" >> "$PHP_INI_DIR/php.ini"

# Create non-root user
RUN addgroup -g 1000 www && adduser -u 1000 -G www -s /bin/sh -D www

# Copy application files
COPY --chown=www:www . /var/www/html

# Install production dependencies only
RUN composer install --no-interaction --prefer-dist --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www:www /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Cache Laravel configuration
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

USER www

EXPOSE 9000

CMD ["php-fpm"]
