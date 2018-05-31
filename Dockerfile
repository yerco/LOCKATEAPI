FROM lock8/lock8-base:latest
MAINTAINER Yerco <yerco@hotmail.com>

# bug "Failed to start the session because headers have already been sent ..."
# it needs `date.timezone` set (e.g. `date.timezone = "Europe/Amsterdam"`)
COPY php.ini /usr/local/etc/php/

# create a non-root user
RUN groupadd -g 999 appuser && \
    useradd -r -u 999 -g appuser appuser

# create workdir and assign perissions to non-root user
RUN set -xe && \
    mkdir /var/www/html/lockate_api  && \
    chown -R appuser:appuser /var/www/html/lockate_api

RUN apt-get update && apt-get install -y zlib1g-dev git lsof vim \
    && docker-php-ext-install zip \
    && docker-php-ext-install pdo pdo_mysql

RUN mkdir -p /home/appuser/.composer && \
    chown -R appuser:appuser /home/appuser/.composer && \
    chmod +w -R /home/appuser/.composer

# switch from root to appuser
USER appuser

# chown included otherwise copied as root
COPY --chown=appuser:appuser composer.phar  /var/www/html/lockate_api
RUN chmod +x /var/www/html/lockate_api/composer.phar
COPY --chown=appuser:appuser phpunit-6.5.phar /var/www/html/lockate_api
RUN chmod +x /var/www/html/lockate_api/phpunit-6.5.phar
# copy PHP code
COPY --chown=appuser:appuser . /var/www/html/lockate_api/

WORKDIR /var/www/html/lockate_api

COPY  --chown=appuser:appuser ./parameters.yml /var/www/html/lockate_api/app/config/

# required by JWTAuthenticationBundle
RUN mkdir /var/www/html/lockate_api/var/jwt
COPY  --chown=appuser:appuser private.pem /var/www/html/lockate_api/var/jwt
COPY  --chown=appuser:appuser public.pem /var/www/html/lockate_api/var/jwt

# symfony
RUN ./composer.phar install
RUN chmod +w ./var/logs/* && chmod +w ./var/cache/*
RUN chmod -R a+w ./var/sessions
RUN ./composer.phar dump-autoload

EXPOSE 8081

CMD ["php", "bin/console", "server:run", "-vvv", "*:8081"]
