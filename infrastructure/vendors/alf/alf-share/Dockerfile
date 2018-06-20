FROM tomcat:7.0.81-jre8

ENV ARTIFACTS=https://artifacts.alfresco.com/nexus/content/groups/public

WORKDIR /usr/local/tomcat/

ENV MMT=5.2.g

## MMT
RUN set -x && \
    curl --location \
      ${ARTIFACTS}/org/alfresco/alfresco-mmt/${MMT}/alfresco-mmt-${MMT}.jar \
      -o /root/alfresco-mmt.jar && \
      mkdir /root/amp

ENV VERSION=5.2.f

## SHARE
RUN set -x && \
    curl  --location \
      ${ARTIFACTS}/org/alfresco/share/${VERSION}/share-${VERSION}.war \
      -o share-${VERSION}.war && \
    unzip  share-${VERSION}.war -d webapps/share && \
    rm share-${VERSION}.war

RUN set -x \
      && sed -i 's/^log4j.rootLogger.*/log4j.rootLogger=error, Console/' webapps/share/WEB-INF/classes/log4j.properties \
      && mkdir -p shared/classes/alfresco/web-extension \
                  shared/lib \
      && rm -rf /usr/share/doc \
                webapps/docs \
                webapps/examples \
                webapps/manager \
                webapps/host-manager

COPY assets/catalina.properties conf/catalina.properties
COPY assets/share-config-custom.xml shared/classes/alfresco/web-extension/share-config-custom.xml
COPY assets/server.xml conf/server.xml
COPY assets/logging.properties conf/logging.properties

ENV JAVA_OPTS " -XX:-DisableExplicitGC -Djava.security.egd=file:/dev/./urandom -Djava.awt.headless=true -Dfile.encoding=UTF-8 "

EXPOSE 8080

WORKDIR /root
