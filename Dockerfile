ARG FROM_IMAGE=usgs/httpd-php:latest
FROM ${FROM_IMAGE}


RUN mkdir /data

COPY src/conf/ /var/www/conf/
COPY src/htdocs/ /var/www/html/
COPY src/lib/ /var/www/lib/


EXPOSE 80
HEALTHCHECK --interval=30s --timeout=15s --start-period=30s --retries=1 CMD [ "/var/www/lib/healthcheck.sh" ]
