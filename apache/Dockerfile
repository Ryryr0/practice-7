FROM ubuntu:latest

# Установка необходимых пакетов
RUN apt-get update && apt-get install -y \
    apache2 \
    php \
    libapache2-mod-php \
    php-mysql \
    php-redis \
    php-gd \
    php-zip \
    unzip \
    curl \
    git && \
    a2enmod headers && a2enmod rewrite

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Копирование файла composer.json и установка зависимостей
WORKDIR /var/www/html
COPY ./composer.json /var/www/html/composer.json
RUN composer install

# Копирование исходного кода
COPY ./src /var/www/html/src

# Открытие порта
EXPOSE 80

# Запуск Apache
CMD ["apachectl", "-D", "FOREGROUND"]

