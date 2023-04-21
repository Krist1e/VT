FROM php:7.4-apache
COPY src/ /var/www/html/
COPY lab1/website /var/www/html/
COPY lab1/ /var/www/html/
COPY lab1/.htaccess /var/www/html/.htaccess