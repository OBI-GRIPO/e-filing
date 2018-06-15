# Σε σερβερ με Ubuntu 16.04.3 LTS 

``` bash 
apt update && apt upgrade 
apt install apt-transport-https ca-certificates curl software-properties-common
```

## Εγκατάσταση Docker

``` bash
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | apt-key add -
add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable"
apt-get update
apt-get -y install docker-ce
docker swarm init --advertise-addr xxx.xxx.xxx.xxx
curl -L https://github.com/docker/compose/releases/download/1.19.0/docker-compose-`uname -s`-`uname -m` -o /usr/local/bin/docker-compose
chmod +x /usr/local/bin/docker-compose
```

## Εναρξη του stack

```
./up.py mysecret | docker stack deploy --compose-file /dev/stdin OBI

```

 >>> * το `mysecret` είναι το ίδιο που ειχε δοθεί με την `encsecrets.py`
