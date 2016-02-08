FROM skiychan/nginx-php7
MAINTAINER Antonio Hernández "antonio.hernandez@panamedia.net"

#Install ansible
RUN rpm -iUvh http://dl.fedoraproject.org/pub/epel/7/x86_64/e/epel-release-7-5.noarch.rpm && \
    yum -y update && \
    yum -y install ansible

#Install ansistrano
COPY docker_config/ansible.cfg /etc/ansible/ansible.cfg
RUN mkdir /roles
RUN chown -Rf www.www /roles
RUN ansible-galaxy install carlosbuenosvinos.ansistrano-deploy carlosbuenosvinos.ansistrano-rollback

VOLUME /playbook

RUN mkdir /code
RUN chown -Rf www.www /code

COPY ./app /data/www
RUN chmod -R 755 /data/www/log

COPY docker_config/nginx.conf /usr/local/nginx/conf/nginx.conf
#RUN chown -Rf www.www /var/www

#RUN echo "www ALL=(ALL) NOPASSWD:ALL" | sudo tee -a /etc/sudoers

WORKDIR /data/www