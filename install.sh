#!/bin/bash

echo "Setting up environment for CodeDay..."
cd app
mkdir -p storage
cd storage
mkdir -p cache logs meta sessions views
cd ..
cd config
cp local.sample.json local.json
cd ../..
echo "Done. Run \`composer install\`, edit app/config/local.json, then run `php artisan migrate; php artisan db:seed`."
