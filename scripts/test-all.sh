#!/bin/bash

# Test with all databases
echo "Starting Docker services..."
docker-compose up -d

echo "Waiting for databases to be ready..."
sleep 10

echo "Running tests with SQLite (default)..."
./vendor/bin/phpunit --no-coverage

echo "Running tests with MySQL..."
cp env.mysql .env
./vendor/bin/phpunit --no-coverage

echo "Running tests with PostgreSQL..."
cp env.postgres .env
./vendor/bin/phpunit --no-coverage

echo "All tests completed!" 