FROM ubuntu:latest

MAINTAINER Filis Futsarov "filisfutsarov@gmail.com"

RUN apt-get update && \
    apt-get --no-install-recommends -q -y install git apt-utils curl ca-certificates apt-transport-https software-properties-common lsb-release sudo

# this is mainly to a avoid Timezone questions when installing PHP
RUN echo 'debconf debconf/frontend select Noninteractive' | debconf-set-selections

COPY install_php.sh /opt/src/scripts/setup.sh
RUN ["/opt/src/scripts/setup.sh"]

## COMPOSER
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir="/usr/bin" --filename=composer

RUN composer self-update --2.2 # LTS to support php7.1
