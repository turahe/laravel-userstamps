#!/bin/bash

# Test with all databases
echo "Starting Docker services..."
docker compose up -d

echo "Waiting for databases to be ready..."
sleep 15

echo "Setting up initial environment..."
if [ ! -f ".env" ]; then
    cp env.mysql .env
fi

echo "Running tests with MySQL (default)..."
docker compose exec -T app ./vendor/bin/phpunit --no-coverage

echo "Running tests with PostgreSQL..."
docker compose exec -T app cp env.postgres .env
docker compose exec -T app ./vendor/bin/phpunit --no-coverage

echo "Cleaning up..."
docker compose exec -T app cp env.mysql .env

echo "All tests completed!"
echo "Cleaning up Docker containers..."
docker compose down --remove-orphans
echo "Docker containers cleaned up successfully!" 