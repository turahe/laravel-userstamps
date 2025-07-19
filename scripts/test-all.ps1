# Test with all databases (PowerShell version for Windows)
Write-Host "Starting Docker services..." -ForegroundColor Green
docker-compose up -d

Write-Host "Waiting for databases to be ready..." -ForegroundColor Yellow
Start-Sleep -Seconds 10

Write-Host "Running tests with SQLite (default)..." -ForegroundColor Cyan
./vendor/bin/phpunit --no-coverage

Write-Host "Running tests with MySQL..." -ForegroundColor Cyan
Copy-Item env.mysql .env
./vendor/bin/phpunit --no-coverage

Write-Host "Running tests with PostgreSQL..." -ForegroundColor Cyan
Copy-Item env.postgres .env
./vendor/bin/phpunit --no-coverage

Write-Host "All tests completed!" -ForegroundColor Green 