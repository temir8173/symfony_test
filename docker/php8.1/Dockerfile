FROM php:8.1-fpm

RUN echo "Acquire::Check-Valid-Until \"false\";\nAcquire::Check-Date \"false\";" | cat > /etc/apt/apt.conf.d/10no--check-valid-until
RUN apt-get update && apt-get install -y \
    curl \
    wget \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libxslt-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    git

RUN docker-php-ext-install -j$(nproc) iconv mbstring mysqli pdo_mysql pgsql pdo_pgsql

# расширение для работы с изображениями
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
&& docker-php-ext-install -j$(nproc) gd

# ставим mcrypt
#RUN apt-get install -y libmcrypt-dev \
#    && pecl install mcrypt-1.0.3 \
#    && docker-php-ext-enable mcrypt

# чтобы работали функции типа cal_days_in_month
RUN docker-php-ext-configure calendar \
    && docker-php-ext-install calendar

RUN docker-php-ext-install soap
RUN docker-php-ext-install pcntl
RUN apt-get install -y libzip-dev unzip \
    && docker-php-ext-install zip
RUN docker-php-ext-install opcache
RUN docker-php-ext-install intl

# установка расширения YAML
RUN apt-get install -y libyaml-dev
RUN pecl install yaml-2.2.2 && docker-php-ext-enable yaml

# чтобы composer не спрашивал разрешения по ключам
RUN mkdir ~/.ssh
RUN touch ~/.ssh/known_hosts
RUN ssh-keyscan -H gitlab.com >> ~/.ssh/known_hosts
RUN ssh-keyscan -H github.com >> ~/.ssh/known_hosts

# для отладки
# замедляет работу проекта, пока подключение прослушивается
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# расширение для работы с redis
RUN pecl install redis \
    && docker-php-ext-enable redis

# расширение для вычислений с произвольной точностью
RUN docker-php-ext-install bcmath

# расширение для работы с сокетами
RUN docker-php-ext-install sockets

COPY composer.phar /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer
COPY fpm.conf /usr/local/etc/php-fpm.d/fpm.conf
RUN composer global require "squizlabs/php_codesniffer=*"

ENV TZ=Asia/Almaty
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# без этого будет создаваться ненужная директория /var/www/html
WORKDIR /var/www

# делаем чтобы пользователь www-data был как основной пользователь на хосте
RUN usermod -u 1000 www-data \
  && groupmod -g 1000 www-data

CMD update-ca-certificates && php-fpm
