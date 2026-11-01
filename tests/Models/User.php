<?php

namespace Turahe\UserStamps\Tests\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class User extends Authenticatable
{
    use HasUuids, HasUlids {
        HasUuids::newUniqueId insteadof HasUlids;
        HasUuids::newUniqueId as newUuid;
        HasUlids::newUniqueId as newUlid;
        HasUuids::isValidUniqueId insteadof HasUlids;
        HasUuids::isValidUniqueId as isValidUuid;
        HasUlids::isValidUniqueId as isValidUlid;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',
    ];

    /**
     * Generate an ID using the configured user ID type.
     *
     * @return string
     */
    public function newUniqueId()
    {
        return $this->usesUlids() ? $this->newUlid() : $this->newUuid();
    }

    /**
     * Determine whether a unique ID matches the configured user ID type.
     *
     * @param  mixed  $value
     * @return bool
     */
    protected function isValidUniqueId($value): bool
    {
        return $this->usesUlids() ? $this->isValidUlid($value) : $this->isValidUuid($value);
    }

    /**
     * Determine whether the configured user ID type is ULID.
     *
     * @return bool
     */
    private function usesUlids()
    {
        return config('userstamps.users_table_column_type', 'bigIncrements') === 'ulid';
    }

    /**
     * Get the columns that should receive a generated unique identifier.
     *
     * @return array
     */
    public function uniqueIds()
    {
        return in_array(config('userstamps.users_table_column_type', 'bigIncrements'), ['uuid', 'ulid'], true)
            ? [$this->getKeyName()]
            : [];
    }

    /**
     * Get the auto-incrementing key type.
     *
     * @return string
     */
    public function getKeyType()
    {
        return config('userstamps.users_table_column_type', 'bigIncrements') === 'bigIncrements' ? 'int' : 'string';
    }

    /**
     * Get the auto-incrementing key type.
     *
     * @return bool
     */
    public function getIncrementing()
    {
        return in_array(config('userstamps.users_table_column_type', 'bigIncrements'), ['increments', 'bigIncrements']);
    }

    /**
     * Get the primary key type.
     *
     * @return string
     */
    public function getKeyName()
    {
        return config('userstamps.users_table_column_id_name', 'id');
    }
}
