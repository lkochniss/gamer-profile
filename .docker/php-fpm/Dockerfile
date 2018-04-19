FROM alpine:edge

MAINTAINER Lukas Kochniss<lukas.kochniss@gmail.com>

ENV COMPOSER_VERSION=1.6.3 \
    COMPOSER_HOME=/composer \
    PATH=$COMPOSER_HOME/vendor/bin:$PATH

# necessary for php7-ast
RUN echo http://nl.alpinelinux.org/alpine/edge/testing >> /etc/apk/repositories

COPY install-composer.sh /usr/local/bin/install-composer.sh

RUN apk --update --no-cache add \
    bash ca-certificates curl git openssh make \
    php7 \
    php7-ast \
    php7-common \
    php7-ctype \
    php7-curl \
    php7-dom \
    php7-fpm \
    php7-gd \
    php7-iconv \
    php7-json \
    php7-ldap \
    php7-mbstring \
    php7-mcrypt \
    php7-opcache \
    php7-openssl \
    php7-pdo \
    php7-pdo_mysql \
    php7-pdo_pgsql \
    php7-pdo_sqlite \
    php7-phar \
    php7-posix \
    php7-redis \
    php7-session \
    php7-simplexml \
    php7-soap \
    php7-sockets \
    php7-tokenizer \
    php7-xdebug \
    php7-xml \
    php7-xmlreader \
    php7-xmlwriter \
    php7-xsl \
    php7-zip \
    php7-zlib \
    && echo "zend_extension=xdebug.so" >> /etc/php7/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on\n" >> /etc/php7/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off\n" >> /etc/php7/conf.d/xdebug.ini \
    && echo "xdebug.remote_port=9000\n" >> /etc/php7/conf.d/xdebug.ini \
    && echo "xdebug.remote_handler=dbgp\n" >> /etc/php7/conf.d/xdebug.ini \
    && echo "xdebug.remote_connect_back=0\n" >> /etc/php7/conf.d/xdebug.ini \
    && mkdir -p $COMPOSER_HOME \
    && ( install-composer.sh && rm /usr/local/bin/install-composer.sh ) \
    && export COMPOSER_ALLOW_SUPERUSER=1 \
    && composer global require -a --prefer-dist "hirak/prestissimo:^0.3" \
    && export COMPOSER_ALLOW_SUPERUSER=0 \
    && chmod -R 0777 $COMPOSER_HOME/cache \
    && rm -Rf /var/cache/apk/* \
    && rm -Rf $COMPOSER_HOME/cache/*

COPY symfony.pool.conf /etc/php7/php-fpm.d/

CMD ["php-fpm7", "-F"]

WORKDIR /var/www/symfony
EXPOSE 9000
