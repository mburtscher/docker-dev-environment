#!/bin/bash -e

# Install extension installer
echo "Installing extension installer…"
curl -Lo /usr/local/bin/install-php-extensions https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions
chmod +x /usr/local/bin/install-php-extensions

# Install composer and extensions
echo "Installing composer and extensions…"
install-php-extensions @composer $EXTENSIONS

# Run setup script
if [ ! -z "$SETUP_SCRIPT" ]
then
  echo "Running setup script…"
  composer run-script $SETUP_SCRIPT
fi

# Run the dev server
echo "Running the dev server on port 8080…"
php -S 0.0.0.0:8080 -t $DOCUMENT_ROOT
