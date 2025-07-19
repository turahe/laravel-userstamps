# Docker Setup for Laravel Userstamps Testing

This setup provides Docker containers for MySQL and PostgreSQL to test the Laravel Userstamps package across different database systems.

## Prerequisites

- Docker and Docker Compose installed
- PHP with required extensions (pdo_mysql, pdo_pgsql)

## Quick Start

1. **Start the Docker services:**
   ```bash
   docker-compose up -d
   ```

2. **Wait for databases to be ready (about 10-15 seconds)**

3. **Run tests with different databases:**

   **SQLite (default):**
   ```bash
   composer test
   ```

   **MySQL:**
   ```bash
   cp env.mysql .env
   composer test
   ```

   **PostgreSQL:**
   ```bash
   cp env.postgres .env
   composer test
   ```

   **All databases (using script):**
   ```bash
   chmod +x scripts/test-all.sh
   ./scripts/test-all.sh
   ```

   **Or use composer scripts (recommended):**
   ```bash
   composer test:all
   ```

## Services

### MySQL
- **Container:** `laravel_userstamps_mysql`
- **Port:** `3306`
- **Database:** `laravel_userstamps_test`
- **Username:** `laravel_userstamps`
- **Password:** `password`

### PostgreSQL
- **Container:** `laravel_userstamps_postgres`
- **Port:** `5432`
- **Database:** `laravel_userstamps_test`
- **Username:** `laravel_userstamps`
- **Password:** `password`

### Redis (Optional)
- **Container:** `laravel_userstamps_redis`
- **Port:** `6379`

## Database Connection Details

### MySQL
```php
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_userstamps_test
DB_USERNAME=laravel_userstamps
DB_PASSWORD=password
```

### PostgreSQL
```php
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=laravel_userstamps_test
DB_USERNAME=laravel_userstamps
DB_PASSWORD=password
```

## Useful Commands

### Start services
```bash
docker-compose up -d
```

### Stop services
```bash
docker-compose down
```

### View logs
```bash
docker-compose logs mysql
docker-compose logs postgres
```

### Access MySQL CLI
```bash
docker exec -it laravel_userstamps_mysql mysql -u laravel_userstamps -p laravel_userstamps_test
```

### Access PostgreSQL CLI
```bash
docker exec -it laravel_userstamps_postgres psql -U laravel_userstamps -d laravel_userstamps_test
```

### Reset databases
```bash
docker-compose down -v
docker-compose up -d
```

## Testing Scripts

The `scripts/` directory contains helper scripts:

- `test-mysql.sh` - Run tests with MySQL
- `test-postgres.sh` - Run tests with PostgreSQL  
- `test-all.sh` - Run tests with all databases

Make scripts executable:
```bash
chmod +x scripts/*.sh
```

## Troubleshooting

### Database connection issues
1. Ensure Docker services are running: `docker-compose ps`
2. Check if databases are ready: `docker-compose logs mysql`
3. Verify ports are not in use: `netstat -an | grep 3306`

### Permission issues
- On Windows, ensure Docker Desktop has access to the project directory
- On Linux/Mac, ensure proper file permissions

### PHP extensions
Make sure you have the required PHP extensions:
```bash
php -m | grep pdo
```

You should see:
- `pdo_mysql`
- `pdo_pgsql`
- `pdo_sqlite` 