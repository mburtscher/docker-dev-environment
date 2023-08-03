ARG PHP_VERSION

FROM php:${PHP_VERSION}

ARG PHP_EXTENSIONS
ARG USER_ID
ARG COMPOSER_VERSION

# APT Packages
RUN apt-get update -y && apt-get install -y procps git

# PHP extensions
RUN curl -Lo /usr/local/bin/install-php-extensions https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions
RUN chmod +x /usr/local/bin/install-php-extensions
RUN install-php-extensions @composer-${COMPOSER_VERSION} xdebug ${PHP_EXTENSIONS}

# Change user
USER $USER_ID
