<?php

class RoleTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->migrate();
    }

    public function test_role_set_permissions_attribute()
    {
        $role = App\Models\Role::create(['name' => 'super-admin']);

        $permissions = [
            'role:update' => true,
            'role:destroy' => true,
        ];

        $role->permissions = $permissions;

        $role->save();

        $this->seeInDatabase('roles', [
            'uuid' => $role->uuid,
            'name' => 'super-admin',
            'permissions' => json_encode($permissions),
        ]);
    }

    public function test_role_get_permissions_attribute()
    {
        $role = App\Models\Role::create(['name' => 'super-admin']);

        $permissions = [
            'role:update' => true,
            'role:destroy' => true,
        ];

        $role->permissions = $permissions;

        $role->save();

        $this->assertSame($permissions, $role->permissions);
    }

    public function test_role_set_permission()
    {
        $role = App\Models\Role::create(['name' => 'super-admin']);

        $role->setPermission('role:index', true);

        $this->assertSame([
            'role:index' => true,
        ], $role->permissions);

        $role->setPermission('role:destroy', false);

        $this->assertSame([
            'role:index' => true,
            'role:destroy' => false,
        ], $role->permissions);

        $role->setPermission('role:index', false);

        $this->assertSame([
            'role:index' => false,
            'role:destroy' => false,
        ], $role->permissions);
    }

    public function test_role_unset_permission()
    {
        $role = App\Models\Role::create(['name' => 'super-admin']);

        $permissions = [
            'role:update' => true,
            'role:destroy' => true,
        ];

        $role->permissions = $permissions;

        $role->save();

        $this->assertSame($permissions, $role->permissions);

        $role->unsetPermission('role:destroy');

        $this->assertSame(['role:update' => true], $role->permissions);
    }

    public function test_role_clear_permissions()
    {
        $role = App\Models\Role::create(['name' => 'super-admin']);

        $permissions = [
            'role:update' => true,
            'role:destroy' => true,
        ];

        $role->permissions = $permissions;

        $role->save();

        $this->assertSame($permissions, $role->permissions);

        $role->clearPermissions();

        $this->assertSame([], $role->permissions);
    }

    public function test_role_has_permission()
    {
        $role = App\Models\Role::create(['name' => 'role']);

        $permissions = [
            'foo' => true,
            'bar' => false,
        ];

        $role->permissions = $permissions;

        $role->save();

        $this->assertSame($permissions, $role->getAllPermissions());

        $this->assertTrue($role->hasPermission('foo'));

        $this->assertFalse($role->hasPermission('bar'));

        $this->assertFalse($role->hasPermission('baz'));

        $role->setPermission('baz');

        $this->assertTrue($role->hasPermission('baz'));
    }

    public function test_role_index_is_proctected()
    {
        $this->get('/roles');

        $this->seeStatusCode(401);
    }

    public function test_role_index_without_permission()
    {
        $user = $this->asUser();

        $this->get('/roles', $this->headers($user));

        $this->seeStatusCode(403);
    }

    public function test_role_index_with_permission()
    {
        $user = $this->asUser();

        $user->setPermission('role:index');

        $user->setPermission('role:update');

        $user->setPermission('role:destroy');

        factory(App\Models\Role::class)->create([
            'name' => 'super-admin',
        ]);

        $normalRole = factory(App\Models\Role::class)->create();

        $this->get('/roles', $this->headers($user));

        $this->seeStatusCode(200);

        $this->seeJsonStructure([
            'data' => [
                '*' => [
                    'uuid', 'name', 'displayName', 'description', 'permissions',
                    '_authorization' => [
                        'update', 'destroy',
                    ],
                ],
            ],
        ]);

        $json = collect(json_decode($this->response->getContent())->data);

        $jsonSuperAdmin = $json->where('name', 'super-admin')->first();

        $this->assertFalse($jsonSuperAdmin->_authorization->update);

        $this->assertFalse($jsonSuperAdmin->_authorization->destroy);

        $jsonNormalRole = $json->where('name', $normalRole->name)->first();

        $this->assertTrue($jsonNormalRole->_authorization->update);

        $this->assertTrue($jsonNormalRole->_authorization->destroy);
    }

    public function test_role_store_is_protected()
    {
        $this->post('/roles');

        $this->seeStatusCode(401);
    }

    public function test_role_store_without_permission()
    {
        $user = $this->asUser();

        $this->post('/roles', [
            'name' => 'my-role',
        ], $this->headers($user));

        $this->seeStatusCode(403);
    }

    public function test_role_store_with_permission()
    {
        $user = $this->asUser();

        $user->setPermission('role:store');

        $permissions = [
            'baz' => true,
            'foo' => false,
        ];

        $this->post('/roles', [
            'permissions' => $permissions + ['bar'],
            'level' => 2,
        ], $this->headers($user));

        $this->seeJsonStructure([
            'message',
            'errors' => ['name', 'permissions'],
        ]);

        $this->seeStatusCode(422);

        $this->post('/roles', [
            'name' => 'my-role',
            'permissions' => $permissions,
            'level' => 2,
        ], $this->headers($user));

        $this->seeStatusCode(201);

        $this->seeInDatabase('roles', [
            'name' => 'my-role',
            'permissions' => json_encode($permissions),
            'level' => 2,
        ]);
    }

    public function test_role_show_is_protected()
    {
        $role = factory(App\Models\Role::class)->create();

        $this->get('/roles/'.$role->uuid);

        $this->seeStatusCode(401);
    }

    public function test_role_show_without_permission()
    {
        $role = factory(App\Models\Role::class)->create();

        $user = $this->asUser();

        $this->get('/roles/'.$role->uuid, $this->headers($user));

        $this->seeStatusCode(403);
    }

    public function test_role_show_with_permission()
    {
        $role = factory(App\Models\Role::class)->create();

        $user = $this->asUser();

        $user->setPermission('role:show');

        $this->get('/roles/'.$role->uuid, $this->headers($user));

        $this->seeStatusCode(200);

        $this->seeJson([
            'uuid' => $role->uuid,
            'name' => $role->name,
            'displayName' => $role->display_name,
            'description' => $role->description,
            'permissions' => $role->permissions,
        ]);
    }

    public function test_role_update_is_protected()
    {
        $role = factory(App\Models\Role::class)->create();

        $this->put('/roles/'.$role->uuid);

        $this->seeStatusCode(401);
    }

    public function test_role_update_without_permission()
    {
        $role = factory(App\Models\Role::class)->create();

        $user = $this->asUser();

        $this->put('/roles/'.$role->uuid, [
            'name' => 'my-role',
        ], $this->headers($user));

        $this->seeStatusCode(403);
    }

    public function test_role_update_with_permission()
    {
        $role = factory(App\Models\Role::class)->create();

        $user = $this->asUser();

        $user->setPermission('role:update');

        $permissions = [
            'baz' => true,
            'foo' => false,
        ];

        $this->put('/roles/'.$role->uuid, [
            'permissions' => $permissions + ['bar'],
            'level' => 2,
        ], $this->headers($user));

        $this->seeJsonStructure([
            'message',
            'errors' => ['name', 'permissions'],
        ]);

        $this->seeStatusCode(422);

        $this->put('/roles/'.$role->uuid, [
            'name' => 'my-role',
            'permissions' => $permissions,
            'level' => 2,
        ], $this->headers($user));

        $this->seeStatusCode(204);

        $this->seeInDatabase('roles', [
            'uuid' => $role->uuid,
            'name' => 'my-role',
            'permissions' => json_encode($permissions),
            'level' => 2,
        ]);
    }

    public function test_role_destroy_is_protected()
    {
        $role = factory(App\Models\Role::class)->create();

        $this->delete('/roles/'.$role->uuid);

        $this->seeStatusCode(401);
    }

    public function test_role_destroy_without_permission()
    {
        $role = factory(App\Models\Role::class)->create();

        $user = $this->asUser();

        $this->delete('/roles/'.$role->uuid, [], $this->headers($user));

        $this->seeStatusCode(403);
    }

    public function test_role_destroy_with_permission()
    {
        $role = factory(App\Models\Role::class)->create();

        $user = $this->asUser();

        $user->setPermission('role:destroy');

        $this->delete('/roles/'.$role->uuid, [], $this->headers($user));

        $this->seeStatusCode(204);

        $this->seeInDatabase('roles', [
            'uuid' => $role->uuid,
        ]);

        $this->notSeeInDatabase('roles', [
            'uuid' => $role->uuid,
            'deleted_at' => null,
        ]);
    }

    public function test_role_destroy_with_permission_and_has_users()
    {
        $role = factory(App\Models\Role::class)->create();

        $user = $this->asUser();

        $user->setPermission('role:destroy');

        $user->attachRole($role);

        $this->delete('/roles/'.$role->uuid, [], $this->headers($user));

        $this->seeStatusCode(403);
    }
}
