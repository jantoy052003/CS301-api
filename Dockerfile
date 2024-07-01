FROM richarvey/nginx-php-fpm:3.1.4

COPY . .

# Image config
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel config
ENV APP_ENV production
ENV APP_DEBUG false
ENV DB_CONNECTION pgsql
ENV DB_HOST dpg-cq0v2cqju9rs73b3mvr0-a
ENV DB_PORT 5432
ENV DB_DATABASE cs301_api_t37y
ENV DB_USERNAME cs301_api_t37y_user
ENV DB_PASSWORD jtG16vrdSbc4P4FJjkc8vDR665koYM2B

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

CMD ["/start.sh"]