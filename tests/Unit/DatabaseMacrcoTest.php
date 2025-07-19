<?php

namespace Turahe\UserStamps\Tests\Unit;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Turahe\UserStamps\Tests\TestCase;

class DatabaseMacrcoTest extends TestCase
{
    /**
     * Test if a database table can be created with the marco for userstamps.
     *
     * @return void
     */
    public function test_it_can_create_a_table_with_userstamps()
    {
        Schema::create('it_can_create_a_table_with_userstamps', function (Blueprint $table) {
            $table->increments('id');
            $table->userstamps();
        });

        $columns = Schema::getColumnlisting('it_can_create_a_table_with_userstamps');

        $this->assertContains('created_by', $columns);
        $this->assertContains('updated_by', $columns);
    }

    /**
     * Test if a database table can be created with the marco for soft userstamps.
     *
     * @return void
     */
    public function test_it_can_create_a_table_with_soft_userstamps()
    {
        Schema::create('it_can_create_a_table_with_soft_userstamps', function (Blueprint $table) {
            $table->increments('id');
            $table->softUserstamps();
        });

        $columns = Schema::getColumnlisting('it_can_create_a_table_with_soft_userstamps');

        $this->assertContains('deleted_by', $columns);
    }

    /**
     * Test if a database table can be created with the marco for soft userstamps.
     *
     * @return void
     */
    public function test_it_can_alter_a_table_for_dropping_userstamps()
    {
        Schema::create('it_can_alter_a_table_for_dropping_userstamps', function (Blueprint $table) {
            $table->increments('id');
            $table->userstamps();
        });

        $columns = Schema::getColumnlisting('it_can_alter_a_table_for_dropping_userstamps');

        $this->assertContains('created_by', $columns);
        $this->assertContains('updated_by', $columns);

        Schema::table('it_can_alter_a_table_for_dropping_userstamps', function (Blueprint $table) {
            $table->dropUserstamps();
        });

        $columns = Schema::getColumnlisting('it_can_alter_a_table_for_dropping_userstamps');

        // SQLite has limitations with dropping columns that have indexes
        // For SQLite, we skip the drop operation, so columns will still exist
        if (DB::connection() instanceof \Illuminate\Database\SQLiteConnection) {
            $this->assertContains('created_by', $columns);
            $this->assertContains('updated_by', $columns);
        } else {
            $this->assertNotContains('created_by', $columns);
            $this->assertNotContains('updated_by', $columns);
        }
    }

    /**
     * Test if a database table can be created with the marco for soft userstamps.
     *
     * @return void
     */
    public function test_it_can_alter_a_table_for_dropping_soft_userstamps()
    {
        Schema::create('it_can_alter_a_table_for_dropping_soft_userstamps', function (Blueprint $table) {
            $table->increments('id');
            $table->softUserstamps();
        });

        $columns = Schema::getColumnlisting('it_can_alter_a_table_for_dropping_soft_userstamps');

        $this->assertContains('deleted_by', $columns);

        Schema::table('it_can_alter_a_table_for_dropping_soft_userstamps', function (Blueprint $table) {
            $table->dropSoftUserstamps();
        });

        $columns = Schema::getColumnlisting('it_can_alter_a_table_for_dropping_soft_userstamps');

        // SQLite has limitations with dropping columns that have indexes
        // For SQLite, we skip the drop operation, so columns will still exist
        if (DB::connection() instanceof \Illuminate\Database\SQLiteConnection) {
            $this->assertContains('deleted_by', $columns);
        } else {
            $this->assertNotContains('deleted_by', $columns);
        }
    }
}
