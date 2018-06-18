#!/bin/bash

#create network in not already exists
if [ -z `docker network ls --filter name=obi_net --format "{{.ID}}"` ]
then
 echo "create network"
 docker network create -d overlay --subnet=10.6.3.0/24 --aux-address 'dns=10.6.3.254' --attachable obi_net
fi

#start dns
if [ -z `docker ps --filter name=dns* --format "{{.ID}}"` ]
then
 echo "start dns"
 docker-compose -p obi -f dns-docker-compose.yml up -d
fi

#start vpn
if [ -z `docker ps --filter name=openvpn* --format "{{.ID}}"` ]
then
 echo "start vpn"
 docker-compose -p obi -f vpn-docker-compose.yml up -d
fi

#start stack
if [ -z `docker stack ls --format "{{.Name}}" |grep obi` ]
then
 echo "start stack"
 ./up.py $1 | docker stack deploy --compose-file /dev/stdin obi
fi


