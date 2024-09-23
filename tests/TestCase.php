<?php

namespace Turahe\UserStamps\Tests;

use Turahe\UserStamps\Tests\Models\User;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return ['Turahe\UserStamps\UserStampsServiceProvider'];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
    }

    protected function setUpDatabase($app)
    {
        $app['config']->set('userstamps.users_model', User::class);

        $app['db.schema']->create('users', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();

            $table->timestamps();
        });

        $app['db.schema']->create('laravel_userstamps', function ($table) {
            $table->increments('id');
            $table->string('name');

            $table->timestamps();
            $table->softDeletes();

            $table->userstamps();
            $table->softUserstamps();
        });
    }
}
