# Default Dockerfile
#
# @link     https://www.hyperf.io
# @document https://hyperf.wiki
# @contact  group@hyperf.io
# @license  https://github.com/hyperf/hyperf/blob/master/LICENSE

FROM hyperf/hyperf:8.0-alpine-v3.12-swoole
LABEL maintainer="Hyperf Developers <group@hyperf.io>" version="1.0" license="MIT" app.name="Hyperf"

##
# ---------- env settings ----------
##
# --build-arg timezone=Asia/Shanghai
ARG timezone

ENV TIMEZONE=${timezone:-"Asia/Shanghai"} \
    APP_ENV=prod \
    SCAN_CACHEABLE=(true)

# update
RUN set -ex \
    # show php version and extensions
    && php -v \
    && php -m \
    && php --ri swoole \
    #  ---------- some config ----------
    && cd /etc/php8 \
    # - config PHP
    && { \
        echo "upload_max_filesize=128M"; \
        echo "post_max_size=128M"; \
        echo "memory_limit=1G"; \
        echo "date.timezone=${TIMEZONE}"; \
    } | tee conf.d/99_overrides.ini \
    # - config timezone
    && ln -sf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime \
    && echo "${TIMEZONE}" > /etc/timezone \
    # ---------- clear works ----------
    && rm -rf /var/cache/apk/* /tmp/* /usr/share/man \
    && echo -e "\033[42;37m Build Completed :).\033[0m\n"

RUN apk add libc-dev pcre2-dev bsd-compat-headers libevent-dev openssl-dev zlib-dev linux-headers

#RUN apk add --no-cache $PHPIZE_DEPS \
#&& pecl8 install grpc \
#&& echo "extension=grpc.so" > /etc/php8/conf.d/grpc.ini

RUN apk --update add --virtual build-dependencies build-base php-pear php-dev openssl-dev autoconf \
  && pecl install mongodb \
  #&& docker-php-ext-enable mongodb \
  #&& apk del build-dependencies build-base openssl-dev autoconf \
  && echo "extension=mongodb.so" > /etc/php8/conf.d/mongodb.ini
  #&& rm -rf /var/cache/apk/*
  
#RUN apk add --no-cache build-base php-pear php-dev openssl-dev \
#&& pecl install mongodb \
#&& echo "extension=mongodb.so" > /etc/php7/conf.d/mongodb.ini

WORKDIR /opt/www

# Composer Cache
# COPY ./composer.* /opt/www/
# RUN composer install --no-dev --no-scripts

COPY . /opt/www
RUN composer install --no-dev -o && php bin/hyperf.php

EXPOSE 9501

ENTRYPOINT ["php", "/opt/www/bin/hyperf.php", "start server:watch"]
