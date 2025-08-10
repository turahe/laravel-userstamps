#!/bin/bash

# Test with MySQL
echo "Starting Docker services..."
docker compose up -d

echo "Waiting for MySQL to be ready..."
sleep 15

echo "Setting up initial environment..."
if [ ! -f ".env" ]; then
    cp env.mysql .env
fi

echo "Running tests with MySQL..."
docker compose exec -T app ./vendor/bin/phpunit --no-coverage

echo "MySQL tests completed!"
echo "Cleaning up Docker containers..."
docker compose down --remove-orphans
echo "Docker containers cleaned up successfully!" 