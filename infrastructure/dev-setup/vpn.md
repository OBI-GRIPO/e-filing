## init openvpn
docker-compose -p obi -f vpn-docker-compose.yml run --rm openvpn ovpn_genconfig -u udp://e-filing.obi.gr
docker-compose -p obi -f vpn-docker-compose.yml run --rm openvpn ovpn_initpki

## create clients
docker-compose -p obi -f vpn-docker-compose.yml run --rm openvpn easyrsa build-client-full dev nopass
docker-compose -p obi -f vpn-docker-compose.yml run --rm openvpn ovpn_getclient dev > dev.ovpn

