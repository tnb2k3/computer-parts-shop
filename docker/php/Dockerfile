FROM php:8.1-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev \
    zip unzip nginx

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy Nginx config
COPY ./docker/nginx/nginx.conf /etc/nginx/conf.d/default.conf

# Set working directory
WORKDIR /var/www

# Copy app files
COPY . /var/www

# Set permissions
RUN chown -R www-data:www-data /var/www

# Start both php-fpm and nginx
COPY ./docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 80

CMD ["/start.sh"]
