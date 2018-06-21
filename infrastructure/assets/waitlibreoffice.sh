#!/bin/bash
LINE=""
while [[ ${LINE} != *"com.sun.star.bridge.XProtocolProperties"* ]]; do
exec {FD}</dev/tcp/libreoffice/8100
read -t1 LINE <&$FD
echo -n "."
done
