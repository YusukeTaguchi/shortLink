# Sử dụng PHP 7.3 Apache image
FROM php:7.3-apache

# Cài đặt các phụ thuộc cần thiết cho Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd pdo pdo_mysql zip

# Cài đặt Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Thiết lập thư mục làm việc
WORKDIR /var/www/html

# Sao chép source code vào trong container
COPY . /var/www/html

# Cấp quyền cho thư mục storage
RUN chown -R www-data:www-data /var/www/html/storage
RUN chmod -R 775 /var/www/html/storage

# Cấu hình Apache
COPY ./docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Cài đặt và chạy các dependencies của Laravel
RUN composer install
RUN php artisan key:generate
RUN php artisan config:cache

# Install Node, Npm
RUN apt-get install -y gnupg \
  && curl -sL https://deb.nodesource.com/setup_12.x | bash - \
  && apt-get install -y nodejs \
  && mkdir /var/www/.config /var/www/.npm

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Expose port 80
EXPOSE 80
