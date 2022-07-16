# Build
run ``./docker-build.sh``

run ``./docker-run.sh``

In first run - exec 

``sudo docker exec rezet-tt-app-1 ./artisan migrate -n --force``

for run database migrations.

Define environment variables:

``GOOGLE_CLIENT_ID``

``GOOGLE_CLIENT_SECRET``

``GOOGLE_CLIENT_REDIRECT``

``OPENWEATHER_API_KEY`` - API key for Openweather API 
