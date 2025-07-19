# GitHub Actions CI/CD Setup

This repository includes comprehensive GitHub Actions workflows for testing, security, and automated dependency management.

## 🚀 Workflows Overview

### 1. **PHP Tests** (`php.yml`)
**Main testing workflow with matrix strategy**

**Matrix Configuration:**
- **Laravel Versions**: 10.*, 11.*, 12.*
- **PHP Versions**: 8.2, 8.3, 8.4
- **Databases**: SQLite, MySQL, PostgreSQL

**Excluded Combinations:**
- Laravel 10.* + PHP 8.4 (incompatible)
- Laravel 11.* + PHP 8.2 (incompatible)

**Features:**
- ✅ Matrix testing across all compatible combinations
- ✅ Database services (MySQL, PostgreSQL) with health checks
- ✅ Composer caching for faster builds
- ✅ Test result artifacts
- ✅ Code coverage reporting
- ✅ Code style checking with Laravel Pint

### 2. **Security Checks** (`security.yml`)
**Security and vulnerability scanning**

**Features:**
- ✅ Composer security audit
- ✅ Dependency vulnerability scanning
- ✅ Weekly scheduled security checks
- ✅ SARIF file upload for GitHub Security tab
- ✅ Dependency review for pull requests

### 3. **Dependabot Auto-merge** (`dependabot.yml`)
**Automated dependency management**

**Features:**
- ✅ Automatic testing of Dependabot PRs
- ✅ Auto-merge for patch updates
- ✅ Manual review required for major updates
- ✅ Matrix testing for dependency updates

## 📊 Matrix Testing Strategy

The main workflow tests your package across:

| Laravel Version | PHP 8.2 | PHP 8.3 | PHP 8.4 |
|----------------|---------|---------|---------|
| 10.*           | ✅      | ✅      | ❌      |
| 11.*           | ❌      | ✅      | ✅      |
| 12.*           | ✅      | ✅      | ✅      |

**Total Test Combinations**: 7 matrix jobs + 2 additional jobs (coverage + lint)

## 🗄️ Database Testing

### SQLite (Default)
- In-memory database for fast testing
- No additional setup required

### MySQL 8.0
- Service container with health checks
- Connection details:
  - Host: `127.0.0.1:3306`
  - Database: `laravel_userstamps_test`
  - User: `laravel_userstamps`
  - Password: `password`

### PostgreSQL 15
- Service container with health checks
- Connection details:
  - Host: `127.0.0.1:5432`
  - Database: `laravel_userstamps_test`
  - User: `laravel_userstamps`
  - Password: `password`

## 🔧 Workflow Features

### Caching
- **Composer packages**: Cached per PHP version and composer.lock hash
- **Faster builds**: Reduces CI time significantly

### Health Checks
- **Database services**: Wait for services to be ready before testing
- **Timeout handling**: 60-second timeout with retry logic

### Artifacts
- **Test results**: Uploaded for failed builds
- **Coverage reports**: Available for successful builds
- **Retention**: 7 days for test artifacts

### Code Quality
- **Laravel Pint**: Code style checking
- **PHPUnit**: Comprehensive test suite
- **Coverage**: Xdebug coverage reporting

## 🛡️ Security Features

### Automated Security Scanning
- **Composer Audit**: Weekly vulnerability scanning
- **Dependency Review**: PR-based security checks
- **SARIF Integration**: GitHub Security tab integration

### Dependabot Configuration
- **Weekly updates**: Monday at 9:00 AM
- **Auto-merge**: Patch updates only
- **Manual review**: Major version updates
- **Smart filtering**: Ignore breaking changes

## 📈 Coverage Reporting

### Codecov Integration
- **Coverage file**: `coverage.xml`
- **Flags**: `unittests`
- **Upload**: Automatic on successful builds

### Coverage Job
- **PHP Version**: 8.4
- **Laravel Version**: 12.*
- **Database**: SQLite (fastest for coverage)

## 🚦 Workflow Triggers

### Automatic Triggers
- **Push**: Any push to master/main
- **Pull Request**: Any PR to master/main
- **Schedule**: Weekly security checks (Sundays)

### Manual Triggers
- **Workflow Dispatch**: Available for all workflows
- **Repository Dispatch**: For external integrations

## 📋 Job Summary

| Job | Description | Runs On |
|-----|-------------|---------|
| `test` | Matrix testing across Laravel/PHP/DB | Ubuntu 24.04 |
| `test-coverage` | Code coverage reporting | Ubuntu 24.04 |
| `lint` | Code style checking | Ubuntu 24.04 |
| `security` | Security audit | Ubuntu 24.04 |
| `dependency-review` | PR dependency review | Ubuntu 24.04 |
| `dependabot` | Dependabot auto-merge | Ubuntu 24.04 |
| `test-dependabot` | Dependabot PR testing | Ubuntu 24.04 |

## 🔍 Monitoring

### GitHub Actions Dashboard
- **Matrix visualization**: See all test combinations
- **Artifact downloads**: Access test results and coverage
- **Security alerts**: View vulnerability reports

### Notifications
- **Email**: Configure in repository settings
- **Slack**: Webhook integration available
- **Discord**: Webhook integration available

## 🛠️ Local Development

### Running Tests Locally
```bash
# Test with all databases (Docker required)
composer test:all

# Test with specific database
composer test:mysql
composer test:postgres

# Test with default (SQLite)
composer test
```

### Code Style
```bash
# Check code style
vendor/bin/pint --test

# Fix code style
vendor/bin/pint
```

## 📝 Configuration Files

- `.github/workflows/php.yml` - Main testing workflow
- `.github/workflows/security.yml` - Security checks
- `.github/workflows/dependabot.yml` - Dependabot automation
- `.github/dependabot.yml` - Dependabot configuration

## 🎯 Best Practices

1. **Matrix Testing**: Ensures compatibility across all supported versions
2. **Database Testing**: Validates functionality across different databases
3. **Security First**: Automated vulnerability scanning
4. **Dependency Management**: Automated updates with safety checks
5. **Code Quality**: Automated style checking and coverage reporting
6. **Fast Feedback**: Caching and optimized workflows

## 🚀 Getting Started

1. **Push to master/main**: Triggers all workflows automatically
2. **Create a PR**: Triggers testing and security checks
3. **Monitor results**: Check GitHub Actions tab for results
4. **Review security**: Check Security tab for vulnerabilities
5. **Manage dependencies**: Review Dependabot PRs weekly

Your Laravel Userstamps package is now fully tested and secured with comprehensive CI/CD! 🎉 