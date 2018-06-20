#!/bin/bash

set -e

PATH=$LIBREOFFICE_HOME/program:$PATH

if [ "$1" == "start" ]
then
# work arround bug https://bugs.documentfoundation.org/show_bug.cgi?id=107912
count=1
while [ $count -le 9 ]
do
    echo "Start Daemon (bug work arround!!!)"
    flo=$(soffice.bin --cat /etc/issue || true)
    echo "Daemon crash! try once again $count" 
    soffice.bin --nofirststartwizard --nologo --headless --norestore --invisible --accept='socket,host=0.0.0.0,port=8100,tcpNoDelay=1;urp'
done
    echo "Daemon crash! give up"
else
	exec "$@"
fi
