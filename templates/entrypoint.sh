#!/bin/sh -e

# Install extension installer
echo "Installing extension installer…"
curl -Lo /usr/local/bin/install-php-extensions https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions
chmod +x /usr/local/bin/install-php-extensions

# Install composer and extensions
echo "Installing composer and extensions…"
install-php-extensions @composer $EXTENSIONS

# Install dependencies
echo "Installing composer dependencies…"
composer install

# Run the dev server
echo "Running the dev server on port :8080…"
php -S 0.0.0.0:8080 -t $DOCUMENT_ROOT
