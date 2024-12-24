# Stage 1: Build
FROM php:7.4-apache AS build

# Install system dependencies
RUN apt-get update && \
    apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    zlib1g-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql mysqli zip \
    && apt-get install -y default-mysql-client

# Install Vim (optional, usually not needed in production)
RUN apt-get update && apt-get install -y vim

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory in the container
WORKDIR /app

# Copy composer files and install dependencies
COPY composer.lock composer.json /app/
RUN composer install --no-dev --no-autoloader --no-scripts

# Copy the rest of the application code
COPY . /app/

# Run Composer install to prepare autoloader
RUN composer install --no-dev --optimize-autoloader

# Set permissions for Apache
RUN chown -R www-data:www-data /app \
    && a2enmod rewrite

# Stage 2: Production
FROM php:7.4-apache

# Copy required extensions and configurations from the build stage
COPY --from=build /usr/local/lib/php/extensions/no-debug-non-zts-* /usr/local/lib/php/extensions/
COPY --from=build /usr/local/bin/composer /usr/local/bin/composer

# Copy the application code from the build stage
COPY --from=build /app /var/www/html

# Set permissions for Apache
RUN chown -R www-data:www-data /var/www/html

# Expose port 80 and start Apache service
EXPOSE 80

# Start Apache service
# Run Composer install command and start Apache service when container starts
CMD ["bash", "-c", "composer install && apache2-foreground"]
