FROM tomcat:7.0.81-jre8

ENV ARTIFACTS=https://artifacts.alfresco.com/nexus/content/groups/public

WORKDIR /usr/local/tomcat/

ENV VERSION=5.2.g

## WAR
RUN set -x && \
    curl --location ${ARTIFACTS}/org/alfresco/alfresco-solr4/${VERSION}/alfresco-solr4-${VERSION}.war \
      -o alfresco-solr4-${VERSION}.war \
    && unzip alfresco-solr4-${VERSION}.war -d webapps/solr4 \
    && rm alfresco-solr4-${VERSION}.war

## CONFIGURATION
RUN set -x && \
    curl --location ${ARTIFACTS}/org/alfresco/alfresco-solr4/${VERSION}/alfresco-solr4-${VERSION}-config.zip \
      -o alfresco-solr4-${VERSION}-config.zip \
    && unzip alfresco-solr4-${VERSION}-config.zip -d /opt/solr/ \
    && rm alfresco-solr4-${VERSION}-config.zip

COPY assets/web.xml webapps/solr4/WEB-INF/web.xml
COPY assets/workspace/* /opt/solr/workspace-SpacesStore/conf/
COPY assets/archive/* /opt/solr/archive-SpacesStore/conf/

RUN mkdir /opt/solr_data/ && rm -rf \
     webapps/docs webapps/examples webapps/manager \
     webapps/host-manager /usr/share/doc

ENV JAVA_OPTS " -XX:-DisableExplicitGC -Djava.security.egd=file:/dev/./urandom -Djava.awt.headless=true -Dfile.encoding=UTF-8 "


EXPOSE 8080

VOLUME "/opt/solr_data/"
WORKDIR /root
