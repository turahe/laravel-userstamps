# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.2.1] - 2024-12-19

### Added
- **Flexible User ID Types**: Added support for different user ID column types including `bigIncrements`, `ulid`, and `uuid`
- **Dynamic Schema Creation**: TestCase now dynamically creates users table with configured ID type
- **Enhanced User Model**: User model now supports UUID and ULID primary keys with proper traits
- **Configuration-Driven ID Types**: User ID type can be configured via `users_table_column_type` setting

### Technical Improvements
- **User Model Flexibility**: Added `HasUuids` and `HasUlids` traits to User model
- **Dynamic Primary Key Creation**: Database schema creation adapts to configured user ID type
- **Backward Compatibility**: Maintains full compatibility with existing bigIncrements configuration
- **Test Infrastructure**: Enhanced testing framework to support multiple user ID types

---

## [1.2.0] - 2024-12-19

### Added
- **Enhanced Configuration System**: New advanced configuration options for customizing column types, names, and behavior
- **Custom UserStamps Trait**: New `HasCustomUserStamps` trait for models requiring custom column configurations
- **Configuration Validation**: New `UserStampsConfigValidator` class for automatic configuration validation
- **Advanced Column Options**: Support for custom column types, indexes, foreign keys, comments, and validation rules
- **Multiple Column Type Support**: Added support for `increments`, `bigIncrements`, `uuid`, `ulid`, `bigInteger`, `integer`, `string`, `text`, and `char` column types
- **Comprehensive Documentation**: New documentation covering enhanced configuration, Docker setup, and advanced usage patterns
- **Docker Infrastructure**: Complete Docker setup with multi-database testing environment
- **Enhanced Testing**: New test cases for custom configurations and column types

### Changed
- **Configuration Structure**: Enhanced configuration file with new `columns` array for detailed column definitions
- **Database Macros**: Improved `userstamps()` and `softUserstamps()` macros with better column type handling
- **CI/CD Pipeline**: Updated GitHub Actions to focus on MySQL and PostgreSQL testing (removed SQLite support)
- **PHP Version Support**: Focused on PHP 8.4 and Laravel 12 for CI testing
- **Test Infrastructure**: Enhanced test environment with Docker Compose and comprehensive database testing

### Technical Improvements
- **Column Type Detection**: Automatic column type detection based on user model configuration
- **Foreign Key Management**: Enhanced foreign key constraint handling with configurable behaviors
- **Index Management**: Configurable index creation for userstamp columns
- **Validation Integration**: Built-in validation rules for userstamp columns
- **Performance Optimization**: Improved database schema generation and column creation

---

## [1.1.3] - 2024-12-19

### Fixed
- **Custom Column Names**: Fixed issue where custom column names (e.g., `custom_created_by`) were not being properly applied in database schema macros
- **Configuration Precedence**: Resolved configuration merging issue that prevented legacy column name settings from taking precedence
- **Test Failures**: Fixed 3 failing tests related to custom column name functionality across MySQL and PostgreSQL

### Technical Improvements
- **UserStampsMacro**: Enhanced column name resolution to properly handle both legacy and new configuration formats
- **Configuration Merging**: Improved configuration precedence to ensure custom column names work correctly
- **Backward Compatibility**: Maintained full backward compatibility while fixing custom column name functionality

---

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
