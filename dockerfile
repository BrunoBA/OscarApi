FROM brunoba93/php-8:f881845

COPY ./default.conf /etc/apache2/sites-available/
COPY ./html /var/www/html/

RUN composer install

RUN a2enmod rewrite
RUN a2enmod actions
RUN a2ensite

EXPOSE 80

RUN service apache2 restart

