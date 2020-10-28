FROM registry.gitlab.com/quanuet94/wl4w:base

ENV WORKDIR /var/www/html

WORKDIR $WORKDIR

COPY docker/conf/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY docker/conf/apache2.conf /etc/apache2/apache2.conf
COPY docker/conf/php.ini /usr/local/etc/php/conf.d/php.ini
COPY docker/start.sh /usr/local/bin/start

COPY composer.* $WORKDIR/

RUN mkdir -p $WORKDIR/database/seeds
RUN mkdir -p $WORKDIR/database/factories

RUN chmod 775 $WORKDIR/composer.phar \
    && $WORKDIR/composer.phar install --no-dev --no-scripts \
    && rm $WORKDIR/composer.phar

COPY . .

RUN mkdir -p $WORKDIR/storage
RUN mkdir -p $WORKDIR/bootstrap/cache

RUN chown -R www-data:www-data \
    $WORKDIR/storage \
    $WORKDIR/bootstrap/cache \
    && chmod u+x /usr/local/bin/start

RUN a2enmod rewrite

CMD ["/usr/local/bin/start"]
