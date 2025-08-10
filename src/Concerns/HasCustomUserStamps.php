<?php

namespace Turahe\UserStamps\Concerns;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Config;

trait HasCustomUserStamps
{
    use HasUserStamps;

    /**
     * Get the custom userstamp columns configuration
     */
    protected function getUserstampColumns(): array
    {
        return Config::get('userstamps.columns', [
            'created_by' => 'created_by',
            'updated_by' => 'updated_by',
            'deleted_by' => 'deleted_by',
        ]);
    }

    /**
     * Get the custom userstamp column names
     */
    protected function getUserstampColumnNames(): array
    {
        $columns = $this->getUserstampColumns();

        return [
            'created_by' => $columns['created_by']['name'] ?? 'created_by',
            'updated_by' => $columns['updated_by']['name'] ?? 'updated_by',
            'deleted_by' => $columns['deleted_by']['name'] ?? 'deleted_by',
        ];
    }

    /**
     * Get the custom userstamp column types
     */
    protected function getUserstampColumnTypes(): array
    {
        $columns = $this->getUserstampColumns();

        return [
            'created_by' => $columns['created_by']['type'] ?? Config::get('userstamps.users_table_column_type', 'bigIncrements'),
            'updated_by' => $columns['updated_by']['type'] ?? Config::get('userstamps.users_table_column_type', 'bigIncrements'),
            'deleted_by' => $columns['deleted_by']['type'] ?? Config::get('userstamps.users_table_column_type', 'bigIncrements'),
        ];
    }

    /**
     * Get the custom userstamp column options
     */
    protected function getUserstampColumnOptions(): array
    {
        $columns = $this->getUserstampColumns();

        return [
            'created_by' => $columns['created_by'] ?? [],
            'updated_by' => $columns['updated_by'] ?? [],
            'deleted_by' => $columns['deleted_by'] ?? [],
        ];
    }

    /**
     * Get the user that created the model with custom column support
     *
     * @return BelongsTo
     */
    public function author()
    {
        $columnNames = $this->getUserstampColumnNames();

        return $this->belongsTo(
            Config::get('userstamps.users_model', 'App\User'),
            $columnNames['created_by'],
            Config::get('userstamps.users_table_column_id_name', 'id')
        );
    }

    /**
     * Get the user that edited the model with custom column support
     *
     * @return BelongsTo
     */
    public function editor()
    {
        $columnNames = $this->getUserstampColumnNames();

        return $this->belongsTo(
            Config::get('userstamps.users_model', 'App\User'),
            $columnNames['updated_by'],
            Config::get('userstamps.users_table_column_id_name', 'id')
        );
    }

    /**
     * Get the user that deleted the model with custom column support
     *
     * @return BelongsTo
     */
    public function destroyer()
    {
        $columnNames = $this->getUserstampColumnNames();

        return $this->belongsTo(
            Config::get('userstamps.users_model', 'App\User'),
            $columnNames['deleted_by'],
            Config::get('userstamps.users_table_column_id_name', 'id')
        );
    }

    /**
     * Get a custom userstamp relationship by column name
     *
     * @param  string  $columnName
     * @return BelongsTo
     */
    public function userstamp($columnName)
    {
        $columns = $this->getUserstampColumns();

        if (! isset($columns[$columnName])) {
            throw new \InvalidArgumentException("Userstamp column '{$columnName}' is not configured.");
        }

        $columnConfig = $columns[$columnName];
        $actualColumnName = $columnConfig['name'] ?? $columnName;

        return $this->belongsTo(
            Config::get('userstamps.users_model', 'App\User'),
            $actualColumnName,
            Config::get('userstamps.users_table_column_id_name', 'id')
        );
    }

    /**
     * Get all userstamp relationships
     */
    public function getAllUserstamps(): array
    {
        $columns = $this->getUserstampColumns();
        $relationships = [];

        foreach ($columns as $columnType => $columnConfig) {
            $columnName = $columnConfig['name'] ?? $columnType;
            $relationships[$columnType] = $this->userstamp($columnType);
        }

        return $relationships;
    }

    /**
     * Check if a specific userstamp column exists
     *
     * @param  string  $columnName
     */
    public function hasUserstampColumn($columnName): bool
    {
        $columns = $this->getUserstampColumns();

        return isset($columns[$columnName]);
    }

    /**
     * Get the fillable userstamp columns
     */
    public function getUserstampFillableColumns(): array
    {
        $columns = $this->getUserstampColumns();
        $fillable = [];

        foreach ($columns as $columnType => $columnConfig) {
            $columnName = $columnConfig['name'] ?? $columnType;
            $fillable[] = $columnName;
        }

        return $fillable;
    }

    /**
     * Get the hidden userstamp columns
     */
    public function getUserstampHiddenColumns(): array
    {
        $columns = $this->getUserstampColumns();
        $hidden = [];

        foreach ($columns as $columnType => $columnConfig) {
            if (($columnConfig['hidden'] ?? false) === true) {
                $columnName = $columnConfig['name'] ?? $columnType;
                $hidden[] = $columnName;
            }
        }

        return $hidden;
    }

    /**
     * Get the castable userstamp columns
     */
    public function getUserstampCastableColumns(): array
    {
        $columns = $this->getUserstampColumns();
        $casts = [];

        foreach ($columns as $columnType => $columnConfig) {
            if (isset($columnConfig['cast'])) {
                $columnName = $columnConfig['name'] ?? $columnType;
                $casts[$columnName] = $columnConfig['cast'];
            }
        }

        return $casts;
    }

    /**
     * Get the validation rules for userstamp columns
     */
    public function getUserstampValidationRules(): array
    {
        $columns = $this->getUserstampColumns();
        $rules = [];

        foreach ($columns as $columnType => $columnConfig) {
            if (isset($columnConfig['validation'])) {
                $columnName = $columnConfig['name'] ?? $columnType;
                $rules[$columnName] = $columnConfig['validation'];
            }
        }

        return $rules;
    }

    /**
     * Check if userstamps are enabled for this model
     */
    public function isUserstampsEnabled(): bool
    {
        return Config::get('userstamps.enabled', true);
    }

    /**
     * Enable userstamps for this model
     */
    public function enableUserstamps(): void
    {
        Config::set('userstamps.enabled', true);
    }

    /**
     * Disable userstamps for this model
     */
    public function disableUserstamps(): void
    {
        Config::set('userstamps.enabled', false);
    }
}
