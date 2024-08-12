# Buzzvel 2024 Dev Team Project

This is a project of a Holiday Planning API, built for the Buzzvel selective process.

## Features

- Basic CRUD operation related to Holiday Plans and its participants
- Authentication with Sanctum to make those operations
- Exporting of one of the Holiday Plan into a PDF file

## Technologies Used

- Laravel
- Docker
- Swagger-UI

## Getting Started

### Pre-requisites

- PHP 8.2
- Docker
- Composer

### Building the application

After clonning the repository, run the following commands on its root:

Create a .env file based on the .env.example:
```
cp ./src/.env.example ./src/.env
```

Create the docker container:
```
docker compose -f docker/docker-compose.yml --env-file ./src/.env up --build
```

Database migrations:
```
docker exec -t vacation_plan_api php artisan migrate
```

If you wish to seed the database with dummy data for testing:
```
docker exec -t vacation_plan_api php artisan db:seed
```

Run the automated tests through PHPUnit, note that this tests do not require seeding since they run on a SQLite Database in memory:
```
docker exec -t vacation_plan_api php artisan test
```
 
## Swagger Documentation

The API documentation can be accessed through Swagger UI, which provides an interactive way to explore and test the API endpoints. To view the documentation access: `http://localhost:PORT/swagger`

## **Endpoints**
 **Base URL**: `http://localhost:PORT/api/v1`
 
    -   `GET /holidayplans` - List all holiday plans
    -   `GET /holidayplans/{id}` - Get a specific holiday plan by ID
    -   `POST /holidayplans` - Create a new holiday plan
    -   `PUT /holidayplans/{id}` - Update an existing holiday plan
    -   `DELETE /holidayplans/{id}` - Delete a holiday plan
    -   `GET /holidayplans/{id}/pdf` - Generate and download a PDF for a holiday plan

## Common Issues

### CRSF Token Mismatch
If you send a requisition to the API through a domain that isn't the sanctum stateful domain (configured in the .env file) you will need to send a request to the endpoint `GET /sanctum/csrf-cookie` and then include the cookie that this endpoint sent to the headers of the following requisitions. 
```json
[{"key":"X-XSRF_TOKEN","value":"{{xsrf-cookie}}"}]
```