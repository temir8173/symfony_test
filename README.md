```

1) git clone
2) docker compose up -d
3) docker exec -it mysql bash
4) mysql -uroot -p123 -e "CREATE DATABASE symfony;"
5) exit
6) docker exec -it postgres bash
7) psql -U postgres -c "CREATE DATABASE symfony2"

docker exec -it php8.1 bash
cd $path_to_your_project

php bin/console generate-csv tttt2.csv 1000 10000  //(filepath, uuid-start, qty)
php bin/console import tttt.csv mysql
php bin/console import tttt.csv postgres
php bin/console import tttt.csv redis

```
