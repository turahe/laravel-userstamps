<?php

namespace Turahe\UserStamps\Tests\Unit;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Turahe\UserStamps\Tests\TestCase;

class ColumnTypesTest extends TestCase
{
    /**
     * Test userstamps with bigIncrements column type.
     *
     * @return void
     */
    public function test_it_can_create_userstamps_with_big_increments()
    {
        Config::set('userstamps.users_table_column_type', 'bigIncrements');

        Schema::create('test_big_increments_userstamps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->userstamps();
        });

        $columns = Schema::getColumnlisting('test_big_increments_userstamps');

        $this->assertContains('created_by', $columns);
        $this->assertContains('updated_by', $columns);
    }

    /**
     * Test userstamps with UUID column type.
     *
     * @return void
     */
    public function test_it_can_create_userstamps_with_uuid()
    {
        Config::set('userstamps.users_table_column_type', 'uuid');

        Schema::create('test_uuid_userstamps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->userstamps();
        });

        $columns = Schema::getColumnlisting('test_uuid_userstamps');

        $this->assertContains('created_by', $columns);
        $this->assertContains('updated_by', $columns);
    }

    /**
     * Test userstamps with ULID column type.
     *
     * @return void
     */
    public function test_it_can_create_userstamps_with_ulid()
    {
        Config::set('userstamps.users_table_column_type', 'ulid');

        Schema::create('test_ulid_userstamps', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->userstamps();
        });

        $columns = Schema::getColumnlisting('test_ulid_userstamps');

        $this->assertContains('created_by', $columns);
        $this->assertContains('updated_by', $columns);
    }

    /**
     * Test userstamps with increments column type.
     *
     * @return void
     */
    public function test_it_can_create_userstamps_with_increments()
    {
        Config::set('userstamps.users_table_column_type', 'increments');

        Schema::create('test_increments_userstamps', function (Blueprint $table) {
            $table->increments('id');
            $table->userstamps();
        });

        $columns = Schema::getColumnlisting('test_increments_userstamps');

        $this->assertContains('created_by', $columns);
        $this->assertContains('updated_by', $columns);
    }

    /**
     * Test soft userstamps with bigIncrements column type.
     *
     * @return void
     */
    public function test_it_can_create_soft_userstamps_with_big_increments()
    {
        Config::set('userstamps.users_table_column_type', 'bigIncrements');

        Schema::create('test_big_increments_soft_userstamps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->softUserstamps();
        });

        $columns = Schema::getColumnlisting('test_big_increments_soft_userstamps');

        $this->assertContains('deleted_by', $columns);
    }

    /**
     * Test soft userstamps with UUID column type.
     *
     * @return void
     */
    public function test_it_can_create_soft_userstamps_with_uuid()
    {
        Config::set('userstamps.users_table_column_type', 'uuid');

        Schema::create('test_uuid_soft_userstamps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->softUserstamps();
        });

        $columns = Schema::getColumnlisting('test_uuid_soft_userstamps');

        $this->assertContains('deleted_by', $columns);
    }

    /**
     * Test soft userstamps with ULID column type.
     *
     * @return void
     */
    public function test_it_can_create_soft_userstamps_with_ulid()
    {
        Config::set('userstamps.users_table_column_type', 'ulid');

        Schema::create('test_ulid_soft_userstamps', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->softUserstamps();
        });

        $columns = Schema::getColumnlisting('test_ulid_soft_userstamps');

        $this->assertContains('deleted_by', $columns);
    }

    /**
     * Test soft userstamps with increments column type.
     *
     * @return void
     */
    public function test_it_can_create_soft_userstamps_with_increments()
    {
        Config::set('userstamps.users_table_column_type', 'increments');

        Schema::create('test_increments_soft_userstamps', function (Blueprint $table) {
            $table->increments('id');
            $table->softUserstamps();
        });

        $columns = Schema::getColumnlisting('test_increments_soft_userstamps');

        $this->assertContains('deleted_by', $columns);
    }

    /**
     * Test that foreign key constraints are created correctly for different column types.
     *
     * @return void
     */
    public function test_foreign_key_constraints_are_created_for_different_column_types()
    {
        $columnTypes = ['bigIncrements', 'uuid', 'ulid', 'increments'];

        foreach ($columnTypes as $columnType) {
            $this->app['config']->set('userstamps.users_table_column_type', $columnType);

            $tableName = "test_foreign_keys_{$columnType}";

            Schema::create($tableName, function (Blueprint $table) {
                $table->userstamps();
            });

            // Verify the table was created successfully
            $this->assertTrue(Schema::hasTable($tableName));

            $columns = Schema::getColumnlisting($tableName);
            $this->assertContains('created_by', $columns);
            $this->assertContains('updated_by', $columns);
        }
    }

    /**
     * Test that indexes are created correctly for different column types.
     *
     * @return void
     */
    public function test_indexes_are_created_for_different_column_types()
    {
        $columnTypes = ['bigIncrements', 'uuid', 'ulid', 'increments'];

        foreach ($columnTypes as $columnType) {
            $this->app['config']->set('userstamps.users_table_column_type', $columnType);

            $tableName = "test_indexes_{$columnType}";

            Schema::create($tableName, function (Blueprint $table) {
                $table->userstamps();
            });

            // Verify the table was created successfully with columns
            $this->assertTrue(Schema::hasTable($tableName));

            $columns = Schema::getColumnlisting($tableName);
            $this->assertContains('created_by', $columns);
            $this->assertContains('updated_by', $columns);
        }
    }

    /**
     * Test that different column types work with custom column names.
     *
     * @return void
     */
    public function test_different_column_types_work_with_custom_column_names()
    {
        $columnTypes = ['bigIncrements', 'uuid', 'ulid', 'increments'];

        foreach ($columnTypes as $columnType) {
            $this->app['config']->set('userstamps.users_table_column_type', $columnType);
            $this->app['config']->set('userstamps.created_by_column', 'custom_created_by');
            $this->app['config']->set('userstamps.updated_by_column', 'custom_updated_by');
            $this->app['config']->set('userstamps.deleted_by_column', 'custom_deleted_by');

            // Debug: Check if config is set correctly
            $this->assertEquals('custom_created_by', config('userstamps.created_by_column'));
            $this->assertEquals('custom_updated_by', config('userstamps.updated_by_column'));
            $this->assertEquals('custom_deleted_by', config('userstamps.deleted_by_column'));

            $tableName = "test_custom_columns_{$columnType}";

            Schema::create($tableName, function (Blueprint $table) {
                $table->userstamps();
                $table->softUserstamps();
            });

            $columns = Schema::getColumnlisting($tableName);
            $this->assertContains('custom_created_by', $columns);
            $this->assertContains('custom_updated_by', $columns);
            $this->assertContains('custom_deleted_by', $columns);
        }
    }

    /**
     * Test that the default column type (bigIncrements) works correctly.
     *
     * @return void
     */
    public function test_default_column_type_works_correctly()
    {
        // Reset to default
        $this->app['config']->set('userstamps.users_table_column_type', 'bigIncrements');

        Schema::create('test_default_column_type', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->userstamps();
            $table->softUserstamps();
        });

        $columns = Schema::getColumnlisting('test_default_column_type');
        $this->assertContains('created_by', $columns);
        $this->assertContains('updated_by', $columns);
        $this->assertContains('deleted_by', $columns);
    }

    /**
     * Test that custom column names work correctly.
     *
     * @return void
     */
    public function test_custom_column_names_work_correctly()
    {
        // Set custom column names
        $this->app['config']->set('userstamps.created_by_column', 'custom_created_by');
        $this->app['config']->set('userstamps.updated_by_column', 'custom_updated_by');
        $this->app['config']->set('userstamps.deleted_by_column', 'custom_deleted_by');

        // Verify config is set
        $this->assertEquals('custom_created_by', config('userstamps.created_by_column'));
        $this->assertEquals('custom_updated_by', config('userstamps.updated_by_column'));
        $this->assertEquals('custom_deleted_by', config('userstamps.deleted_by_column'));

        // Create table with userstamps
        Schema::create('test_custom_columns_simple', function (Blueprint $table) {
            $table->increments('id');
            $table->userstamps();
        });

        // Check what columns were actually created
        $columns = Schema::getColumnlisting('test_custom_columns_simple');
        $this->assertContains('id', $columns);
        $this->assertContains('custom_created_by', $columns, 'Expected custom_created_by column not found. Actual columns: '.implode(', ', $columns));
        $this->assertContains('custom_updated_by', $columns, 'Expected custom_updated_by column not found. Actual columns: '.implode(', ', $columns));
    }
}
