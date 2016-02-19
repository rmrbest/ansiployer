#!/usr/bin/env bash
sudo docker stop deployment
sudo docker rm deployment
sudo docker rmi antonienko/ansiployer
sudo docker build -t antonienko/ansiployer .
sudo docker run -it -d -p 3000:80 -v $(pwd)/playbook:/playbook -v $(pwd)/app:/app --name deployment antonienko/ansiployer