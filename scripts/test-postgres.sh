#!/bin/bash

# Test with PostgreSQL
echo "Running tests with PostgreSQL..."
cp env.postgres .env
./vendor/bin/phpunit --no-coverage 