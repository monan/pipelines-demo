#!/bin/sh

# Usage ./build.sh ~/Sites/demo.local --dev
TARGET=$1
shift

echo '      ____________________________'
echo '     _/\/\/\/\/\____/\/\/\/\/\/\_'
echo '    _/\/\____/\/\__/\/\_________'
echo '   _/\/\____/\/\__/\/\/\/\/\___'
echo '  _/\/\____/\/\__/\/\_________'
echo ' _/\/\/\/\/\____/\/\_________'
echo '____________________________'

if [ -d $TARGET ]; then
  yes "yes" | rm -rf $TARGET
fi

# Remove existing build files.
if [ -d docroot/sites ]; then
  chmod -R 777 docroot/sites
fi
rm -rf bin composer.lock docroot vendor

# Use Composer to attempt to install dependencies.
composer clear-cache
composer install $@

# Copy build files to target directory.
rsync -a . $TARGET
