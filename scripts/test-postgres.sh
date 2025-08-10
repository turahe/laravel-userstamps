#!/bin/bash

# Test with PostgreSQL
echo "Starting Docker services..."
docker compose up -d

echo "Waiting for PostgreSQL to be ready..."
sleep 15

echo "Setting up initial environment..."
if [ ! -f ".env" ]; then
    cp env.mysql .env
fi

echo "Running tests with PostgreSQL..."
docker compose exec -T app cp env.postgres .env
docker compose exec -T app ./vendor/bin/phpunit --no-coverage

echo "PostgreSQL tests completed!"
echo "Cleaning up Docker containers..."
docker compose down --remove-orphans
echo "Docker containers cleaned up successfully!" 