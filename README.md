## Buzzvel 2024 Dev Team Project

docker compose -f docker/docker-compose.yml --env-file ./src/.env up --build
docker exec -t vacation_plan_api php artisan migrate
docker exec -t vacation_plan_api php artisan db:seed