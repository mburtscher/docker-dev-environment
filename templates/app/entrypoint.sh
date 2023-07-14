#!/bin/bash -e

# Run setup script
if [ ! -z "$SETUP_SCRIPT" ]
then
  echo "Running setup script…"
  composer run-script $SETUP_SCRIPT
fi

# Run the dev server
echo "Running the dev server on port 8080…"
php -S 0.0.0.0:8080 -t $DOCUMENT_ROOT
