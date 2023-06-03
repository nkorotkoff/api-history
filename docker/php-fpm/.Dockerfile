FROM php:8.1-fpm

# Определение пользователя для PHP-FPM
RUN usermod -u 1000 www-data

# Настройка директории сайта
WORKDIR /var/www/html

# Копирование файлов конфигурации
COPY ./config/fpm-pool.conf /usr/local/etc/php-fpm.d/www.conf
COPY ./config/php.ini /usr/local/etc/php/conf.d/custom.ini

# Копирование приложения
COPY ./ /var/www/html/

# Установка зависимостей
RUN apt-get update && apt-get install -y \
    libcurl4-openssl-dev \
    libicu-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-install \
    curl \
    intl \
    mysqli \
    opcache \
    pdo_mysql \
    zip \
    && rm -rf /var/lib/apt/lists/*

RUN pecl install xdebug && docker-php-ext-enable xdebug

RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Определение пользователя в файле конфигурации PHP-FPM
RUN sed -i -e "s/user = .*/user = www-data/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i -e "s/group = .*/group = www-data/g" /usr/local/etc/php-fpm.d/www.conf

# Убедимся, что файлы/директории, необходимые для процессов, доступны при запуске от пользователя www-data
RUN chown -R www-data:www-data /var/www/html

# Открытие порта, на котором будет доступен PHP-FPM
EXPOSE 9000

# Запуск PHP-FPM
CMD ["php-fpm"]