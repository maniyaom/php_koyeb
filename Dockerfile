# Use an official PHP image
FROM php:8.0-apache

# Install the MySQLi extension
RUN docker-php-ext-install mysqli

# (Optional) Install other dependencies for image processing (uncomment if needed)
# RUN apt-get update && apt-get install -y \
#     libpng-dev \
#     libjpeg-dev \
#     libfreetype6-dev \
#     && docker-php-ext-configure gd --with-freetype --with-jpeg \
#     && docker-php-ext-install gd

# Copy your application code
COPY . /var/www/html/

# Set permissions (if necessary)
RUN chown -R www-data:www-data /var/www/html

# Expose the port the app runs on
EXPOSE 80