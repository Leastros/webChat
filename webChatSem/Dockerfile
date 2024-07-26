# Use a LAMP base image
FROM mattrayner/lamp:latest-2004-php8

# Copy application files to the container
WORKDIR /app
COPY ./api api
COPY ./controllers controllers
COPY ./db db
COPY ./dbscripts dbscripts
COPY ./models models
COPY ./pages pages
COPY ./resources resources
COPY ./services services
COPY ./validation validation
COPY ./style.css style.css
COPY ./index.php index.php
COPY ./.htaccess.docker .htaccess
COPY ./healthz.php healthz.php

CMD ["/run.sh"]

# .htaccess fix
# See https://github.com/mattrayner/docker-lamp/issues/124
RUN sed -i "s/<Directory \\/>/<Directory \\/var\\/www\\/>/g" "/etc/apache2/sites-available/000-default.conf"
# append .php, so healthz return healthz.php
RUN sed -i 's/AddType text\/html/AddType text\/html .php/' /etc/apache2/mods-enabled/mime.conf
