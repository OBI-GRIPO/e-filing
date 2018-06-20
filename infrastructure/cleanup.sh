#!/bin/bash
read -p "Are you sure? (Y/y)" -n 1 -r
echo 
if [[ $REPLY =~ ^[Yy]$ ]]
then
 docker stack rm obi 2>/dev/null
 docker-compose -p obi -f dns-docker-compose.yml stop
 docker-compose -p obi -f vpn-docker-compose.yml stop
 docker network rm obi_net 2>/dev/null
 docker rm $(docker ps -a -q) 2>/dev/null
fi
