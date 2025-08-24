FROM php:8.2-apache
RUN a2enmod rewrite
WORKDIR /var/www/html
COPY . /var/www/html
# Habilitar AllowOverride en el virtual host por defecto
RUN sed -i 's#AllowOverride None#AllowOverride All#g' /etc/apache2/apache2.conf
EXPOSE 80
