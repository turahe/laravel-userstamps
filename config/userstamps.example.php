<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Basic Configuration
    |--------------------------------------------------------------------------
    |
    | Basic settings for the userstamps package
    |
    */

    'users_table' => 'users',
    'users_table_column_type' => 'bigIncrements',
    'users_table_column_id_name' => 'id',
    'users_model' => env('AUTH_MODEL', 'App\User'),

    /*
    |--------------------------------------------------------------------------
    | Column Names
    |--------------------------------------------------------------------------
    |
    | Define the names of the userstamp columns
    |
    */

    'created_by_column' => 'created_by',
    'updated_by_column' => 'updated_by',
    'deleted_by_column' => 'deleted_by',

    /*
    |--------------------------------------------------------------------------
    | Enhanced Column Configuration
    |--------------------------------------------------------------------------
    |
    | Advanced configuration for each userstamp column
    |
    */

    'columns' => [
        'created_by' => [
            'name' => 'created_by',
            'type' => null, // null = auto-detect from users_table_column_type
            'nullable' => true,
            'index' => true,
            'foreign_key' => true,
            'on_delete' => 'set null',
            'on_update' => 'cascade',
            'comment' => 'User who created this record',
            'hidden' => false,
            'cast' => null, // Custom cast type if needed
            'validation' => 'nullable|exists:users,id', // Laravel validation rules
        ],
        'updated_by' => [
            'name' => 'updated_by',
            'type' => null, // null = auto-detect from users_table_column_type
            'nullable' => true,
            'index' => true,
            'foreign_key' => true,
            'on_delete' => 'set null',
            'on_update' => 'cascade',
            'comment' => 'User who last updated this record',
            'hidden' => false,
            'cast' => null,
            'validation' => 'nullable|exists:users,id',
        ],
        'deleted_by' => [
            'name' => 'deleted_by',
            'type' => null, // null = auto-detect from users_table_column_type
            'nullable' => true,
            'index' => true,
            'foreign_key' => true,
            'on_delete' => 'set null',
            'on_update' => 'cascade',
            'comment' => 'User who deleted this record',
            'hidden' => false,
            'cast' => null,
            'validation' => 'nullable|exists:users,id',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Column Type Mappings
    |--------------------------------------------------------------------------
    |
    | Define custom column types for specific use cases
    |
    */

    'custom_column_types' => [
        // Standard Laravel column types
        'ulid' => [
            'method' => 'ulid',
            'parameters' => [],
        ],
        'uuid' => [
            'method' => 'uuid',
            'parameters' => [],
        ],
        'bigIncrements' => [
            'method' => 'unsignedBigInteger',
            'parameters' => [],
        ],
        'increments' => [
            'method' => 'unsignedInteger',
            'parameters' => [],
        ],
        'bigInteger' => [
            'method' => 'bigInteger',
            'parameters' => [],
        ],
        'integer' => [
            'method' => 'integer',
            'parameters' => [],
        ],
        'string' => [
            'method' => 'string',
            'parameters' => [255],
        ],
        'text' => [
            'method' => 'text',
            'parameters' => [],
        ],
        'char' => [
            'method' => 'char',
            'parameters' => [36],
        ],

        // Custom column types for specific use cases
        'user_id' => [
            'method' => 'unsignedBigInteger',
            'parameters' => [],
        ],
        'modifier_id' => [
            'method' => 'unsignedBigInteger',
            'parameters' => [],
        ],
        'deleter_id' => [
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

    /*
    |--------------------------------------------------------------------------
    | Advanced Configuration
    |--------------------------------------------------------------------------
    |
    | Advanced features and behavior settings
    |
    */

    'advanced' => [
        /*
         * Enable/disable automatic foreign key constraints
         */
        'foreign_keys' => true,

        /*
         * Enable/disable automatic index creation
         */
        'indexes' => true,

        /*
         * Enable/disable automatic comments on columns
         */
        'comments' => false,

        /*
         * Default foreign key behavior
         */
        'default_foreign_key_behavior' => [
            'on_delete' => 'set null',
            'on_update' => 'cascade',
        ],

        /*
         * Enable/disable SQLite compatibility mode
         * When enabled, certain operations will be skipped for SQLite
         */
        'sqlite_compatibility' => true,

        /*
         * Custom validation rules for column names
         */
        'column_name_validation' => [
            'pattern' => '/^[a-zA-Z_][a-zA-Z0-9_]*$/',
            'max_length' => 64,
        ],

        /*
         * Performance optimization settings
         */
        'performance' => [
            'batch_operations' => true,
            'lazy_loading' => true,
            'cache_relationships' => false,
        ],

        /*
         * Security settings
         */
        'security' => [
            'validate_user_existence' => true,
            'sanitize_column_names' => true,
            'prevent_sql_injection' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Options
    |--------------------------------------------------------------------------
    |
    | Settings for migration generation and management
    |
    */

    'migration' => [
        /*
         * Enable/disable automatic migration generation
         */
        'auto_generate' => false,

        /*
         * Default migration file naming convention
         */
        'naming_convention' => 'add_userstamps_to_{table}_table',

        /*
         * Migration file path (relative to database/migrations)
         */
        'path' => '',

        /*
         * Enable/disable rollback support
         */
        'rollback_support' => true,

        /*
         * Migration template customization
         */
        'template' => [
            'include_foreign_keys' => true,
            'include_indexes' => true,
            'include_comments' => false,
            'include_validation' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Example Custom Configurations
    |--------------------------------------------------------------------------
    |
    | Examples of different configuration scenarios
    |
    */

    'examples' => [
        /*
         * Example 1: UUID-based userstamps
         */
        'uuid_example' => [
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
        ],

        /*
         * Example 2: String-based userstamps (for usernames)
         */
        'string_example' => [
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
        ],

        /*
         * Example 3: Audit trail with multiple userstamps
         */
        'audit_example' => [
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
        ],
    ],

];
