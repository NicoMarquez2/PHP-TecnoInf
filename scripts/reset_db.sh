#!/bin/bash
# Edit DB_USER, DB_PASS, DB_NAME as needed
DB_USER="root"
DB_PASS=""
DB_NAME="restaurant"
echo "Resetting DB: $DB_NAME (you may be asked for mysql password)"
mysql -u $DB_USER -p$DB_PASS $DB_NAME < reset_dump.sql
echo "Done"
