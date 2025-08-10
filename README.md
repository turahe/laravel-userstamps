# Laravel Userstamps

[![CI](https://github.com/turahe/laravel-userstamps/actions/workflows/php.yml/badge.svg)](https://github.com/turahe/laravel-userstamps/actions/workflows/php.yml)
[![Security](https://github.com/turahe/laravel-userstamps/actions/workflows/security.yml/badge.svg)](https://github.com/turahe/laravel-userstamps/actions/workflows/security.yml)
[![codecov](https://codecov.io/gh/turahe/laravel-userstamps/branch/master/graph/badge.svg)](https://codecov.io/gh/turahe/laravel-userstamps)
[![Latest Stable Version](https://poser.pugx.org/turahe/laravel-userstamps/v/stable)](https://packagist.org/packages/turahe/laravel-userstamps)
[![Total Downloads](https://poser.pugx.org/turahe/laravel-userstamps/downloads)](https://packagist.org/packages/turahe/laravel-userstamps)
[![Monthly Downloads](https://poser.pugx.org/turahe/laravel-userstamps/d/monthly)](https://packagist.org/packages/turahe/laravel-userstamps)
[![Daily Downloads](https://poser.pugx.org/turahe/laravel-userstamps/d/daily)](https://packagist.org/packages/turahe/laravel-userstamps)
[![License](https://poser.pugx.org/turahe/laravel-userstamps/license)](LICENSE)
[![PHP Version](https://img.shields.io/badge/php-8.2%2B-blue.svg)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/laravel-10%2B%20%7C%2011%2B%20%7C%2012%2B-red.svg)](https://laravel.com)
[![StyleCI](https://github.styleci.io/repos/CHANGEME/badge)](https://styleci.io/repos/CHANGEME)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/turahe/laravel-userstamps/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/turahe/laravel-userstamps/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/turahe/laravel-userstamps/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/turahe/laravel-userstamps/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/turahe/laravel-userstamps/badges/build.png?b=master)](https://scrutinizer-ci.com/g/turahe/laravel-userstamps/build-status/master)
[![Dependabot Status](https://api.dependabot.com/badges/status?host=github&repo=turahe/laravel-userstamps)](https://dependabot.com)
[![Maintenance](https://img.shields.io/badge/Maintained%3F-yes-green.svg)](https://github.com/turahe/laravel-userstamps/graphs/commit-activity)
[![Made with Love](https://img.shields.io/badge/Made%20with-Love-red.svg)](https://github.com/turahe/laravel-userstamps)

A Laravel package to automatically add `created_by`, `updated_by`, and `deleted_by` fields to your Eloquent models, with enhanced configuration options, custom column types, and comprehensive testing infrastructure.

---

## Features

- **Core Functionality**: Automatically tracks which user created, updated, or deleted a model
- **Enhanced Configuration**: Advanced options for customizing column types, names, and behavior
- **Custom UserStamps**: Support for custom column configurations and advanced use cases
- **Multi-Database Support**: Works with MySQL, PostgreSQL, and SQLite
- **Laravel Compatibility**: Supports Laravel 10, 11, and 12
- **Database Schema Macros**: Easy-to-use schema macros for userstamps
- **Comprehensive Testing**: GitHub Actions CI/CD with multi-database testing
- **Docker Integration**: Complete Docker setup for local development and testing
- **Code Quality**: Laravel Pint integration and security scanning

---

## Installation

```bash
composer require turahe/laravel-userstamps
```

---

## Usage

### 1. Basic Usage with HasUserStamps

```php
use Turahe\UserStamps\Concerns\HasUserStamps;

class Post extends Model
{
    use HasUserStamps;
}
```

### 2. Advanced Usage with HasCustomUserStamps

```php
use Turahe\UserStamps\Concerns\HasCustomUserStamps;

class Post extends Model
{
    use HasCustomUserStamps;
    
    protected $userstampsConfig = [
        'columns' => [
            'created_by' => [
                'type' => 'uuid',
                'index' => true,
                'comment' => 'User who created this post'
            ],
            'updated_by' => [
                'type' => 'uuid',
                'nullable' => false
            ]
        ]
    ];
}
```

### 3. Database Schema

Add the userstamps columns using the provided schema macros:

```php
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->userstamps();      // Adds created_by and updated_by
    $table->softUserstamps();  // Adds deleted_by
    $table->timestamps();
    $table->softDeletes();
});
```

---

## Configuration

### Publish Configuration

```bash
php artisan vendor:publish --provider="Turahe\UserStamps\UserStampsServiceProvider" --tag="config"
```

### Enhanced Configuration Options

The package now supports advanced configuration for customizing column behavior:

```php
return [
    'users_table' => 'users',
    'users_table_column_type' => 'bigIncrements',
    'users_table_column_id_name' => 'id',
    'users_model' => env('AUTH_MODEL', 'App\User'),
    
    // Legacy column names (backward compatible)
    'created_by_column' => 'created_by',
    'updated_by_column' => 'updated_by',
    'deleted_by_column' => 'deleted_by',
    
    // Enhanced column configuration
    'columns' => [
        'created_by' => [
            'name' => 'created_by',
            'type' => null,                   // Auto-detect
            'nullable' => true,
            'index' => true,
            'foreign_key' => true,
            'on_delete' => 'set null',
            'comment' => 'User who created this record'
        ],
        'updated_by' => [
            'name' => 'updated_by',
            'type' => null,
            'nullable' => true,
            'index' => true,
            'foreign_key' => true,
            'on_delete' => 'set null',
            'comment' => 'User who last updated this record'
        ],
        'deleted_by' => [
            'name' => 'deleted_by',
            'type' => null,
            'nullable' => true,
            'index' => true,
            'foreign_key' => true,
            'on_delete' => 'set null',
            'comment' => 'User who deleted this record'
        ]
    ]
];
```

### Supported Column Types

- `increments` - Auto-incrementing integer
- `bigIncrements` - Auto-incrementing big integer (default)
- `uuid` - UUID string
- `ulid` - ULID string
- `bigInteger` - Big integer
- `integer` - Regular integer
- `string` - Variable-length string
- `text` - Long text
- `char` - Fixed-length string

---

## Testing

### Local Testing with Docker

**Requirements:** Docker, PHP 8.2+, Composer

```bash
# Start database containers
docker-compose up -d

# Run all tests (MySQL, PostgreSQL)
composer test:all

# Or test with a specific database
composer test:mysql
composer test:postgres
```

### Test Scripts

The package includes PowerShell and shell scripts for automated testing:

- **Windows**: `scripts/test-all.ps1`
- **Linux/macOS**: `scripts/test-all.sh`

### GitHub Actions CI/CD

- Matrix tests for Laravel 12 and PHP 8.4
- Multi-database testing (MySQL and PostgreSQL)
- Code style checking with Laravel Pint
- Security scanning with Composer Audit and CodeQL

---

## Documentation

For detailed information on advanced features:

- **[Enhanced Configuration](docs/ENHANCED_CONFIGURATION.md)** - Advanced configuration options and custom column types
- **[Docker Setup](docs/DOCKER_README.md)** - Complete Docker environment setup
- **[Docker Setup Summary](docs/DOCKER_SETUP_SUMMARY.md)** - Quick Docker setup guide

---

## Docker Setup

The package includes a complete Docker testing environment:

- **MySQL 8.0**: `127.0.0.1:3306`, user: `laravel_userstamps`, pass: `password`
- **PostgreSQL 15**: `127.0.0.1:5432`, user: `laravel_userstamps`, pass: `password`
- **Redis 7**: `127.0.0.1:6379`

See [DOCKER_README.md](docs/DOCKER_README.md) for full setup instructions.

---

## Security

- Composer audit and CodeQL scanning in CI
- Dependabot for automated dependency updates
- Regular security vulnerability scanning

---

## Contributing

1. Fork the repo
2. Create your feature branch (`git checkout -b feature/foo`)
3. Commit your changes
4. Push to the branch (`git push origin feature/foo`)
5. Open a pull request

---

## License

MIT © [Nur Wachid](https://www.wach.id)
