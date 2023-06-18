#ARG ALPINE_VERSION=3.17

FROM nginx:stable-alpine3.17-slim

## Установка Nginx
#RUN apk add --no-cache nginx

# Копирование файла конфигурации Nginx
COPY ./config/nginx.conf /etc/nginx/nginx.conf

# Создание директории для статических файлов
RUN mkdir -p /var/www/html

# Открытие порта, на котором будет доступен Nginx
EXPOSE 80

# Запуск Nginx
CMD ["nginx", "-g", "daemon off;"]