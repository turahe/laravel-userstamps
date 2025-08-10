<?php

namespace Turahe\UserStamps\Database\Schema\Macros;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Support\Facades\Config;

/**
 * UserStamps Macro for Laravel Schema Builder
 *
 * This macro provides enhanced userstamps functionality with:
 * - Configurable column types (bigInteger, uuid, ulid, string, etc.)
 * - Custom column naming
 * - Advanced foreign key configuration
 * - SQLite compatibility
 * - Column validation
 * - Audit trail support
 */
class UserStampsMacro implements MacroInterface
{
    /**
     * Bootstrap the schema macro.
     *
     * @return void
     */
    public function register()
    {
        $this->registerUserstamps();
        $this->registerSoftUserstamps();
        $this->registerDropUserstamps();
        $this->registerDropSoftUserstamps();
        $this->registerCustomUserstamps();
        $this->registerHelperMethods();
    }

    private function registerUserstamps()
    {
        Blueprint::macro('userstamps', function ($options = []) {
            $config = $this->getUserstampsConfig('userstamps', $options);
            
            foreach (['created_by', 'updated_by'] as $columnType) {
                $this->createUserstampColumn($columnType, $config[$columnType]);
            }

            return $this;
        });
    }

    private function registerSoftUserstamps()
    {
        Blueprint::macro('softUserstamps', function ($options = []) {
            $config = $this->getUserstampsConfig('softUserstamps', $options);
            
            $this->createUserstampColumn('deleted_by', $config['deleted_by']);

            return $this;
        });
    }

    private function registerCustomUserstamps()
    {
        Blueprint::macro('customUserstamps', function ($columns = [], $options = []) {
            $config = $this->getUserstampsConfig('custom', $options);
            
            foreach ($columns as $columnName => $columnConfig) {
                $mergedConfig = array_merge($config['default'] ?? [], $columnConfig);
                $this->createUserstampColumn($columnName, $mergedConfig);
            }

            return $this;
        });
    }

    private function registerDropUserstamps()
    {
        Blueprint::macro('dropUserstamps', function ($options = []) {
            $config = $this->getUserstampsConfig('dropUserstamps', $options);
            $columns = ['created_by', 'updated_by'];
            
            $this->dropUserstampColumns($columns, $config);

            return $this;
        });
    }

    private function registerDropSoftUserstamps()
    {
        Blueprint::macro('dropSoftUserstamps', function ($options = []) {
            $config = $this->getUserstampsConfig('dropSoftUserstamps', $options);
            $columns = ['deleted_by'];
            
            $this->dropUserstampColumns($columns, $config);

            return $this;
        });
    }

    /**
     * Register helper methods on the Blueprint class
     */
    private function registerHelperMethods()
    {
        Blueprint::macro('getUserstampsConfig', function ($type, $options = []) {
            $baseConfig = config('userstamps.columns', []);
            $defaultConfig = [
                'created_by' => $baseConfig['created_by'] ?? [],
                'updated_by' => $baseConfig['updated_by'] ?? [],
                'deleted_by' => $baseConfig['deleted_by'] ?? [],
            ];
            
            // Merge with legacy configuration for backward compatibility
            $legacyConfig = [
                'created_by' => [
                    'name' => config('userstamps.created_by_column', 'created_by'),
                ],
                'updated_by' => [
                    'name' => config('userstamps.updated_by_column', 'updated_by'),
                ],
                'deleted_by' => [
                    'name' => config('userstamps.deleted_by_column', 'deleted_by'),
                ],
            ];
            
            // Merge legacy config with base config (legacy config takes precedence for column names)
            foreach ($defaultConfig as $key => $config) {
                $defaultConfig[$key] = array_merge($config, $legacyConfig[$key]);
            }
            
            // Merge with provided options
            if (!empty($options)) {
                foreach ($options as $key => $value) {
                    if (isset($defaultConfig[$key])) {
                        $defaultConfig[$key] = array_merge($defaultConfig[$key], $value);
                    }
                }
            }
            
            return $defaultConfig;
        });

        Blueprint::macro('createUserstampColumn', function ($columnType, $config) {
            // Get the actual column name from config or use default
            // Support both old format (created_by_column) and new format (columns.created_by.name)
            $columnName = $config['name'] ?? 
                         config("userstamps.{$columnType}_column") ?? 
                         config("userstamps.columns.{$columnType}.name", $columnType);
            
            // For backward compatibility, also check the legacy column names
            if ($columnType === 'created_by') {
                $columnName = $config['name'] ?? config('userstamps.created_by_column', 'created_by');
            } elseif ($columnType === 'updated_by') {
                $columnName = $config['name'] ?? config('userstamps.updated_by_column', 'updated_by');
            } elseif ($columnType === 'deleted_by') {
                $columnName = $config['name'] ?? config('userstamps.deleted_by_column', 'deleted_by');
            }
            
            // Ensure we have a valid column name
            if (empty($columnName)) {
                $columnName = $columnType;
            }
            
            $columnTypeMethod = $config['type'] ?? config('userstamps.users_table_column_type', 'bigIncrements');
            
            // Validate column name
            $this->validateUserstampColumnName($columnName);
            
            // Create the column with the specified type
            $column = $this->createUserstampColumnByType($columnName, $columnTypeMethod, $config);
            
            // Add nullable if specified
            if ($config['nullable'] ?? true) {
                $column->nullable();
            }
            
            // Add index if specified
            if ($config['index'] ?? config('userstamps.advanced.indexes', true)) {
                $column->index();
            }
            
            // Add comment if enabled and specified
            if (config('userstamps.advanced.comments', false) && isset($config['comment'])) {
                $column->comment($config['comment']);
            }
            
            // Add foreign key if specified
            if ($config['foreign_key'] ?? config('userstamps.advanced.foreign_keys', true)) {
                $this->addUserstampForeignKeyConstraint($columnName, $config);
            }
        });

        Blueprint::macro('createUserstampColumnByType', function ($columnName, $type, $config) {
            $customTypes = config('userstamps.custom_column_types', []);
            
            if (isset($customTypes[$type])) {
                $typeConfig = $customTypes[$type];
                $method = $typeConfig['method'];
                $parameters = array_merge([$columnName], $typeConfig['parameters'] ?? []);
                
                return $this->$method(...$parameters);
            }
            
            // Fallback to default type handling
            return $this->createDefaultUserstampColumn($columnName, $type);
        });

        Blueprint::macro('createDefaultUserstampColumn', function ($columnName, $type) {
            switch ($type) {
                case 'bigIncrements':
                    return $this->unsignedBigInteger($columnName);
                case 'uuid':
                    return $this->uuid($columnName);
                case 'ulid':
                    return $this->ulid($columnName);
                case 'increments':
                    return $this->unsignedInteger($columnName);
                case 'bigInteger':
                    return $this->bigInteger($columnName);
                case 'integer':
                    return $this->integer($columnName);
                case 'string':
                    return $this->string($columnName, 255);
                case 'text':
                    return $this->text($columnName);
                case 'char':
                    return $this->char($columnName, 36);
                default:
                    return $this->unsignedBigInteger($columnName);
            }
        });

        Blueprint::macro('addUserstampForeignKeyConstraint', function ($columnName, $config) {
            $onDelete = $config['on_delete'] ?? config('userstamps.advanced.default_foreign_key_behavior.on_delete', 'set null');
            $onUpdate = $config['on_update'] ?? config('userstamps.advanced.default_foreign_key_behavior.on_update', 'cascade');
            
            $this->foreign($columnName)
                ->references(config('userstamps.users_table_column_id_name', 'id'))
                ->on(config('userstamps.users_table', 'users'))
                ->onDelete($onDelete)
                ->onUpdate($onUpdate);
        });

        Blueprint::macro('dropUserstampColumns', function ($columns, $config) {
            // Check SQLite compatibility
            if (config('userstamps.advanced.sqlite_compatibility', true) && 
                DB::connection() instanceof SQLiteConnection) {
                return $this;
            }

            foreach ($columns as $columnType) {
                // Support both old format (created_by_column) and new format (columns.created_by.name)
                $columnName = config("userstamps.{$columnType}_column") ?? 
                             config("userstamps.columns.{$columnType}.name", $columnType);
                
                // For backward compatibility, also check the legacy column names
                if ($columnType === 'created_by') {
                    $columnName = config('userstamps.created_by_column', 'created_by');
                } elseif ($columnType === 'updated_by') {
                    $columnName = config('userstamps.updated_by_column', 'updated_by');
                } elseif ($columnType === 'deleted_by') {
                    $columnName = config('userstamps.deleted_by_column', 'deleted_by');
                }
                
                // Drop foreign key if enabled
                if (config('userstamps.advanced.foreign_keys', true)) {
                    try {
                        $this->dropForeign([$columnName]);
                    } catch (\Exception $e) {
                        // Foreign key might not exist, continue
                    }
                }
                
                // Drop the column (indexes will be dropped automatically)
                try {
                    $this->dropColumn($columnName);
                } catch (\Exception $e) {
                    // Column might not exist, continue
                }
            }
        });

        Blueprint::macro('validateUserstampColumnName', function ($columnName) {
            $validation = config('userstamps.advanced.column_name_validation', []);
            
            if (isset($validation['pattern'])) {
                if (!preg_match($validation['pattern'], $columnName)) {
                    throw new \InvalidArgumentException(
                        "Column name '{$columnName}' does not match the required pattern."
                    );
                }
            }
            
            if (isset($validation['max_length']) && strlen($columnName) > $validation['max_length']) {
                throw new \InvalidArgumentException(
                    "Column name '{$columnName}' exceeds maximum length of {$validation['max_length']} characters."
                );
            }
        });
    }
}
