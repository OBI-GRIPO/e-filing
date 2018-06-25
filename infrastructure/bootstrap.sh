#!/bin/bash

#functions
function help {
               echo "Usage: bootstrap.sh --secret=mysecret devel=true"
               echo "Commands: 
                    -h --help       This help.
                    -s --secret     The secret you set with encode.py
                    -d --devel      Expose service ports to wild, otherwise you need vpn to access the services. (default is false)
                    -v --verbose    Set to show the steps (optional)                 
                    "
           }
VERBOSE=0
#handle command line options
while :
do
    case $1 in
        -h | --help | -\?)
            help
            exit 0
            ;;
        -d | --devel)
            DEVEL=$2
            shift 2
            ;;    
        --devel=*)
            DEVEL=${1#*=}
            shift
            ;;
        -s | --secret)
            SECRET=$2
            shift 2
            ;;    
        --secret=*)
            SECRET=${1#*=}
            shift
            ;;
        -v | --verbose)
            VERBOSE=$((VERBOSE+1))
            shift
            ;;
        --) 
            shift
            break
            ;;
        -*)
            echo "WARN: Unknown option (ignored): $1" >&2
            shift
            ;;
        *)  # no more options. Stop while loop
            break
            ;;
    esac
done

if [ -z "$SECRET" ]; then 
echo "you must set the secret (with flag --secret=yoursecret) see help:"
echo
help
exit 1
fi

#create network in not already exists
if [ -z `docker network ls --filter name=obi_net --format "{{.ID}}"` ] ;
then
[ $VERBOSE -ge 1 ] && echo "create network"
 docker network create -d overlay --subnet=10.6.3.0/24 --aux-address 'dns=10.6.3.254' --attachable obi_net
else
 echo 'remove old network due docker bug, i am on investicate to fill a issue' 
 docker network rm obi_net
 if [ $? -ne 0 ]; then
 echo -e "\e[1mmay need to run cleanup.sh first"
 exit 1
 fi
 echo "recreate the network"
 docker network create -d overlay --subnet=10.6.3.0/24 --aux-address 'dns=10.6.3.254' --attachable obi_net
fi

#build formio image if not exist localy
if [[ "$(docker images -q skarvelis/formio-api 2> /dev/null)" == "" ]];
then
[ $VERBOSE -ge 1 ] && echo "build form io image"
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
[ $VERBOSE -ge 1 ] && echo "start dns"
 docker-compose -p obi -f dns-docker-compose.yml up -d
fi

#start vpn
if [ -z `docker ps --filter name=openvpn* --format "{{.ID}}"` ] ;
then
[ $VERBOSE -ge 1 ] && echo "start vpn"
 docker-compose -p obi -f vpn-docker-compose.yml up -d
fi

#start stack
if [ -z `docker stack ls --format "{{.Name}}" |grep obi` ] ;
then
[ $VERBOSE -ge 1 ] && echo "start stack"
  if [ "$DEVEL" == "true" ]; then
    ./up.py $SECRET | docker stack deploy --compose-file /dev/stdin --compose-file devel.yml obi
  else
    ./up.py $SECRET | docker stack deploy --compose-file /dev/stdin obi
  fi
fi

