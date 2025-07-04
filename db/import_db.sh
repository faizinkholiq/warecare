#!/bin/sh

# goto basedir
cd $(dirname "$0")

# var
MYSQL_HOST__="warecare_db"
MYSQL_PORT__="3306"
MYSQL_USER__="root"
MYSQL_PASS__="root"
MYSQL_DB__="warecare"

docker exec $MYSQL_HOST__ /bin/sh -c '
    echo start importing data...
    for file in `find /var/db/migrations/* | grep -i '.sql'` 
    do 
        mysql -h '$MYSQL_HOST__' -P '$MYSQL_PORT__' -u '$MYSQL_USER__' -p'$MYSQL_PASS__' -D '$MYSQL_DB__' < $file
        if [ $? -eq 0 ]; then
            echo $file ✅
        else
            echo $file ❌
        fi
    done
    echo 🎉 success import data 🎉
'
