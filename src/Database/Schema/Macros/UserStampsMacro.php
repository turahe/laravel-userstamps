<?php

namespace Turahe\UserStamps\Database\Schema\Macros;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Support\Facades\DB;

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
    }

    private function registerUserstamps()
    {
        Blueprint::macro('userstamps', function () {
            if (config('userstamps.users_table_column_type') === 'bigIncrements') {
                $this->unsignedBigInteger(config('userstamps.created_by_column'))->index()->nullable();
                $this->unsignedBigInteger(config('userstamps.updated_by_column'))->index()->nullable();
            } elseif (config('userstamps.users_table_column_type') === 'uuid') {
                $this->uuid(config('userstamps.created_by_column'))->index()->nullable();
                $this->uuid(config('userstamps.updated_by_column'))->index()->nullable();
            } elseif (config('userstamps.users_table_column_type') === 'ulid') {
                $this->ulid(config('userstamps.created_by_column'))->index()->nullable();
                $this->ulid(config('userstamps.updated_by_column'))->index()->nullable();
            } else {
                $this->unsignedInteger(config('userstamps.created_by_column'))->index()->nullable();
                $this->unsignedInteger(config('userstamps.updated_by_column'))->index()->nullable();
            }

            $this->foreign(config('userstamps.created_by_column'))
                ->references(config('userstamps.users_table_column_id_name'))
                ->on(config('userstamps.users_table'))
                ->onDelete('set null');

            $this->foreign(config('userstamps.updated_by_column'))
                ->references(config('userstamps.users_table_column_id_name'))
                ->on(config('userstamps.users_table'))
                ->onDelete('set null');

            return $this;
        });
    }

    private function registerSoftUserstamps()
    {
        Blueprint::macro('softUserstamps', function () {
            if (config('userstamps.users_table_column_type') === 'bigIncrements') {
                $this->unsignedBigInteger(config('userstamps.deleted_by_column'))->index()->nullable();
            } elseif (config('userstamps.users_table_column_type') === 'uuid') {
                $this->uuid(config('userstamps.deleted_by_column'))->index()->nullable();
            } elseif (config('userstamps.users_table_column_type') === 'ulid') {
                $this->ulid(config('userstamps.deleted_by_column'))->index()->nullable();
            } else {
                $this->unsignedInteger(config('userstamps.deleted_by_column'))->index()->nullable();
            }

            $this->foreign(config('userstamps.deleted_by_column'))
                ->references(config('userstamps.users_table_column_id_name'))
                ->on(config('userstamps.users_table'))
                ->onDelete('set null');

            return $this;
        });
    }

    private function registerDropUserstamps()
    {
        Blueprint::macro('dropUserstamps', function () {
            $createdByColumn = config('userstamps.created_by_column');
            $updatedByColumn = config('userstamps.updated_by_column');

            // For SQLite, we need to handle this differently due to limitations
            if (DB::connection() instanceof SQLiteConnection) {
                // SQLite doesn't support dropping columns with indexes easily
                // This is a known limitation - we'll skip the drop operation for SQLite
                return $this;
            }

            // Drop foreign keys first
            $this->dropForeign([$createdByColumn]);
            $this->dropForeign([$updatedByColumn]);

            // Then drop the columns (indexes will be dropped automatically)
            $this->dropColumn([$createdByColumn, $updatedByColumn]);

            return $this;
        });
    }

    private function registerDropSoftUserstamps()
    {
        Blueprint::macro('dropSoftUserstamps', function () {
            $deletedByColumn = config('userstamps.deleted_by_column');

            // For SQLite, we need to handle this differently due to limitations
            if (DB::connection() instanceof SQLiteConnection) {
                // SQLite doesn't support dropping columns with indexes easily
                // This is a known limitation - we'll skip the drop operation for SQLite
                return $this;
            }

            // Drop foreign key first
            $this->dropForeign([$deletedByColumn]);

            // Then drop the column (index will be dropped automatically)
            $this->dropColumn($deletedByColumn);

            return $this;
        });
    }
}
