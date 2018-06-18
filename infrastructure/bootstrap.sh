#!/bin/bash

#create network in not already exists
if [ -z `docker network ls --filter name=obi_net --format "{{.ID}}"` ] ;
then
 echo "create network"
 docker network create -d overlay --subnet=10.6.3.0/24 --aux-address 'dns=10.6.3.254' --attachable obi_net
fi

#build formio image if not exist localy
if [[ "$(docker images -q skarvelis/formio-api 2> /dev/null)" == "" ]];
then
 echo "build form io image"
 docker-compose build formio-api
fi

#build wso2 image  if not exist localy
if [[ "$(docker images -q wso2is 2> /dev/null)" == "" ]];
then
 echo "build wso2 image"
 docker-compose build wso2
fi

#start dns
if [ -z `docker ps --filter name=dns* --format "{{.ID}}"` ] ;
then
 echo "start dns"
 docker-compose -p obi -f dns-docker-compose.yml up -d
fi

#start vpn
if [ -z `docker ps --filter name=openvpn* --format "{{.ID}}"` ] ;
then
 echo "start vpn"
 docker-compose -p obi -f vpn-docker-compose.yml up -d
fi

#start stack
if [ -z `docker stack ls --format "{{.Name}}" |grep obi` ] ;
then
 echo "start stack"
 ./up.py $1 | docker stack deploy --compose-file /dev/stdin obi
fi


