#!/bin/sh

# Go to the script's directory
cd "$(dirname "$0")"

# Configuration
MYSQL_HOST__="warecare_db"
MYSQL_PORT__="3306"
MYSQL_USER__="root"
MYSQL_PASS__="root"
MYSQL_DB__="warecare"
MIGRATION_PATH="/var/migrations"

echo "⏳ Starting full migration (drop and re-create database)..."

docker exec -i $MYSQL_HOST__ /bin/sh -c "
    echo 'Dropping and recreating database: $MYSQL_DB__';
    mysql -u$MYSQL_USER__ -p$MYSQL_PASS__ -e \"
        DROP DATABASE IF EXISTS $MYSQL_DB__;
        CREATE DATABASE $MYSQL_DB__;
    \"
"

echo "✅ Database '$MYSQL_DB__' recreated."

# Now apply all SQL migrations inside /var/migrations
docker exec -i $MYSQL_HOST__ /bin/sh -c "
    echo '⏳ Running migrations...';
    for file in \$(find $MIGRATION_PATH -type f -name '*.sql' | sort); do
        echo \"⚙️  Running \$file\";
        mysql -u$MYSQL_USER__ -p$MYSQL_PASS__ $MYSQL_DB__ < \$file
        if [ \$? -eq 0 ]; then
            echo \"\$file ✅\"
        else
            echo \"\$file ❌\"
        fi
    done
    echo '🎉 All migrations applied.'
"
