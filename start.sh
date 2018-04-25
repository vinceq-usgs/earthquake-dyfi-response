#! /bin/bash

CONTAINER_PORT=${CONTAINER_PORT:-8123}


echo "Starting container on http://localhost:${CONTAINER_PORT}/"
echo

docker run --rm \
    -p ${CONTAINER_PORT}:80 \
    -v $(pwd)/responses:/data \
    -v $(pwd)/src/conf:/var/www/conf \
    -v $(pwd)/src/htdocs:/var/www/html \
    -v $(pwd)/src/lib:/var/www/lib \
    $@ \
    usgs/earthquake-dyfi-response:latest
