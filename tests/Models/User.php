<?php

namespace Turahe\UserStamps\Tests\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class User extends Authenticatable
{
    use HasUuids, HasUlids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',
    ];

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
