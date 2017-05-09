#!/bin/sh

# Use: Run this file to install Demo Framework and optionally enable a scenario.
# Example: ./install.sh ../d8df dfs_tec

TARGET=$1
SCENARIO=$2

# Ensure the target directory exists.
if [ -r $TARGET/docroot ]; then
  cd $TARGET/docroot
fi

# Install the profile.
if [ ! -r sites/default/settings.php ]; then
  echo "No DB connection for docroot: $TARGET. Ensure settings.php is configured before installation."
else
  drush si df --account-name=admin --account-pass=presales -y
  # Enable optional scenario.
  if [ ! -z "$SCENARIO" ]; then
    drush es $SCENARIO
  fi
fi
