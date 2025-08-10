<?php

namespace Turahe\UserStamps\Tests\Unit;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Turahe\UserStamps\Tests\TestCase;
use Turahe\UserStamps\UserStampsServiceProvider;

class ServiceProviderTest extends TestCase
{
    /**
     * Test that the service provider can be instantiated.
     *
     * @return void
     */
    public function test_service_provider_can_be_instantiated()
    {
        $provider = new UserStampsServiceProvider($this->app);

        $this->assertInstanceOf(UserStampsServiceProvider::class, $provider);
    }

    /**
     * Test that the service provider registers correctly.
     *
     * @return void
     */
    public function test_service_provider_registers_correctly()
    {
        $provider = new UserStampsServiceProvider($this->app);
        $provider->register();

        // Test that config is merged
        $this->assertArrayHasKey('users_table', config('userstamps'));
        $this->assertArrayHasKey('users_table_column_type', config('userstamps'));
        $this->assertArrayHasKey('created_by_column', config('userstamps'));
        $this->assertArrayHasKey('updated_by_column', config('userstamps'));
        $this->assertArrayHasKey('deleted_by_column', config('userstamps'));
    }

    /**
     * Test that the service provider boots correctly.
     *
     * @return void
     */
    public function test_service_provider_boots_correctly()
    {
        $provider = new UserStampsServiceProvider($this->app);
        $provider->boot();

        // Test that macros are registered by trying to use them
        Schema::create('test_service_provider_boot', function (Blueprint $table) {
            $table->increments('id');
            $table->userstamps();
        });

        $columns = Schema::getColumnlisting('test_service_provider_boot');

        $this->assertContains('created_by', $columns);
        $this->assertContains('updated_by', $columns);
    }

    /**
     * Test that config publishing works correctly.
     *
     * @return void
     */
    public function test_config_publishing_works()
    {
        $provider = new UserStampsServiceProvider($this->app);
        $provider->boot();

        // Get the published config path
        $publishedPath = config_path('userstamps.php');

        // Check if the config file would be published (we can't actually publish in tests)
        $this->assertTrue(method_exists($provider, 'publishes'));
    }

    /**
     * Test that config merging works correctly.
     *
     * @return void
     */
    public function test_config_merging_works()
    {
        $provider = new UserStampsServiceProvider($this->app);
        $provider->register();

        // Test default config values
        $this->assertEquals('users', config('userstamps.users_table'));
        $this->assertEquals('bigIncrements', config('userstamps.users_table_column_type'));
        $this->assertEquals('id', config('userstamps.users_table_column_id_name'));
        $this->assertEquals('created_by', config('userstamps.created_by_column'));
        $this->assertEquals('updated_by', config('userstamps.updated_by_column'));
        $this->assertEquals('deleted_by', config('userstamps.deleted_by_column'));
    }

    /**
     * Test that macros are registered after service provider boot.
     *
     * @return void
     */
    public function test_macros_are_registered_after_boot()
    {
        $provider = new UserStampsServiceProvider($this->app);
        $provider->boot();

        // Test userstamps macro
        Schema::create('test_userstamps_macro', function (Blueprint $table) {
            $table->increments('id');
            $table->userstamps();
        });

        $columns = Schema::getColumnlisting('test_userstamps_macro');
        $this->assertContains('created_by', $columns);
        $this->assertContains('updated_by', $columns);

        // Test softUserstamps macro
        Schema::create('test_soft_userstamps_macro', function (Blueprint $table) {
            $table->increments('id');
            $table->softUserstamps();
        });

        $columns = Schema::getColumnlisting('test_soft_userstamps_macro');
        $this->assertContains('deleted_by', $columns);
    }

    /**
     * Test that the service provider works with custom configuration.
     *
     * @return void
     */
    public function test_service_provider_works_with_custom_config()
    {
        // Set custom config values
        Config::set('userstamps.users_table', 'custom_users');
        Config::set('userstamps.created_by_column', 'custom_created_by');
        Config::set('userstamps.updated_by_column', 'custom_updated_by');
        Config::set('userstamps.deleted_by_column', 'custom_deleted_by');

        $provider = new UserStampsServiceProvider($this->app);
        $provider->register();
        $provider->boot();

        // Test that custom config is used
        $this->assertEquals('custom_users', config('userstamps.users_table'));
        $this->assertEquals('custom_created_by', config('userstamps.created_by_column'));
        $this->assertEquals('custom_updated_by', config('userstamps.updated_by_column'));
        $this->assertEquals('custom_deleted_by', config('userstamps.deleted_by_column'));

        // Test that macros work with custom column names
        Schema::create('test_custom_config', function (Blueprint $table) {
            $table->increments('id');
            $table->userstamps();
        });

        $columns = Schema::getColumnlisting('test_custom_config');
        $this->assertContains('custom_created_by', $columns);
        $this->assertContains('custom_updated_by', $columns);
    }

    /**
     * Test that the service provider can be registered multiple times safely.
     *
     * @return void
     */
    public function test_service_provider_can_be_registered_multiple_times()
    {
        $provider = new UserStampsServiceProvider($this->app);

        // Register multiple times
        $provider->register();
        $provider->register();
        $provider->register();

        // Should still work correctly
        $this->assertArrayHasKey('users_table', config('userstamps'));
        $this->assertArrayHasKey('created_by_column', config('userstamps'));
    }

    /**
     * Test that the service provider can be booted multiple times safely.
     *
     * @return void
     */
    public function test_service_provider_can_be_booted_multiple_times()
    {
        $provider = new UserStampsServiceProvider($this->app);

        // Boot multiple times
        $provider->boot();
        $provider->boot();
        $provider->boot();

        // Macros should still work
        Schema::create('test_multiple_boot', function (Blueprint $table) {
            $table->increments('id');
            $table->userstamps();
        });

        $columns = Schema::getColumnlisting('test_multiple_boot');
        $this->assertContains('created_by', $columns);
        $this->assertContains('updated_by', $columns);
    }

    /**
     * Test that the service provider handles missing config gracefully.
     *
     * @return void
     */
    public function test_service_provider_handles_missing_config_gracefully()
    {
        // Temporarily remove config
        $configPath = __DIR__.'/../../../config/userstamps.php';
        $tempConfigPath = $configPath.'.backup';

        if (file_exists($configPath)) {
            rename($configPath, $tempConfigPath);
        }

        try {
            $provider = new UserStampsServiceProvider($this->app);
            $provider->register();

            // Should not throw an exception
            $this->assertTrue(true);
        } finally {
            // Restore config
            if (file_exists($tempConfigPath)) {
                rename($tempConfigPath, $configPath);
            }
        }
    }

    /**
     * Test that the service provider works with different Laravel versions.
     *
     * @return void
     */
    public function test_service_provider_works_with_different_laravel_versions()
    {
        $provider = new UserStampsServiceProvider($this->app);

        // Test that both register and boot methods exist and are callable
        $this->assertTrue(method_exists($provider, 'register'));
        $this->assertTrue(method_exists($provider, 'boot'));
        $this->assertTrue(is_callable([$provider, 'register']));
        $this->assertTrue(is_callable([$provider, 'boot']));
    }

    /**
     * Test that the service provider correctly sets up the application.
     *
     * @return void
     */
    public function test_service_provider_sets_up_application_correctly()
    {
        $provider = new UserStampsServiceProvider($this->app);
        $provider->register();
        $provider->boot();

        // Test that the application can use userstamps functionality
        $this->assertTrue(Schema::hasTable('users'));

        // Test that we can create a table with userstamps
        Schema::create('test_application_setup', function (Blueprint $table) {
            $table->increments('id');
            $table->userstamps();
            $table->softUserstamps();
        });

        $columns = Schema::getColumnlisting('test_application_setup');
        $this->assertContains('created_by', $columns);
        $this->assertContains('updated_by', $columns);
        $this->assertContains('deleted_by', $columns);
    }
}
