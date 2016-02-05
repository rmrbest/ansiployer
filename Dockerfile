FROM richarvey/nginx-php-fpm
MAINTAINER Antonio Hernández "antonio.hernandez@panamedia.net"

#Install ansible
RUN apt-get update && apt-get install -y software-properties-common && \
    apt-add-repository ppa:ansible/ansible && \
    apt-get update && apt-get install -y ansible

#Install ansistrano
COPY ./ansible.cfg /etc/ansible/ansible.cfg
RUN mkdir /roles
RUN chown -Rf www-data.www-data /roles
RUN ansible-galaxy install carlosbuenosvinos.ansistrano-deploy carlosbuenosvinos.ansistrano-rollback

VOLUME /playbook

RUN mkdir /code
RUN chown -Rf www-data.www-data /code

COPY ./app /usr/share/nginx/html
RUN chown -Rf www-data.www-data /usr/share/nginx/html
RUN chmod -R 755 /usr/share/nginx/html/log
RUN chown -Rf www-data.www-data /var/www

#RUN crontab /usr/share/nginx/html/crontab.txt

WORKDIR /usr/share/nginx/html