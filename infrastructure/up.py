#!/usr/bin/env python3
import sys
import base64 

def decode(key, enc):
    dec = []
    enc = base64.urlsafe_b64decode(enc).decode('utf8')
    for i in range(len(enc)):
        key_c = key[i % len(key)]
        dec_c = chr((256 + ord(enc[i]) - ord(key_c)) % 256)
        dec.append(dec_c)
    return "".join(dec)

docker_compose = open("docker-compose.yml", "r").read()
docker_compose = docker_compose.replace("{--formio_host--}", decode(sys.argv[1],open("secrets/formio_host", "r").read()))
docker_compose = docker_compose.replace("{--formio_host_ip--}", decode(sys.argv[1],open("secrets/formio_host_ip", "r").read()))
docker_compose = docker_compose.replace("{--formio_admin_email--}", decode(sys.argv[1],open("secrets/formio_admin_email", "r").read()))
docker_compose = docker_compose.replace("{--formio_admin_password--}", decode(sys.argv[1],open("secrets/formio_admin_password", "r").read()))
docker_compose = docker_compose.replace("{--formio_jwt_secret--}", decode(sys.argv[1],open("secrets/formio_jwt_secret", "r").read()))
docker_compose = docker_compose.replace("{--bd_vnc_password--}", decode(sys.argv[1],open("secrets/bd_vnc_password", "r").read()))
docker_compose = docker_compose.replace("{--bd_user--}", decode(sys.argv[1],open("secrets/bd_user", "r").read()))
docker_compose = docker_compose.replace("{--bd_email--}", decode(sys.argv[1],open("secrets/bd_email", "r").read()))
docker_compose = docker_compose.replace("{--bd_password--}", decode(sys.argv[1],open("secrets/bd_password", "r").read()))
docker_compose = docker_compose.replace("{--postgres_password--}", decode(sys.argv[1],open("secrets/postgres_password", "r").read()))
docker_compose = docker_compose.replace("{--bonita_tenant_login--}", decode(sys.argv[1],open("secrets/bonita_tenant_login", "r").read()))
docker_compose = docker_compose.replace("{--bonita_tenant_password--}", decode(sys.argv[1],open("secrets/bonita_tenant_password", "r").read()))
docker_compose = docker_compose.replace("{--bonita_platform_login--}", decode(sys.argv[1],open("secrets/bonita_platform_login", "r").read()))
docker_compose = docker_compose.replace("{--bonita_platform_password--}", decode(sys.argv[1],open("secrets/bonita_platform_password", "r").read()))
docker_compose = docker_compose.replace("{--bonita_db_pass--}", decode(sys.argv[1],open("secrets/bonita_db_pass", "r").read()))
docker_compose = docker_compose.replace("{--bonita_db_biz_pass--}", decode(sys.argv[1],open("secrets/bonita_db_biz_pass", "r").read()))
docker_compose = docker_compose.replace("{--alfresco_password--}", decode(sys.argv[1],open("secrets/alfresco_password", "r").read()))
docker_compose = docker_compose.replace("{--alfresco_db_password--}", decode(sys.argv[1],open("secrets/alfresco_db_password", "r").read()))
docker_compose = docker_compose.replace("{--alfresco_db_user--}", decode(sys.argv[1],open("secrets/alfresco_db_user", "r").read()))
docker_compose = docker_compose.replace("{--alfresco_host_share--}", decode(sys.argv[1],open("secrets/alfresco_host_share", "r").read()))
docker_compose = docker_compose.replace("{--alfresco_host--}", decode(sys.argv[1],open("secrets/alfresco_host", "r").read()))
docker_compose = docker_compose.replace("{--alfresco_host_ip--}", decode(sys.argv[1],open("secrets/alfresco_host_ip", "r").read()))
docker_compose = docker_compose.replace("{--mysql_password--}", decode(sys.argv[1],open("secrets/mysql_password", "r").read()))
docker_compose = docker_compose.replace("{--wso2_db_pass--}", decode(sys.argv[1],open("secrets/wso2_db_pass", "r").read()))


print (docker_compose)
