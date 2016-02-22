FROM skiychan/nginx-php7
MAINTAINER Antonio Hernández "antonio.hernandez@panamedia.net"

#Install ansible & php-cli & rabbitmq-server
RUN rpm -iUvh http://dl.fedoraproject.org/pub/epel/7/x86_64/e/epel-release-7-5.noarch.rpm && \
    rpm -Uvh https://mirror.webtatic.com/yum/el7/webtatic-release.rpm && \
    yum -y update && \
    yum -y install python-pip python-devel php70w-xml php70w-bcmath php70w-mbstring php70w-cli rabbitmq-server git sudo bzip2

RUN pip install --upgrade pip
RUN pip install ansible


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

RUN curl -sS https://getcomposer.org/installer | /usr/bin/php
RUN mv composer.phar /usr/local/bin/composer

ADD ./app /app
RUN /usr/local/bin/composer install --no-dev --no-progress --no-interaction --verbose --working-dir=/app
RUN chown -Rf www.www /app

#RUN mv /data/www2/* /data/www/
#RUN rm -rf /data/www2/

COPY docker_config/nginx.conf /usr/local/nginx/conf/nginx.conf
COPY docker_config/supervisord.conf /etc/supervisord.conf

WORKDIR /app
