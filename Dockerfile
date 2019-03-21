FROM cyberduck/php-fpm-laravel:7.3

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
    gnupg \
    libxml2 \
    libsodium-dev

RUN apt-get install -y libxml2-dev

# install node and npm
RUN curl -sL https://deb.nodesource.com/setup_11.x | bash -
RUN apt-get install -y nodejs

RUN docker-php-ext-install exif sodium

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ADD https://github.com/ufoscout/docker-compose-wait/releases/download/2.2.1/wait /wait
RUN chmod +x /wait

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
#COPY --chown=www:www . /var/www

RUN mkdir -p /usr/lib/node_modules
WORKDIR /var/www
RUN chmod +x /var/www/sync-all.sh
RUN mkdir -p /var/www/vendor/

RUN composer update
RUN composer install
RUN composer require mcamara/laravel-localization

RUN npm install -g cross-env

# Set up crontabs

COPY crontab /home/crontab
RUN cat /home/crontab >> /etc/crontab
RUN chmod 0644 /etc/crontab
RUN crontab /etc/crontab

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD sh run.sh
