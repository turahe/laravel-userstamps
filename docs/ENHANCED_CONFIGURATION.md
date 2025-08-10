# Enhanced Configuration Options for Custom Column Types

This document explains how to use the enhanced configuration options in Laravel Userstamps package to customize column types, names, and behavior.

## Table of Contents

1. [Overview](#overview)
2. [Basic Configuration](#basic-configuration)
3. [Enhanced Column Configuration](#enhanced-column-configuration)
4. [Custom Column Types](#custom-column-types)
5. [Advanced Configuration](#advanced-configuration)
6. [Migration Options](#migration-options)
7. [Usage Examples](#usage-examples)
8. [Configuration Validation](#configuration-validation)
9. [Best Practices](#best-practices)

## Overview

The enhanced configuration system allows you to:

- Customize column names and types
- Define custom column type mappings
- Control foreign key behavior
- Enable/disable features like indexes and comments
- Validate configuration automatically
- Support multiple database types

## Basic Configuration

### Standard Settings

```php
return [
    'users_table' => 'users',
    'users_table_column_type' => 'bigIncrements',
    'users_table_column_id_name' => 'id',
    'users_model' => env('AUTH_MODEL', 'App\User'),
    
    'created_by_column' => 'created_by',
    'updated_by_column' => 'updated_by',
    'deleted_by_column' => 'deleted_by',
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

## Enhanced Column Configuration

### Column Definition Structure

Each column can be configured with detailed options:

```php
'columns' => [
    'created_by' => [
        'name' => 'created_by',           // Column name in database
        'type' => null,                   // null = auto-detect
        'nullable' => true,               // Allow NULL values
        'index' => true,                  // Create index
        'foreign_key' => true,            // Create foreign key
        'on_delete' => 'set null',        // Foreign key behavior
        'on_update' => 'cascade',         // Foreign key behavior
        'comment' => 'User who created this record',
        'hidden' => false,                // Hide from JSON/array
        'cast' => null,                   // Custom cast type
        'validation' => 'nullable|exists:users,id',
    ],
],
```

### Column Options Explained

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `name` | string | column_type | Database column name |
| `type` | string|null | null | Column data type |
| `nullable` | bool | true | Allow NULL values |
| `index` | bool | true | Create database index |
| `foreign_key` | bool | true | Create foreign key constraint |
| `on_delete` | string | 'set null' | Foreign key delete behavior |
| `on_update` | string | 'cascade' | Foreign key update behavior |
| `comment` | string | null | Column comment |
| `hidden` | bool | false | Hide from model serialization |
| `cast` | string|null | null | Custom cast type |
| `validation` | string | null | Laravel validation rules |

### Foreign Key Behaviors

- `cascade` - Delete/update related records
- `set null` - Set foreign key to NULL
- `restrict` - Prevent deletion/update
- `no action` - No action (database default)

## Custom Column Types

### Defining Custom Types

```php
'custom_column_types' => [
    'user_id' => [
        'method' => 'unsignedBigInteger',
        'parameters' => [],
    ],
    'audit_user' => [
        'method' => 'string',
        'parameters' => [100],
    ],
    'tracking_id' => [
        'method' => 'char',
        'parameters' => [26], // ULID length
    ],
],
```

### Custom Type Structure

| Field | Type | Description |
|-------|------|-------------|
| `method` | string | Laravel Schema Builder method |
| `parameters` | array | Parameters for the method |

### Available Schema Methods

- `unsignedBigInteger()`
- `unsignedInteger()`
- `bigInteger()`
- `integer()`
- `string($length)`
- `text()`
- `char($length)`
- `uuid()`
- `ulid()`

## Advanced Configuration

### Feature Control

```php
'advanced' => [
    'foreign_keys' => true,           // Enable foreign key constraints
    'indexes' => true,                // Enable index creation
    'comments' => false,              // Enable column comments
    'sqlite_compatibility' => true,   // SQLite compatibility mode
],
```

### Performance Settings

```php
'performance' => [
    'batch_operations' => true,       // Enable batch operations
    'lazy_loading' => true,           // Enable lazy loading
    'cache_relationships' => false,   // Cache relationship queries
],
```

### Security Settings

```php
'security' => [
    'validate_user_existence' => true,    // Validate user exists
    'sanitize_column_names' => true,     // Sanitize column names
    'prevent_sql_injection' => true,     // Prevent SQL injection
],
```

### Column Name Validation

```php
'column_name_validation' => [
    'pattern' => '/^[a-zA-Z_][a-zA-Z0-9_]*$/',
    'max_length' => 64,
],
```

## Migration Options

### Migration Configuration

```php
'migration' => [
    'auto_generate' => false,         // Auto-generate migrations
    'naming_convention' => 'add_userstamps_to_{table}_table',
    'path' => '',                     // Migration file path
    'rollback_support' => true,       // Enable rollback
],
```

### Migration Templates

```php
'template' => [
    'include_foreign_keys' => true,   // Include foreign key constraints
    'include_indexes' => true,        // Include indexes
    'include_comments' => false,      // Include column comments
    'include_validation' => false,    // Include validation rules
],
```

## Usage Examples

### Example 1: UUID-based Userstamps

```php
return [
    'users_table_column_type' => 'uuid',
    'columns' => [
        'created_by' => [
            'name' => 'creator_id',
            'type' => 'uuid',
            'comment' => 'UUID of the user who created this record',
        ],
        'updated_by' => [
            'name' => 'modifier_id',
            'type' => 'uuid',
            'comment' => 'UUID of the user who last modified this record',
        ],
    ],
];
```

### Example 2: String-based Userstamps

```php
return [
    'columns' => [
        'created_by' => [
            'name' => 'created_by_user',
            'type' => 'string',
            'comment' => 'Username of the user who created this record',
        ],
        'updated_by' => [
            'name' => 'updated_by_user',
            'type' => 'string',
            'comment' => 'Username of the user who last updated this record',
        ],
    ],
    'custom_column_types' => [
        'username' => [
            'method' => 'string',
            'parameters' => [50],
        ],
    ],
];
```

### Example 3: Audit Trail Configuration

```php
return [
    'columns' => [
        'created_by' => [
            'name' => 'author_id',
            'type' => 'bigInteger',
            'comment' => 'ID of the user who authored this record',
        ],
        'updated_by' => [
            'name' => 'editor_id',
            'type' => 'bigInteger',
            'comment' => 'ID of the user who last edited this record',
        ],
        'deleted_by' => [
            'name' => 'remover_id',
            'type' => 'bigInteger',
            'comment' => 'ID of the user who removed this record',
        ],
    ],
    'advanced' => [
        'comments' => true,
        'performance' => [
            'batch_operations' => true,
            'lazy_loading' => false,
        ],
    ],
];
```

## Configuration Validation

### Automatic Validation

The package includes a configuration validator:

```php
use Turahe\UserStamps\Config\UserStampsConfigValidator;

// Validate configuration
$errors = UserStampsConfigValidator::validate();

if (empty($errors)) {
    echo "Configuration is valid!";
} else {
    foreach ($errors as $error) {
        echo "Error: {$error}\n";
    }
}

// Check if valid
if (UserStampsConfigValidator::isValid()) {
    echo "Configuration is valid!";
}

// Get configuration summary
$summary = UserStampsConfigValidator::getSummary();
```

### Validation Rules

The validator checks:

- Required configuration fields
- Valid column types
- Valid foreign key behaviors
- Column name patterns
- Custom type definitions
- Advanced configuration options

## Best Practices

### 1. Column Naming

- Use descriptive names (e.g., `creator_id` instead of `created_by`)
- Follow your database naming conventions
- Keep names under 64 characters
- Use only alphanumeric characters and underscores

### 2. Type Selection

- Use `bigIncrements` for most user ID columns
- Use `uuid` for distributed systems
- Use `ulid` for time-ordered IDs
- Use `string` for usernames or display names

### 3. Performance Considerations

- Enable indexes for frequently queried columns
- Use appropriate foreign key behaviors
- Consider batch operations for large datasets
- Enable lazy loading for complex relationships

### 4. Security

- Validate user existence
- Sanitize column names
- Use appropriate foreign key constraints
- Implement proper access controls

### 5. Database Compatibility

- Test with all supported database types
- Enable SQLite compatibility mode
- Use standard column types when possible
- Test foreign key behaviors

## Migration from Basic Configuration

### Step 1: Update Configuration

```php
// Old configuration
'created_by_column' => 'created_by',

// New configuration
'columns' => [
    'created_by' => [
        'name' => 'created_by',
        'type' => null, // Auto-detect
        'nullable' => true,
        'index' => true,
        'foreign_key' => true,
    ],
],
```

### Step 2: Update Models

```php
// Old usage
use Turahe\UserStamps\Concerns\HasUserStamps;

// New usage (optional)
use Turahe\UserStamps\Concerns\HasCustomUserStamps;
```

### Step 3: Test Configuration

```php
// Validate new configuration
$errors = UserStampsConfigValidator::validate();

// Run tests to ensure compatibility
php artisan test
```

## Troubleshooting

### Common Issues

1. **Column Type Not Supported**
   - Check `custom_column_types` configuration
   - Ensure method exists in Laravel Schema Builder
   - Verify parameter types

2. **Foreign Key Errors**
   - Check `on_delete` and `on_update` values
   - Ensure referenced table exists
   - Verify column types match

3. **Validation Errors**
   - Run configuration validator
   - Check column name patterns
   - Verify required fields

4. **Performance Issues**
   - Enable/disable indexes as needed
   - Adjust batch operation settings
   - Optimize relationship loading

### Getting Help

- Check the configuration examples
- Run the configuration validator
- Review Laravel Schema Builder documentation
- Test with different database types

## Conclusion

The enhanced configuration system provides powerful customization options while maintaining backward compatibility. Use these features to:

- Adapt to your specific database schema
- Optimize performance for your use case
- Implement custom business logic
- Ensure database compatibility
- Maintain code quality and security

For more information, refer to the main README and test examples in the package.
