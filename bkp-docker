# Use the official PHP 7.4 Apache base image
FROM php:7.4-apache

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

# Set the working directory in the container
WORKDIR /var/www/html

# Copy composer.lock and composer.json
COPY composer.lock composer.json /var/www/html/

# Install Vim
RUN apt-get update && \
    apt-get install -y vim

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install dependencies
RUN composer install --no-autoloader --no-scripts

# Copy the rest of the application code
COPY . /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite

# Expose port 80 and start Apache service
EXPOSE 80

# Run Composer install command and start Apache service when container starts
CMD ["bash", "-c", "composer install && apache2-foreground"]