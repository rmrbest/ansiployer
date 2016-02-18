FROM skiychan/nginx-php7
MAINTAINER Antonio Hernández "antonio.hernandez@panamedia.net"

#Install ansible & php-cli & rabbitmq-server
RUN rpm -iUvh http://dl.fedoraproject.org/pub/epel/7/x86_64/e/epel-release-7-5.noarch.rpm && \
    rpm -Uvh https://mirror.webtatic.com/yum/el7/webtatic-release.rpm && \
    yum -y update && \
    yum -y install ansible && \
    yum -y install php70w-xml && \
    yum -y install php70w-bcmath && \
    yum -y install php70w-mbstring && \
    yum -y install php70w-cli && \
    yum -y install rabbitmq-server

#Install ansistrano
COPY docker_config/ansible.cfg /etc/ansible/ansible.cfg
RUN mkdir /roles
RUN chown -Rf www.www /roles
RUN ansible-galaxy install carlosbuenosvinos.ansistrano-deploy carlosbuenosvinos.ansistrano-rollback

VOLUME /playbook

RUN mkdir /code
RUN chown -Rf www.www /code

RUN mkdir /assets
RUN chown -Rf www.www /assets

RUN mkdir /logs
RUN chown -Rf www.www /logs

COPY ./app /data/www
RUN curl -sS https://getcomposer.org/installer | /usr/bin/php
RUN mv composer.phar /usr/local/bin/composer
RUN /usr/local/bin/composer install --no-dev --working-dir=/data/www

COPY docker_config/nginx.conf /usr/local/nginx/conf/nginx.conf
COPY docker_config/supervisord.conf /etc/supervisord.conf

WORKDIR /data/www