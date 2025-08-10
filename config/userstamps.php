<?php

return [

    /*
     * Define the table which is used in the database to retrieve the users
     */

    'users_table' => 'users',

    /*
     * Define the table column type which is used in the table schema for
     * the id of the user
     *
     * Options: increments, bigIncrements, uuid, ulid
     * Default: bigIncrements
     */

    'users_table_column_type' => 'bigIncrements',

    /*
     * Define the name of the column which is used in the foreign key reference
     * to the id of the user
     */

    'users_table_column_id_name' => 'id',

    /*
     * Define the model which is used for the relationships on your models
     */

    'users_model' => env('AUTH_MODEL', 'App\User'),

    /*
     * Define the column which is used in the database to save the user's id
     * which created the model.
     */

    'created_by_column' => 'created_by',

    /*
     * Define the column which is used in the database to save the user's id
     * which updated the model.
     */

    'updated_by_column' => 'updated_by',

    /*
     * Define the column which is used in the database to save the user's id
     * which deleted the model.
     */

    'deleted_by_column' => 'deleted_by',

    /*
     * Enhanced column configuration options
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
        ],
    ],

    /*
     * Custom column type mappings
     * You can define custom column types here for specific use cases
     */

    'custom_column_types' => [
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
    ],

    /*
     * Advanced configuration options
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
    ],

    /*
     * Migration options
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
    ],

];
