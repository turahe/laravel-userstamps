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

        $app['config']->set('database.connections.mysql', [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'laravel_userstamps_test'),
            'username' => env('DB_USERNAME', 'laravel_userstamps'),
            'password' => env('DB_PASSWORD', 'password'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);

        $app['config']->set('database.connections.pgsql', [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'laravel_userstamps_test'),
            'username' => env('DB_USERNAME', 'laravel_userstamps'),
            'password' => env('DB_PASSWORD', 'password'),
            'charset' => 'utf8',
            'prefix' => '',
            'search_path' => 'public',
            'sslmode' => 'prefer',
        ]);

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
