FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nginx \
    supervisor \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy composer files first for better caching
COPY composer.json composer.lock ./

# Install composer dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy application files
COPY . /var/www

# Re-run composer to execute post-install scripts
RUN composer dump-autoload --optimize

# Copy production nginx config and remove ALL defaults
RUN rm -f /etc/nginx/sites-enabled/default \
    && rm -f /etc/nginx/sites-available/default \
    && rm -f /etc/nginx/conf.d/default.conf
COPY docker/nginx/nginx-prod.conf /etc/nginx/sites-available/default
RUN ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default
COPY docker/nginx/nginx-prod.conf /etc/nginx/conf.d/app.conf

# Copy supervisord config
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Create necessary directories and set permissions
RUN mkdir -p /var/www/public/images/uploads \
    && mkdir -p /var/log/supervisor \
    && mkdir -p /var/log/nginx \
    && chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/public

# Use PORT env variable from Railway (default 80)
ENV PORT=80

# Expose port
EXPOSE ${PORT}

# Start supervisord (manages both nginx and php-fpm)
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
