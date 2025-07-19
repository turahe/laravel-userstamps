#!/bin/bash

# Test with MySQL
echo "Running tests with MySQL..."
cp env.mysql .env
./vendor/bin/phpunit --no-coverage 