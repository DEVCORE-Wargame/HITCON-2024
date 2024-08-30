FROM php:7.4.33-apache

RUN apt update && apt install -y gcc

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY src/ /var/www/html/
RUN chown root:root -R /var/www/html && chmod go-w -R /var/www/html && chmod ugo+r /var/www/html/

COPY ./flag/readflag.c /readflag.c
COPY ./flag/flag.txt /flag.txt
RUN chmod 0400 /flag.txt && chown root:root /flag.txt
RUN chmod 0444 /readflag.c && gcc /readflag.c -o /readflag
RUN chown root:root /readflag && chmod 4555 /readflag
