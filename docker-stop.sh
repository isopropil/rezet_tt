#!/usr/bin/env bash

sudo docker compose -f docker-compose.yml -p rezet-tt down
sudo docker exec rezet-tt-app-1 ./artisan migrate -n --force
sudo docker ps
