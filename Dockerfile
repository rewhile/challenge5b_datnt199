FROM php:8.2-fpm

# Install dependencies
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
    libzip-dev \
    netcat-openbsd

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy existing application directory contents
COPY . /var/www/html

# Generate the autoloader files
RUN composer install

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

# Create a more robust startup script
RUN echo '#!/bin/sh' > /usr/local/bin/startup.sh && \
    echo 'cd /var/www/html' >> /usr/local/bin/startup.sh && \
    echo 'echo "Waiting for database to be ready..."' >> /usr/local/bin/startup.sh && \
    echo 'ATTEMPTS=0' >> /usr/local/bin/startup.sh && \
    echo 'while [ $ATTEMPTS -lt 30 ]; do' >> /usr/local/bin/startup.sh && \
    echo '  nc -z db 3306 && break' >> /usr/local/bin/startup.sh && \
    echo '  ATTEMPTS=$((ATTEMPTS+1))' >> /usr/local/bin/startup.sh && \
    echo '  echo "Waiting for db to be ready... $ATTEMPTS/30"' >> /usr/local/bin/startup.sh && \
    echo '  sleep 1' >> /usr/local/bin/startup.sh && \
    echo 'done' >> /usr/local/bin/startup.sh && \
    echo 'if [ $ATTEMPTS -eq 30 ]; then' >> /usr/local/bin/startup.sh && \
    echo '  echo "Database connection timed out"' >> /usr/local/bin/startup.sh && \
    echo 'else' >> /usr/local/bin/startup.sh && \
    echo '  echo "Database connection established!"' >> /usr/local/bin/startup.sh && \
    echo '  php artisan config:cache' >> /usr/local/bin/startup.sh && \
    echo '  php artisan key:generate --force' >> /usr/local/bin/startup.sh && \
    echo '  php artisan migrate --force --verbose' >> /usr/local/bin/startup.sh && \
    echo '  php artisan db:seed --force --verbose' >> /usr/local/bin/startup.sh && \
    echo 'fi' >> /usr/local/bin/startup.sh && \
    echo 'php-fpm' >> /usr/local/bin/startup.sh && \
    chmod +x /usr/local/bin/startup.sh

# Expose port 9000
EXPOSE 9000

# Start php-fpm server with migrations
CMD ["/usr/local/bin/startup.sh"]
