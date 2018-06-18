## init openvpn
docker-compose -p obi run --rm openvpn ovpn_genconfig -u udp://e-filing.obi.gr
docker-compose -p obi run --rm openvpn ovpn_initpki

## create clients
docker-compose -p obi run --rm openvpn easyrsa build-client-full dev nopass
docker-compose -p obi run --rm openvpn ovpn_getclient dev > dev.ovpn

