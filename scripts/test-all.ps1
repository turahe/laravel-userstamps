# Test with all databases (PowerShell version for Windows)
Write-Host "Starting Docker services..." -ForegroundColor Green
docker compose up -d

Write-Host "Waiting for databases to be ready..." -ForegroundColor Yellow
Start-Sleep -Seconds 15

Write-Host "Setting up initial environment..." -ForegroundColor Cyan
if (-not (Test-Path ".env")) {
    Copy-Item "env.mysql" ".env"
}

Write-Host "Running tests with MySQL (default)..." -ForegroundColor Cyan
docker compose exec -T app ./vendor/bin/phpunit --no-coverage

Write-Host "Running tests with PostgreSQL..." -ForegroundColor Cyan
docker compose exec -T app cp env.postgres .env
docker compose exec -T app ./vendor/bin/phpunit --no-coverage

Write-Host "Cleaning up..." -ForegroundColor Yellow
docker compose exec -T app cp env.mysql .env

Write-Host "All tests completed!" -ForegroundColor Green
Write-Host "Cleaning up Docker containers..." -ForegroundColor Yellow
docker compose down --remove-orphans
Write-Host "Docker containers cleaned up successfully!" -ForegroundColor Green 