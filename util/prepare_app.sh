#!/bin/sh

# Run as root
if [ "$(id -u)" -ne 0 ]; then
    exec sudo -u root "$0" "$@"
    exit 0
fi

ROOT="$PWD"
echo 'Checking if uploads directory exists...'
UPLOAD_PATH="${ROOT}/var/uploads"
if [ ! -d "$UPLOAD_PATH" ]; then
  echo 'Creating uploads directory...'
  mkdir -p "$UPLOAD_PATH"
fi

echo 'Checking if cache directory exists...'
CACHE_PATH="${ROOT}/var/cache"
if [ ! -d "$CACHE_PATH" ]; then
  echo 'Creating cache directory...'
  mkdir -p "$CACHE_PATH"
fi

echo 'Setting permissions...'
chmod 0775 "${ROOT}/var" "${ROOT}/var/uploads" "${ROOT}/var/cache"

echo 'Checking if database exists...'
DB_PATH="${ROOT}/var/sqlite.db"

if [ ! -f "$DB_PATH" ]; then
  echo 'Creating database...'
  sqlite3 "$DB_PATH" <"${ROOT}/util/schema/sqlite.schema.sql"
fi

echo 'Changing owner...'
chown -R docker:docker "${ROOT}/var"