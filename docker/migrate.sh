#!/usr/bin/env bash

until mysql -h$PHPTOURNEY_DB_HOST -P$PHPTOURNEY_DB_PORT -u$PHPTOURNEY_DB_USERNAME -p$PHPTOURNEY_DB_PASSWORD; do
  >&2 echo "Database is unavailable - sleeping"
  sleep 1
done

>&2 echo "Database is up"

PHPTOURNEY_DB_EXISTS=$(mysql -h$PHPTOURNEY_DB_HOST -P$PHPTOURNEY_DB_PORT -u$PHPTOURNEY_DB_USERNAME -p$PHPTOURNEY_DB_PASSWORD -s -N -e "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME='$PHPTOURNEY_DB_DATABASE'");
if [ -z "$PHPTOURNEY_DB_EXISTS" ];
then
  >&2 echo 'Populate database'
  mysql -h$PHPTOURNEY_DB_HOST -P$PHPTOURNEY_DB_PORT -u$PHPTOURNEY_DB_USERNAME -p$PHPTOURNEY_DB_PASSWORD -s -N -e "CREATE DATABASE $PHPTOURNEY_DB_DATABASE DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
  mysql -h$PHPTOURNEY_DB_HOST -P$PHPTOURNEY_DB_PORT -u$PHPTOURNEY_DB_USERNAME -p$PHPTOURNEY_DB_PASSWORD $PHPTOURNEY_DB_DATABASE < /phptourney/populate.sql
fi

# Check whether version field exists
# and if not, set to 0
PHPTOURNEY_VERSION=0

# Check wether a migration exists for the given version
# and then loop through all available migrations
#migrate-$VERSION*.sh

# >&2 echo 'Migrate database'

