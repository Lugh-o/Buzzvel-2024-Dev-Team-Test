#!/bin/bash

# Check if the directory exists, if not, create it
if [ ! -d "../src/public/swagger" ]; then
  mkdir -p ../src/public/swagger
fi

# Generate Swagger documentation in JSON format
php ../src/vendor/bin/openapi --output ../src/public/swagger/swagger.json ../src/app/Http/Controllers/Api/V1 ../swagger/swagger-v1.php

# Generate Swagger documentation in YAML format
php ../src/vendor/bin/openapi --output ../src/public/swagger/openapi.yaml --format yaml ../src/app/Http/Controllers/Api/V1 ../swagger/swagger-v1.php

# Verify the generation
if [ -f "../src/public/swagger/swagger.json" ] && [ -f "../src/public/swagger/openapi.yaml" ]; then
  echo "Swagger documentation generated successfully in JSON and YAML formats."
else
  echo "Failed to generate Swagger documentation in JSON and/or YAML formats."
fi
