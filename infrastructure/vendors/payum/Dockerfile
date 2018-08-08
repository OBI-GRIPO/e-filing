FROM ubuntu:16.04

ENV LC_ALL=C.UTF-8

RUN apt-get update && \
    apt-get install -y --no-install-recommends --no-install-suggests openssl pkg-config libssl-dev libsslcommon2-dev && \
    rm -rf /var/lib/apt/lists/*

RUN apt-get update && \
    apt-get -y --no-install-recommends --no-install-suggests install software-properties-common python-software-properties && \
    add-apt-repository ppa:ondrej/php && \
    add-apt-repository ppa:ondrej/pkg-gearman && \
    rm -rf /var/lib/apt/lists/*


#nginx and friends
RUN apt-get update && \
    apt-get remove php7.0 && \
    apt-get install -y --no-install-recommends --no-install-suggests ca-certificates gettext nginx nginx-extras && \
    rm -rf /var/lib/apt/lists/*

# php stuff
RUN apt-get update && \
    apt-get install -y --no-install-recommends --no-install-suggests \
    php7.1 php7.1-fpm php7.1-cli php7.1-common \
    php7.1-mongodb php7.1-curl php7.1-intl php7.1-soap php7.1-xml php7.1-mcrypt php7.1-bcmath \
    php7.1-mysql php7.1-amqp php7.1-gearman php7.1-mbstring php7.1-ldap php7.1-zip php7.1-gd php7.1-xdebug php7.1-imagick && \
    rm -f /etc/php/7.1/cli/conf.d/*xdebug.ini && \
    rm -f /etc/php/7.1/fpm/conf.d/*xdebug.ini && \
    rm -rf /var/lib/apt/lists/*

# helpers
RUN apt-get update && \
    apt-get install -y --no-install-recommends --no-install-suggests mc vim htop && \
    rm -rf /var/lib/apt/lists/*


# git and build stuff
RUN apt-get update && \
    apt-get install -y --no-install-recommends --no-install-suggests git && \
    rm -rf /var/lib/apt/lists/*

# forward request and error logs to docker log collector
RUN ln -sf /dev/stderr /var/log/nginx/access.log \
	&& ln -sf /dev/stderr /var/log/nginx/error.log \
	&& ln -sf /dev/stderr /var/log/php7.1-fpm.log \
	&& ln -sf /dev/stderr /var/log/php-fpm.log

RUN rm -f /etc/nginx/sites-enabled/*

COPY nginx.conf.tpl /tmp/nginx.conf.tpl
COPY php-fpm.conf.tpl /tmp/php-fpm.conf.tpl
COPY defaults.ini /etc/php/7.1/cli/conf.d/defaults.ini
COPY defaults.ini /etc/php/7.1/fpm/conf.d/defaults.ini

RUN mkdir -p /run/php && touch /run/php/php7.1-fpm.sock && touch /run/php/php7.1-fpm.pid

COPY entrypoint.sh /entrypoint.sh
RUN chmod 755 /entrypoint.sh

ENV PAYUM_DEBUG 0
ENV NGINX_WEB_ROOT=/payum/public
ENV NGINX_PHP_FALLBACK=/index.php
ENV NGINX_PHP_LOCATION='^/index\.php(/|$)'

EXPOSE 80

ADD . /payum
WORKDIR /payum

RUN php7.1 -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php7.1 -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" &&\
    php7.1 composer-setup.php &&\
    php7.1 -r "unlink('composer-setup.php');"

#ignore errors
RUN php7.1 composer.phar install ; exit 0

# updates
RUN ./update-psl-icann-section


ENTRYPOINT ["/entrypoint.sh"]
