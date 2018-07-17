./up.py $1 | docker-compose -f - build alfresco
./up.py $1 | docker-compose -f - build solr
./up.py $1 | docker-compose -f - build libreoffice
./up.py $1 | docker-compose -f - build share
./up.py $1 | docker-compose -f - build fio2pdf
./up.py $1 | docker-compose -f - build payum


