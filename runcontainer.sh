#!/usr/bin/env bash
docker stop deployment
docker rm deployment
docker rmi antonienko/ansiployer
docker build -t antonienko/ansiployer .
docker run -it -d -p 3000:80 -v $(pwd)/playbook:/playbook --name deployment antonienko/ansiployer
