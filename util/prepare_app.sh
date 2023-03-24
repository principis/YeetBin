#!/bin/sh

ROOT="$PWD"

echo 'Checking if uploads directory exists...'
UPLOAD_PATH="${ROOT}/var/uploads"
if [ ! -d "$UPLOAD_PATH" ]; then
  echo 'Creating uploads directory...'
  mkdir -p 0775 "$UPLOAD_PATH"
fi

echo 'Setting permissions...'
chmod 0775 "${ROOT}/var" "${ROOT}/var/uploads"

echo 'Checking if database exists...'
DB_PATH="${ROOT}/var/sqlite.db"

if [ ! -f "$DB_PATH" ]; then
  echo 'Creating database...'
  sqlite3 "$DB_PATH" <"${ROOT}/util/schema/sqlite.schema.sql"
fi
