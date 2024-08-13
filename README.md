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

Create a .env file based on the .env.example and install the dependencies:
```
cp ./src/.env.example ./src/.env; cd src; composer install; cd ../
```

Create the docker container:
```
docker-compose --env-file src/.env up --build
```

Database migrations:
```
docker exec -t app php artisan migrate
```

If you wish to seed the database with dummy data for testing:
```
docker exec -t app php artisan db:seed
```

Run the automated tests through PHPUnit, note that this tests do not require seeding nor affect the data in production since they run on a disposable SQLite Database in memory:
```
docker exec -t app php artisan test
```
 
## Swagger Documentation

The API documentation can be accessed through Swagger UI, which provides an interactive way to explore and test the API endpoints. To view the documentation access: `http://localhost:8080/swagger`

## phpMyAdmin
To access the database access: `http://localhost:8081`
The default credentials are:
```
username: root
password: root
```

## **Endpoints**
 **Base URL**: `http://localhost:8080/api`
 
    -   `POST /register` - Register to retrieve an auth token
    -   `POST /login` - Login to retrieve an auth token
    -   `GET /v1/holidayplans` - List all holiday plans
    -   `GET /v1/holidayplans/{id}` - Get a specific holiday plan by ID
    -   `POST /v1/holidayplans` - Create a new holiday plan
    -   `PUT /v1/holidayplans/{id}` - Update an existing holiday plan
    -   `DELETE /v1/holidayplans/{id}` - Delete a holiday plan
    -   `GET /v1/holidayplans/{id}/pdf` - Generate and download a PDF for a holiday plan

## Common Issues

### CRSF Token Mismatch
If you send a requisition to the API through a domain that isn't the sanctum stateful domain (configured in the .env file) you will need to send a request to the endpoint `GET /sanctum/csrf-cookie` and then include the cookie that this endpoint sent to the headers of the following requisitions. 
```json
[{"key":"X-XSRF_TOKEN","value":"{{xsrf-cookie}}"}]
```

### Route [login] not defined.
Sometimes, mainly when you send a request to a protected endpoint without the token, you might get a response in the form of a View, rather than a Json. To avoid that, remember to include on the requisition header:
```json
[{"key":"Accept","value":"application/json"}]
```