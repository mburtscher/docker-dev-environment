ARG PHP_VERSION

FROM php:${PHP_VERSION}

ARG PHP_EXTENSIONS
ARG PACKAGES
ARG USER_ID

# APT Packages
RUN echo ${PACKAGES}
RUN apt-get update -y && apt-get install -y procps ${PACKAGES}

# PHP extensions
RUN curl -Lo /usr/local/bin/install-php-extensions https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions
RUN chmod +x /usr/local/bin/install-php-extensions
RUN install-php-extensions @composer xdebug ${PHP_EXTENSIONS}

# Change user
USER $USER_ID
