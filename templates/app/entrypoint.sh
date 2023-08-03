#!/bin/bash -e

# Run the dev server
echo "Running the dev server on port 8080…"
php -S 0.0.0.0:8080 -t $DOCUMENT_ROOT
