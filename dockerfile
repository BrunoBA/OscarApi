FROM brunoba93/php-8:f881845

COPY ./default.conf /etc/apache2/sites-available/

RUN a2enmod rewrite
RUN a2enmod actions
RUN a2ensite

RUN service apache2 restart

RUN  ls /etc/apache2/sites-available/

