FROM node:20-alpine AS node

WORKDIR /var/www

COPY ./. ./

# Устанавливаем зависимости и собираем front-end
RUN npm install && npm run build

FROM nginx:alpine

ADD .deploy/nginx/nginx.prod.conf /etc/nginx/conf.d/default.conf

# Копируем только собранный клиент, папка node_modules больше не нужна
COPY --from=node /var/www/public /var/www/public

WORKDIR /var/www/public

EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
