<?php
namespace AuditLog\Test\TestCase\Model\Table;

use AuditLog\Model\Table\CurrentUserTrait;
use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\TestSuite\IntegrationTestCase;

// Some hacks to ensure the functionality we want from cake works 
define('TMP', tempnam(sys_get_temp_dir(), 'audit_log_test_session'));

// Start the session
session_start();

/**
 * Utility class used for testing the trait
 */
class ImplementsCurrentUser {
    use CurrentUserTrait;
}

/**
 * Tests for the CurrentUserTrait
 *
 */
class CurrentUserTraitTest extends TestCase
{
    public function setUp()
    {
        // \Cake\Network\Request::createFromGlobals() expects an application configuration 
        // to be present. We lie to cake that it does, here.. ;)
        Configure::write('App', [
            'name' => 'testApp',
        ]);
    }

    public function testCurrentUserMethodExists()
    {
        $fakeModel = new ImplementsCurrentUser();
        $this->assertTrue(method_exists($fakeModel, 'currentUser'));
    }

    public function testCurrentUserReturnsArray()
    {
        $fakeModel = new ImplementsCurrentUser();
        $data = $fakeModel->currentUser();
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('ip', $data);
        $this->assertArrayHasKey('url', $data);
        $this->assertArrayHasKey('description', $data);
    }

    public function testModifyingDescription()
    {
        $expected = 'This item is no longer relevant';

        $_SESSION['Auth'] = [
            'User' => [
                'username' => 'jeff',
            ],
        ];

        $fakeModel = new ImplementsCurrentUser();
        $data = $fakeModel->currentUser();

        $this->assertEquals("Action by jeff", $data['description']);
    }
}
