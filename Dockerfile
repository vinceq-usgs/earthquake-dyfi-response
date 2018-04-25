ARG FROM_IMAGE=usgs/httpd-php:latest
FROM ${FROM_IMAGE}


RUN mkdir /data

COPY src/conf/ /var/www/conf/
COPY src/htdocs/ /var/www/html/
COPY src/lib/ /var/www/lib/


EXPOSE 80
