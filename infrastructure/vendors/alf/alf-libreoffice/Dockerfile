FROM ubuntu:18.04

ENV DEBIAN_FRONTEND noninteractive

## Create a 'docker' user
RUN  addgroup --gid 9999 docker \
  && adduser --uid 9999 --gid 9999 --disabled-password --gecos "Docker User" docker \
  && usermod -L docker

RUN apt-get clean \
 && rm -rf /var/lib/apt/lists/* \
 && apt-get update \
 && apt-get install -y ghostscript libxinerama1 libdbus-glib-1-2 \
    libcairo2 libcups2 libgl1-mesa-dri libgl1-mesa-glx libsm6 curl \
    --no-install-recommends \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN apt-get update \
 && apt-get install -y ^hyphen-* --no-install-recommends \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN apt-get clean \
 && rm -rf /var/lib/apt/lists/* \
 && apt-get update \
 && apt-get install -y $(apt-cache --names-only search ^fonts-* | awk '{ print $1 }' | grep -v fonts-mathematica) --no-install-recommends \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*    


RUN apt-get update \
 && apt-get install -y ca-certificates --no-install-recommends \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

ENV LIBREOFFICE_VERSION="5.4.7" \
    LIBREOFFICE_DOWNLOAD_MIRROR="https://ftp.cc.uoc.gr/mirrors/tdf/libreoffice/stable" 

ENV LIBREOFFICE_DEB_TGZ="LibreOffice_${LIBREOFFICE_VERSION}_Linux_x86-64_deb.tar.gz" 

ENV LIBREOFFICE_HOME=/opt/libreoffice5.4 \
    PATH=/opt/libreoffice/program:$PATH

RUN mkdir /tmp/lo \
    && cd /tmp/lo \
    && echo ${LIBREOFFICE_DOWNLOAD_MIRROR}/${LIBREOFFICE_VERSION}/deb/x86_64/${LIBREOFFICE_DEB_TGZ} \
    && curl -L ${LIBREOFFICE_DOWNLOAD_MIRROR}/${LIBREOFFICE_VERSION}/deb/x86_64/${LIBREOFFICE_DEB_TGZ} | tar xz --strip-components=1 \
    && cd DEBS \
    && dpkg -i *.deb \
    && rm -fr /tmp/lo \
    && ln -s ${LIBREOFFICE_HOME} /opt/libreoffice \
    && ln -s ${LIBREOFFICE_HOME}/program/soffice /usr/bin/soffice

WORKDIR /opt/libreoffice

ENV HOST="0.0.0.0" \
    PORT="8100" 

EXPOSE 8100

COPY entrypoint.sh /opt/libreoffice/
RUN chmod +x /opt/libreoffice/entrypoint.sh

ENTRYPOINT ["/opt/libreoffice/entrypoint.sh"]
CMD ["start"]

USER docker
