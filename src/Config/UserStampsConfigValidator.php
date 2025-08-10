<?php

namespace Turahe\UserStamps\Config;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class UserStampsConfigValidator
{
    /**
     * Validate the userstamps configuration
     */
    public static function validate(): array
    {
        $errors = [];

        // Validate basic configuration
        $errors = array_merge($errors, self::validateBasicConfig());

        // Validate column configuration
        $errors = array_merge($errors, self::validateColumnConfig());

        // Validate custom column types
        $errors = array_merge($errors, self::validateCustomColumnTypes());

        // Validate advanced configuration
        $errors = array_merge($errors, self::validateAdvancedConfig());

        return $errors;
    }

    /**
     * Validate basic configuration
     */
    private static function validateBasicConfig(): array
    {
        $errors = [];

        $config = Config::get('userstamps', []);

        // Check if we have the new enhanced configuration structure
        if (isset($config['columns']) && is_array($config['columns'])) {
            // New enhanced configuration - validate required fields
            $requiredFields = ['users_table', 'users_table_column_type', 'users_table_column_id_name', 'users_model'];

            foreach ($requiredFields as $field) {
                if (! isset($config[$field])) {
                    $errors[] = "Missing required configuration: {$field}";
                }
            }
        } else {
            // Legacy configuration - validate old format
            $rules = [
                'users_table' => 'required|string|max:64',
                'users_table_column_type' => 'required|string|in:increments,bigIncrements,uuid,ulid,bigInteger,integer,string,text,char',
                'users_table_column_id_name' => 'required|string|max:64',
                'users_model' => 'required|string',
                'created_by_column' => 'required|string|max:64',
                'updated_by_column' => 'required|string|max:64',
                'deleted_by_column' => 'required|string|max:64',
            ];

            foreach ($rules as $key => $rule) {
                if (! isset($config[$key])) {
                    $errors[] = "Missing required configuration: {$key}";

                    continue;
                }

                $validator = Validator::make([$key => $config[$key]], [$key => $rule]);

                if ($validator->fails()) {
                    $errors[] = "Invalid configuration for {$key}: ".$validator->errors()->first($key);
                }
            }
        }

        // Always validate users_table_column_type if present (regardless of structure)
        if (isset($config['users_table_column_type'])) {
            $validTypes = ['increments', 'bigIncrements', 'uuid', 'ulid', 'bigInteger', 'integer', 'string', 'text', 'char'];
            if (! in_array($config['users_table_column_type'], $validTypes)) {
                $errors[] = "Invalid configuration for users_table_column_type: {$config['users_table_column_type']}";
            }
        }

        return $errors;
    }

    /**
     * Validate column configuration
     */
    private static function validateColumnConfig(): array
    {
        $errors = [];
        $columns = Config::get('userstamps.columns', []);

        // If no columns configuration is set, use defaults
        if (empty($columns)) {
            return $errors;
        }

        $requiredColumns = ['created_by', 'updated_by', 'deleted_by'];

        foreach ($requiredColumns as $columnType) {
            if (! isset($columns[$columnType])) {
                // Column not configured, skip validation
                continue;
            }

            $columnConfig = $columns[$columnType];
            $columnErrors = self::validateSingleColumnConfig($columnType, $columnConfig);
            $errors = array_merge($errors, $columnErrors);
        }

        return $errors;
    }

    /**
     * Validate single column configuration
     */
    private static function validateSingleColumnConfig(string $columnType, array $config): array
    {
        $errors = [];

        // Validate name field if present
        if (isset($config['name'])) {
            $name = $config['name'];

            if (! is_string($name) || empty($name)) {
                $errors[] = "Column name for {$columnType} must be a non-empty string";
            } else {
                // Get max length from advanced config or use default
                $maxLength = Config::get('userstamps.advanced.column_name_validation.max_length', 64);
                if (strlen($name) > $maxLength) {
                    $errors[] = "Column name for {$columnType} exceeds maximum length of {$maxLength} characters";
                }

                // Get pattern from advanced config or use default
                $pattern = Config::get('userstamps.advanced.column_name_validation.pattern', '/^[a-zA-Z_][a-zA-Z0-9_]*$/');
                if (! preg_match($pattern, $name)) {
                    $errors[] = "Column name for {$columnType} contains invalid characters";
                }
            }
        }

        // Validate type field if present
        if (isset($config['type'])) {
            $validTypes = ['increments', 'bigIncrements', 'uuid', 'ulid', 'bigInteger', 'integer', 'string', 'text', 'char'];

            if (! in_array($config['type'], $validTypes)) {
                $errors[] = "Invalid column type for {$columnType}: {$config['type']}. Valid types are: ".implode(', ', $validTypes);
            }
        }

        // Validate boolean fields
        $booleanFields = ['nullable', 'index', 'foreign_key'];
        foreach ($booleanFields as $field) {
            if (isset($config[$field]) && ! is_bool($config[$field])) {
                $errors[] = "Field '{$field}' for {$columnType} must be a boolean";
            }
        }

        // Validate foreign key behavior
        if (isset($config['on_delete'])) {
            $validOnDelete = ['cascade', 'set null', 'restrict', 'no action'];
            if (! in_array($config['on_delete'], $validOnDelete)) {
                $errors[] = "Invalid on_delete value for {$columnType}: {$config['on_delete']}. Valid values are: ".implode(', ', $validOnDelete);
            }
        }

        if (isset($config['on_update'])) {
            $validOnUpdate = ['cascade', 'set null', 'restrict', 'no action'];
            if (! in_array($config['on_update'], $validOnUpdate)) {
                $errors[] = "Invalid on_update value for {$columnType}: {$config['on_update']}. Valid values are: ".implode(', ', $validOnUpdate);
            }
        }

        return $errors;
    }

    /**
     * Validate custom column types
     */
    private static function validateCustomColumnTypes(): array
    {
        $errors = [];
        $customTypes = Config::get('userstamps.custom_column_types', []);

        // If no custom column types are set, use defaults
        if (empty($customTypes)) {
            return $errors;
        }

        foreach ($customTypes as $typeName => $typeConfig) {
            if (! is_array($typeConfig)) {
                $errors[] = "Custom column type '{$typeName}' must be an array";

                continue;
            }

            if (! isset($typeConfig['method'])) {
                $errors[] = "Custom column type '{$typeName}' is missing 'method' field";

                continue;
            }

            if (! is_string($typeConfig['method'])) {
                $errors[] = "Method for custom column type '{$typeName}' must be a string";

                continue;
            }

            if (! isset($typeConfig['parameters'])) {
                $errors[] = "Custom column type '{$typeName}' is missing 'parameters' field";

                continue;
            }

            if (! is_array($typeConfig['parameters'])) {
                $errors[] = "Parameters for custom column type '{$typeName}' must be an array";

                continue;
            }
        }

        return $errors;
    }

    /**
     * Validate advanced configuration
     */
    private static function validateAdvancedConfig(): array
    {
        $errors = [];
        $advanced = Config::get('userstamps.advanced', []);

        // If no advanced configuration is set, use defaults
        if (empty($advanced)) {
            return $errors;
        }

        // Validate boolean fields
        $booleanFields = ['foreign_keys', 'indexes', 'comments', 'sqlite_compatibility'];
        foreach ($booleanFields as $field) {
            if (isset($advanced[$field]) && ! is_bool($advanced[$field])) {
                $errors[] = "Advanced configuration field '{$field}' must be a boolean";
            }
        }

        // Validate foreign key behavior
        if (isset($advanced['default_foreign_key_behavior'])) {
            $behavior = $advanced['default_foreign_key_behavior'];

            if (! is_array($behavior)) {
                $errors[] = 'Default foreign key behavior must be an array';
            } else {
                if (isset($behavior['on_delete'])) {
                    $validOnDelete = ['cascade', 'set null', 'restrict', 'no action'];
                    if (! in_array($behavior['on_delete'], $validOnDelete)) {
                        $errors[] = "Invalid default on_delete value: {$behavior['on_delete']}";
                    }
                }

                if (isset($behavior['on_update'])) {
                    $validOnUpdate = ['cascade', 'set null', 'restrict', 'no action'];
                    if (! in_array($behavior['on_update'], $validOnUpdate)) {
                        $errors[] = "Invalid default on_update value: {$behavior['on_update']}";
                    }
                }
            }
        }

        // Validate column name validation
        if (isset($advanced['column_name_validation'])) {
            $validation = $advanced['column_name_validation'];

            if (! is_array($validation)) {
                $errors[] = 'Column name validation must be an array';
            } else {
                if (isset($validation['pattern']) && ! is_string($validation['pattern'])) {
                    $errors[] = 'Column name validation pattern must be a string';
                }

                if (isset($validation['max_length']) && ! is_numeric($validation['max_length'])) {
                    $errors[] = 'Column name validation max_length must be a number';
                }
            }
        }

        return $errors;
    }

    /**
     * Check if configuration is valid
     */
    public static function isValid(): bool
    {
        return empty(self::validate());
    }

    /**
     * Get configuration summary
     */
    public static function getSummary(): array
    {
        $config = Config::get('userstamps', []);

        return [
            'basic' => [
                'users_table' => $config['users_table'] ?? 'not set',
                'users_table_column_type' => $config['users_table_column_type'] ?? 'not set',
                'users_model' => $config['users_model'] ?? 'not set',
            ],
            'columns' => [
                'created_by' => $config['columns']['created_by']['name'] ?? 'not set',
                'updated_by' => $config['columns']['updated_by']['name'] ?? 'not set',
                'deleted_by' => $config['columns']['deleted_by']['name'] ?? 'not set',
            ],
            'custom_types' => array_keys($config['custom_column_types'] ?? []),
            'advanced_features' => [
                'foreign_keys' => $config['advanced']['foreign_keys'] ?? false,
                'indexes' => $config['advanced']['indexes'] ?? false,
                'comments' => $config['advanced']['comments'] ?? false,
                'sqlite_compatibility' => $config['advanced']['sqlite_compatibility'] ?? false,
            ],
        ];
    }
}
