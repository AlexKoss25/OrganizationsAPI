FROM php:8.2-fpm

WORKDIR /var/www

# Устанавливаем расширения PHP
RUN docker-php-ext-install pdo pdo_mysql

# Копируем весь код проекта
COPY ../../ /var/www

# Создаём папку для swagger
RUN mkdir -p /var/www/public/swagger

# Копируем Swagger UI и OpenAPI
COPY ../../docker/swagger/ /var/www/public/swagger/
COPY ../../docs/openapi.yaml /var/www/public/swagger/openapi.yaml

# Права
RUN chown -R www-data:www-data /var/www
