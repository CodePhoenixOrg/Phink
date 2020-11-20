FROM php:7.2-apache
RUN a2enmod rewrite
COPY apache/phink.conf /etc/apache2/sites-available/phink.conf
COPY php/php.ini /usr/local/etc/php/conf.d/customizations.ini
RUN a2ensite phink
ENV PORT 80
ENTRYPOINT []
CMD ["php", "-S", "0.0.0.0:80", "-t", "/sites"]