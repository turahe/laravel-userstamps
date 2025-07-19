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

A Laravel package to automatically add `created_by`, `updated_by`, and `deleted_by` fields to your Eloquent models, supporting multi-database testing and modern CI/CD.

---

## Features

- Automatically tracks which user created, updated, or deleted a model
- Works with Laravel 10, 11, and 12
- Supports MySQL, PostgreSQL, and SQLite
- Easy integration with Eloquent models
- Database schema macros for userstamps
- Thoroughly tested with GitHub Actions matrix and Docker
- Code style and security checks included

---

## Installation

```bash
composer require turahe/laravel-userstamps
```

---

## Usage

### 1. Add the Trait

```php
use Turahe\UserStamps\Concerns\HasUserStamps;

class Post extends Model
{
    use HasUserStamps;
}
```

### 2. Migrate Your Table

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

You can publish and customize the config:

```bash
php artisan vendor:publish --provider="Turahe\UserStamps\UserStampsServiceProvider" --tag="config"
```

---

## Testing

### Local Testing

**Requirements:** Docker, PHP 8.2+, Composer

```bash
# Start database containers
docker-compose up -d

# Run all tests (SQLite, MySQL, PostgreSQL)
composer test:all

# Or test with a specific database
composer test:mysql
composer test:postgres
composer test        # SQLite (default)
```

### GitHub Actions

- Matrix tests for Laravel 10/11/12, PHP 8.2/8.3/8.4, and all supported databases
- Code style checked with Laravel Pint
- Security scanning with Composer Audit and CodeQL

---

## Docker Setup

See [DOCKER_README.md](DOCKER_README.md) for full details.

- MySQL: `127.0.0.1:3306`, user: `laravel_userstamps`, pass: `password`
- PostgreSQL: `127.0.0.1:5432`, user: `laravel_userstamps`, pass: `password`

---

## Security

- Composer audit and CodeQL scanning in CI
- Dependabot for automated dependency updates

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
