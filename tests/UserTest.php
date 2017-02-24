<?php


class UserTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->migrate();
    }

    public function test_user_has_permission()
    {
        $superAdmin = App\Models\Role::create(['name' => 'super-admin', 'level' => 999]);

        $moderator = App\Models\Role::create(['name' => 'moderator', 'level' => 1]);

        $superAdminPermissions = [
            'foo' => true,
        ];

        $superAdmin->permissions = $superAdminPermissions;

        $superAdmin->save();

        $moderatorPermissions = [
            'foo' => false,
            'bar' => false,
        ];

        $moderator->permissions = $moderatorPermissions;

        $moderator->save();

        $rolePermissions = $superAdminPermissions + $moderatorPermissions;

        $user = $this->asUser();

        $user->attachRole($superAdmin);

        $user->attachRole($moderator);

        $this->assertTrue(in_array($user->getAllPermissions(), $rolePermissions));

        $this->assertTrue($user->hasPermission('foo'));

        $this->assertFalse($user->hasPermission('bar'));

        $this->assertFalse($user->hasPermission('baz'));

        $user->setPermission('baz');

        $this->assertTrue($user->hasPermission('baz'));

        $this->assertTrue(in_array($user->getAllPermissions(), $rolePermissions + ['baz' => true]));

        $user->setPermission('foo', false);

        $this->assertFalse($user->hasPermission('foo'));

        $this->assertFalse($user->hasPermission('bar'));
    }

    public function test_user_index()
    {
        $superAdmin = $this->asSuperAdmin();

        $normalUser = factory(App\Models\User::class)->create();

        $this->get('/users');

        $this->seeStatusCode(401);

        $this->get('/users', $this->headers($superAdmin));

        $this->seeStatusCode(200);

        $this->seeJsonStructure([
            'data' => [
                '*' => [
                    'uuid', 'name', 'email', 'created_at', 'updated_at',
                    '_authorization' => [
                        'update', 'destroy',
                    ],
                ],
            ],
        ]);

        $json = collect(json_decode($this->response->getContent())->data);

        $jsonSuperAdmin = $json->where('email', $superAdmin->email)->first();

        $this->assertTrue($jsonSuperAdmin->_authorization->update);

        $this->assertFalse($jsonSuperAdmin->_authorization->destroy);

        $jsonNormalUser = $json->where('email', $normalUser->email)->first();

        $this->assertTrue($jsonNormalUser->_authorization->update);

        $this->assertTrue($jsonNormalUser->_authorization->destroy);
    }

    public function test_user_destroy_by_super_admin()
    {
        $superAdmin = $this->asSuperAdmin();

        $normalUser = $this->asUser();

        $this->delete('/users/'.$normalUser->uuid);

        $this->seeStatusCode(401);

        $this->delete('/users/'.$normalUser->uuid, [], $this->headers($superAdmin));

        $this->seeStatusCode(204);

        $this->seeInDatabase('users', [
            'uuid' => $normalUser->uuid,
        ]);

        $this->notSeeInDatabase('users', [
            'uuid' => $normalUser->uuid,
            'deleted_at' => null,
        ]);

        $this->delete('/users/'.$superAdmin->uuid, [], $this->headers($superAdmin));

        $this->seeStatusCode(403);
    }

    public function test_user_destroy_by_user_with_permission()
    {
        $john = $this->asUser();

        $john->setPermission('user:destroy');

        $jane = $this->asUser();

        $this->delete('/users/'.$jane->uuid, [], $this->headers($john));

        $this->seeStatusCode(204);
    }

    public function test_user_destroy_by_user_without_permission()
    {
        $john = $this->asUser();

        $jane = $this->asUser();

        $this->delete('/users/'.$jane->uuid, [], $this->headers($john));

        $this->seeStatusCode(403);
    }
}
