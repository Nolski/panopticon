FROM php:7.2-fpm

# Copy composer.lock and composer.json
COPY composer.lock composer.json /var/www/

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    mysql-client \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    gnupg

RUN docker-php-ext-install pdo pdo_mysql

# install node and npm
RUN curl -sL https://deb.nodesource.com/setup_11.x | bash -
RUN apt-get install -y nodejs

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
# COPY --chown=www:www . /var/www

RUN mkdir -p /usr/lib/node_modules
# RUN chown www:www /usr/lib/node_modules -R
# RUN chown www:www /usr/bin -R
# RUN chown www:www /var/www -R
# # Change current user to www
# USER www
WORKDIR /var/www
RUN mkdir -p /var/www/vendor/

RUN composer install

RUN npm install
RUN npm install -g cross-env
RUN npm run production

ADD https://github.com/ufoscout/docker-compose-wait/releases/download/2.2.1/wait /wait
RUN chmod +x /wait

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD /wait && php artisan migrate && php artisan sync:structure && php artisan serve --host=0.0.0.0 --port=9000
