# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.2] - 2024-12-19

### Added
- **Docker Testing Environment**: Complete Docker setup with MySQL 8.0, PostgreSQL 15, and Redis 7
- **Multi-Database Testing**: Support for testing across SQLite, MySQL, and PostgreSQL
- **Composer Scripts**: Added `test`, `test:mysql`, `test:postgres`, and `test:all` commands
- **Test Scripts**: PowerShell and shell scripts for automated testing across all databases
- **GitHub Actions CI/CD**: Comprehensive CI/CD pipeline with matrix testing
- **Security Workflows**: Automated security audits and dependency reviews
- **Dependabot Integration**: Automated dependency updates and testing
- **Code Coverage**: Integration with Codecov for test coverage reporting
- **Code Style**: Laravel Pint integration for consistent code formatting
- **Comprehensive Documentation**: Updated README, Docker setup guide, and GitHub Actions documentation

### Changed
- **PHPUnit Configuration**: Migrated to PHPUnit 12.3.2 with updated schema
- **Test Method Names**: Updated test methods to use `test_` prefix for PHPUnit 12 compatibility
- **Database Macros**: Enhanced `dropUserstamps` and `dropSoftUserstamps` with SQLite compatibility
- **Test Case**: Updated `TestCase.php` to support dynamic database connections
- **Environment Files**: Created separate environment files for MySQL and PostgreSQL testing

### Fixed
- **SQLite Limitations**: Properly handled SQLite's column dropping limitations in database macros
- **PHPUnit Deprecations**: Resolved all PHPUnit 12 deprecation warnings
- **Database Connections**: Fixed foreign key and index handling across different database types
- **Test Failures**: Resolved issues with column dropping operations on MySQL and PostgreSQL
- **GitHub Actions**: Updated deprecated actions and fixed workflow configurations

### Technical Improvements
- **Database Schema**: Enhanced table creation with proper foreign key constraints
- **Error Handling**: Improved error handling for database operations
- **Performance**: Optimized test execution with proper database connection management
- **Cross-Platform**: Added Windows PowerShell script support alongside Unix shell scripts

## [Unreleased]

### Planned Features
- Enhanced configuration options for custom column types
- Additional database driver support
- Performance optimizations for large datasets

## [Previous Versions]

### [1.0.0] - Initial Release
- Basic userstamps functionality
- Support for `created_by`, `updated_by`, and `deleted_by` columns
- Laravel 10+ compatibility
- Basic test coverage

---

## Testing Matrix

### Supported Laravel Versions
- Laravel 10.x
- Laravel 11.x  
- Laravel 12.x

### Supported PHP Versions
- PHP 8.2
- PHP 8.3
- PHP 8.4

### Supported Databases
- SQLite (in-memory for testing)
- MySQL 8.0+
- PostgreSQL 15+

### CI/CD Features
- Automated testing across all Laravel and PHP versions
- Multi-database testing
- Code coverage reporting
- Security vulnerability scanning
- Automated dependency updates
- Code style enforcement

---

## Migration Guide

### From Previous Versions
1. **PHPUnit 12**: Update your test configuration if using older PHPUnit versions
2. **Database Testing**: Use the new composer scripts for multi-database testing
3. **Docker**: Follow the `DOCKER_README.md` for local development setup

### Breaking Changes
- None in this release - all changes are backward compatible

---

## Contributors

- **Nur Wachid** - Original author and maintainer
- **AI Assistant** - Docker setup, testing improvements, and CI/CD integration

---

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
