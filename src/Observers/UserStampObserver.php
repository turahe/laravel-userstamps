<?php

namespace Turahe\UserStamps\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserStampObserver
{
    /**
     * Handle to the User "creating" event.
     *
     * @return void
     */
    public function creating(Model $model)
    {
        $model->{config('userstamps.created_by_column')} = $this->getUsersPrimaryValue();
        $model->{config('userstamps.updated_by_column')} = $this->getUsersPrimaryValue();
    }

    /**
     * Handle to the User "updating" event.
     *
     * @return void
     */
    public function updating(Model $model)
    {
        $model->{config('userstamps.updated_by_column')} = $this->getUsersPrimaryValue();
    }

    /**
     * Handle to the User "deleting" event.
     *
     * @return void
     */
    public function deleting(Model $model)
    {
        if ($model->usingSoftDeletes()) {
            $model->{config('userstamps.deleted_by_column')} = $this->getUsersPrimaryValue();
            $model->{config('userstamps.updated_by_column')} = $this->getUsersPrimaryValue();
            $this->saveWithoutEventDispatching($model);
        }
    }

    /**
     * Handle to the User "restoring" event.
     *
     * @return void
     */
    public function restoring(Model $model)
    {
        if ($model->usingSoftDeletes()) {
            $model->{config('userstamps.deleted_by_column')} = null;
            $model->{config('userstamps.updated_by_column')} = $this->getUsersPrimaryValue();
            $this->saveWithoutEventDispatching($model);
        }
    }

    /**
     * Saves a model by ignoring all other event dispatchers.
     *
     * @return bool
     */
    private function saveWithoutEventDispatching(Model $model)
    {
        $eventDispatcher = $model->getEventDispatcher();

        $model->unsetEventDispatcher();
        $saved = $model->save();
        $model->setEventDispatcher($eventDispatcher);

        return $saved;
    }

    /**
     * Returns the primary value for the logged in user or null when
     * there is no logged in user.
     *
     * @return int|null
     */
    private function getUsersPrimaryValue()
    {
        if (! Auth::check()) {
            return null;
        }

        if (config('userstamps.users_table_column_id_name') !== 'id') {
            return Auth::user()->{config('userstamps.users_table_column_id_name')};
        }

        return Auth::id();
    }
}
