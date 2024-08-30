FROM php:7.4.30-apache-bullseye

RUN rm -rf /var/www/html/*

COPY flag.txt config/readflag /
RUN chown 0:1337 /flag.txt /readflag && \
    chmod 040 /flag.txt && \
    chmod 2555 /readflag

COPY src /var/www/html/
COPY config/php.ini-production /usr/local/etc/php/php.ini

RUN mkdir /var/www/html/uploads && \
    chmod 777 /var/www/html/uploads

RUN ln -sf /dev/stdout /var/log/apache2/access.log && \
    ln -sf /dev/stderr /var/log/apache2/error.log

RUN find / -ignore_readdir_race -type f \( -perm -4000 -o -perm -2000 \) -not -wholename /readflag -delete
USER www-data
RUN (find --version && id --version && sed --version && grep --version) > /dev/null
USER root

RUN a2dismod -f deflate

EXPOSE 80
CMD while true; do chmod 777 /var/www/html/uploads/; sleep 10s; done & \
    apache2ctl -D FOREGROUND