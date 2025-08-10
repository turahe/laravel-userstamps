<?php

namespace Turahe\UserStamps\Tests\Unit;

use Illuminate\Support\Facades\Config;
use Turahe\UserStamps\Config\UserStampsConfigValidator;
use Turahe\UserStamps\Tests\TestCase;

class EnhancedConfigurationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Set up a basic configuration for testing
        $this->app['config']->set('userstamps', [
            'users_table' => 'users',
            'users_table_column_type' => 'bigIncrements',
            'users_table_column_id_name' => 'id',
            'users_model' => 'App\User',
            'created_by_column' => 'created_by',
            'updated_by_column' => 'updated_by',
            'deleted_by_column' => 'deleted_by',
        ]);
    }

    public function test_it_validates_basic_configuration()
    {
        $errors = UserStampsConfigValidator::validate();

        $this->assertEmpty($errors, 'Basic configuration should be valid');
        $this->assertTrue(UserStampsConfigValidator::isValid());
    }

    public function test_it_validates_enhanced_column_configuration()
    {
        $this->app['config']->set('userstamps.columns', [
            'created_by' => [
                'name' => 'creator_id',
                'type' => 'bigInteger',
                'nullable' => true,
                'index' => true,
                'foreign_key' => true,
                'on_delete' => 'set null',
                'on_update' => 'cascade',
                'comment' => 'User who created this record',
            ],
            'updated_by' => [
                'name' => 'modifier_id',
                'type' => 'bigInteger',
                'nullable' => true,
                'index' => true,
                'foreign_key' => true,
            ],
            'deleted_by' => [
                'name' => 'remover_id',
                'type' => 'bigInteger',
                'nullable' => true,
                'index' => true,
                'foreign_key' => true,
            ],
        ]);

        $errors = UserStampsConfigValidator::validate();

        $this->assertEmpty($errors, 'Enhanced column configuration should be valid');
    }

    public function test_it_validates_custom_column_types()
    {
        $this->app['config']->set('userstamps.custom_column_types', [
            'user_id' => [
                'method' => 'unsignedBigInteger',
                'parameters' => [],
            ],
            'audit_user' => [
                'method' => 'string',
                'parameters' => [100],
            ],
            'tracking_id' => [
                'method' => 'char',
                'parameters' => [26],
            ],
        ]);

        $errors = UserStampsConfigValidator::validate();

        $this->assertEmpty($errors, 'Custom column types should be valid');
    }

    public function test_it_validates_advanced_configuration()
    {
        $this->app['config']->set('userstamps.advanced', [
            'foreign_keys' => true,
            'indexes' => true,
            'comments' => false,
            'sqlite_compatibility' => true,
            'default_foreign_key_behavior' => [
                'on_delete' => 'set null',
                'on_update' => 'cascade',
            ],
            'column_name_validation' => [
                'pattern' => '/^[a-zA-Z_][a-zA-Z0-9_]*$/',
                'max_length' => 64,
            ],
        ]);

        $errors = UserStampsConfigValidator::validate();

        $this->assertEmpty($errors, 'Advanced configuration should be valid');
    }

    public function test_it_detects_missing_required_configuration()
    {
        $this->app['config']->set('userstamps.users_table', null);

        $errors = UserStampsConfigValidator::validate();

        $this->assertNotEmpty($errors);
        $this->assertContains('Missing required configuration: users_table', $errors);
    }

    public function test_it_detects_invalid_column_type()
    {
        $this->app['config']->set('userstamps.users_table_column_type', 'invalid_type');

        $errors = UserStampsConfigValidator::validate();

        $this->assertNotEmpty($errors);
        $this->assertContains('Invalid configuration for users_table_column_type: invalid_type', $errors);
    }

    public function test_it_detects_invalid_column_configuration()
    {
        $this->app['config']->set('userstamps.columns.created_by.name', '');

        $errors = UserStampsConfigValidator::validate();

        $this->assertNotEmpty($errors);
        $this->assertContains('Column name for created_by must be a non-empty string', $errors);
    }

    public function test_it_detects_invalid_foreign_key_behavior()
    {
        $this->app['config']->set('userstamps.columns.created_by.on_delete', 'invalid_behavior');

        $errors = UserStampsConfigValidator::validate();

        $this->assertNotEmpty($errors);
        $this->assertContains('Invalid on_delete value for created_by: invalid_behavior. Valid values are: cascade, set null, restrict, no action', $errors);
    }

    public function test_it_detects_invalid_custom_column_type()
    {
        $this->app['config']->set('userstamps.custom_column_types.invalid_type', [
            'method' => 'invalidMethod',
            'parameters' => 'not_an_array',
        ]);

        $errors = UserStampsConfigValidator::validate();

        $this->assertNotEmpty($errors);
        $this->assertContains('Parameters for custom column type \'invalid_type\' must be an array', $errors);
    }

    public function test_it_provides_configuration_summary()
    {
        $this->app['config']->set('userstamps.columns', [
            'created_by' => ['name' => 'creator_id'],
            'updated_by' => ['name' => 'modifier_id'],
            'deleted_by' => ['name' => 'remover_id'],
        ]);

        $this->app['config']->set('userstamps.custom_column_types', [
            'user_id' => ['method' => 'unsignedBigInteger', 'parameters' => []],
        ]);

        Config::set('userstamps.advanced', [
            'foreign_keys' => true,
            'indexes' => false,
            'comments' => true,
            'sqlite_compatibility' => false,
        ]);

        $summary = UserStampsConfigValidator::getSummary();

        $this->assertArrayHasKey('basic', $summary);
        $this->assertArrayHasKey('columns', $summary);
        $this->assertArrayHasKey('custom_types', $summary);
        $this->assertArrayHasKey('advanced_features', $summary);

        $this->assertEquals('creator_id', $summary['columns']['created_by']);
        $this->assertEquals('modifier_id', $summary['columns']['updated_by']);
        $this->assertEquals('remover_id', $summary['columns']['deleted_by']);

        $this->assertContains('user_id', $summary['custom_types']);

        $this->assertTrue($summary['advanced_features']['foreign_keys']);
        $this->assertFalse($summary['advanced_features']['indexes']);
        $this->assertTrue($summary['advanced_features']['comments']);
        $this->assertFalse($summary['advanced_features']['sqlite_compatibility']);
    }

    public function test_it_validates_uuid_based_configuration()
    {
        Config::set('userstamps.users_table_column_type', 'uuid');
        Config::set('userstamps.columns', [
            'created_by' => [
                'name' => 'creator_uuid',
                'type' => 'uuid',
                'comment' => 'UUID of the user who created this record',
            ],
            'updated_by' => [
                'name' => 'modifier_uuid',
                'type' => 'uuid',
                'comment' => 'UUID of the user who last modified this record',
            ],
            'deleted_by' => [
                'name' => 'remover_uuid',
                'type' => 'uuid',
                'comment' => 'UUID of the user who removed this record',
            ],
        ]);

        $errors = UserStampsConfigValidator::validate();

        $this->assertEmpty($errors, 'UUID-based configuration should be valid');
    }

    public function test_it_validates_string_based_configuration()
    {
        Config::set('userstamps.columns', [
            'created_by' => [
                'name' => 'created_by_user',
                'type' => 'string',
                'comment' => 'Username of the user who created this record',
            ],
            'updated_by' => [
                'name' => 'updated_by_user',
                'type' => 'string',
                'comment' => 'Username of the user who last updated this record',
            ],
        ]);

        $errors = UserStampsConfigValidator::validate();

        $this->assertEmpty($errors, 'String-based configuration should be valid');
    }

    public function test_it_validates_audit_trail_configuration()
    {
        Config::set('userstamps.columns', [
            'created_by' => [
                'name' => 'author_id',
                'type' => 'bigInteger',
                'comment' => 'ID of the user who authored this record',
            ],
            'updated_by' => [
                'name' => 'editor_id',
                'type' => 'bigInteger',
                'comment' => 'ID of the user who last edited this record',
            ],
            'deleted_by' => [
                'name' => 'remover_id',
                'type' => 'bigInteger',
                'comment' => 'ID of the user who removed this record',
            ],
        ]);

        Config::set('userstamps.advanced', [
            'comments' => true,
            'performance' => [
                'batch_operations' => true,
                'lazy_loading' => false,
            ],
        ]);

        $errors = UserStampsConfigValidator::validate();

        $this->assertEmpty($errors, 'Audit trail configuration should be valid');
    }

    public function test_it_validates_column_name_patterns()
    {
        Config::set('userstamps.advanced.column_name_validation', [
            'pattern' => '/^[a-zA-Z_][a-zA-Z0-9_]*$/',
            'max_length' => 64,
        ]);

        // Valid column names
        Config::set('userstamps.columns.created_by.name', 'valid_column_name');
        Config::set('userstamps.columns.updated_by.name', 'another_valid_name');
        Config::set('userstamps.columns.deleted_by.name', 'valid123');

        $errors = UserStampsConfigValidator::validate();
        $this->assertEmpty($errors, 'Valid column names should pass validation');

        // Invalid column names
        Config::set('userstamps.columns.created_by.name', '123invalid');
        Config::set('userstamps.columns.updated_by.name', 'invalid-name');
        Config::set('userstamps.columns.deleted_by.name', 'invalid name');

        $errors = UserStampsConfigValidator::validate();
        $this->assertNotEmpty($errors, 'Invalid column names should fail validation');
    }

    public function test_it_validates_column_name_length()
    {
        Config::set('userstamps.advanced.column_name_validation', [
            'max_length' => 10,
        ]);

        // Column name within limit
        Config::set('userstamps.columns.created_by.name', 'short_name');

        $errors = UserStampsConfigValidator::validate();
        $this->assertEmpty($errors, 'Column name within length limit should pass validation');

        // Column name exceeding limit
        Config::set('userstamps.columns.created_by.name', 'very_long_column_name_that_exceeds_limit');

        $errors = UserStampsConfigValidator::validate();
        $this->assertNotEmpty($errors, 'Column name exceeding length limit should fail validation');
    }
}
