#!/bin/bash
until eval 'exec {FD}</dev/tcp/mongodb/27017' 2> >(logger)
do
echo -n "."
sleep 5
done
eval 'exec $fd>&-'
sleep 10
