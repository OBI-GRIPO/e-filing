#!/bin/bash
#to create Alfresco And Bonita users and databases

set -e

echo "POSTGRES_USER $POSTGRES_USER"
echo "POSTGRES_DB $POSTGRES_DB"
echo "ALFRESCO_DB_USER $ALFRESCO_DB_USER"
echo "ALFRESCO_DB_USER_PASSWORD $ALFRESCO_DB_USER_PASSWORD"
echo "ALFRESCO_DB $ALFRESCO_DB"

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$POSTGRES_DB" <<-EOSQL
    CREATE ROLE "$ALFRESCO_DB_USER" WITH LOGIN PASSWORD '$ALFRESCO_DB_USER_PASSWORD';
    CREATE DATABASE "$ALFRESCO_DB";
    GRANT ALL PRIVILEGES ON DATABASE "$ALFRESCO_DB" TO "$ALFRESCO_DB_USER";
EOSQL

echo "DONE CREATING ALFRESCO DB AND USER"
