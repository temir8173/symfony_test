#!/bin/sh

cp /etc/mysql/conf.d/source/* /etc/mysql/conf.d/

docker-entrypoint.sh mysqld
