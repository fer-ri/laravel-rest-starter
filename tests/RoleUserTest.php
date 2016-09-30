<?php

class RoleUserTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->migrate();
    }

    public function test_role_user_attach_role()
    {
        $superAdminRole = App\Models\Role::create(['name' => 'super-admin']);

        $moderatorRole = App\Models\Role::create(['name' => 'moderator']);

        $user = $this->asUser();

        $user->detachAllRoles();

        $this->assertSame([], $user->roles->toArray());

        $user->attachRole($superAdminRole);

        $this->assertTrue($user->hasRole('super-admin'));

        $this->assertTrue($user->isSuperAdmin());

        $this->assertSame(1, $user->roles->count());

        $this->seeInDatabase('role_user', [
            'role_id' => $superAdminRole->id,
            'user_id' => $user->id,
        ]);

        $user->attachRole($moderatorRole);

        $this->assertSame(2, $user->roles->count());

        $this->seeInDatabase('role_user', [
            'role_id' => $moderatorRole->id,
            'user_id' => $user->id,
        ]);

        $this->assertSame('super-admin', $user->role->name);
    }

    public function test_role_user_detach_role()
    {
        $superAdminRole = App\Models\Role::create(['name' => 'super-admin']);

        $moderatorRole = App\Models\Role::create(['name' => 'moderator']);

        $user = $this->asUser();

        $user->detachAllRoles();

        $this->assertSame([], $user->roles->toArray());

        $user->attachRole($superAdminRole);

        $user->attachRole($moderatorRole);

        $this->assertTrue($user->hasRole('super-admin'));

        $this->assertTrue($user->hasRole('moderator'));

        $user->detachRole($superAdminRole);

        $this->assertFalse($user->hasRole('super-admin'));

        $this->assertTrue($user->hasRole('moderator'));

        $this->assertSame(1, $user->roles->count());
    }

    public function test_role_user_detach_all_role()
    {
        $superAdminRole = App\Models\Role::create(['name' => 'super-admin']);

        $moderatorRole = App\Models\Role::create(['name' => 'moderator']);

        $user = $this->asUser();

        $user->detachAllRoles();

        $this->assertSame([], $user->roles->toArray());

        $user->attachRole($superAdminRole);

        $user->attachRole($moderatorRole);

        $this->assertTrue($user->hasRole('super-admin'));

        $this->assertTrue($user->hasRole('moderator'));

        $user->detachAllRoles();

        $this->assertFalse($user->hasRole('super-admin'));

        $this->assertFalse($user->hasRole('moderator'));

        $this->assertSame([], $user->roles->toArray());
    }

    public function test_role_user_has_role()
    {
        $superAdminRole = App\Models\Role::create(['name' => 'super-admin']);

        $user = $this->asUser();

        $user->attachRole($superAdminRole);

        $this->assertTrue($user->hasRole('super-admin'));

        $this->assertTrue($user->isSuperAdmin());

        $this->assertFalse($user->hasRole('moderator'));

        $user->detachRole($superAdminRole);

        $this->assertFalse($user->hasRole('super-admin'));

        $this->assertFalse($user->hasRole('moderator'));
    }

    public function test_role_user_role_attribute()
    {
        $superAdminRole = App\Models\Role::create(['name' => 'super-admin']);

        $moderatorRole = App\Models\Role::create(['name' => 'moderator']);

        $user = $this->asUser();

        $user->detachAllRoles();

        $this->assertNull($user->role);

        $user->attachRole($superAdminRole);

        $user->attachRole($moderatorRole);

        $this->assertSame('super-admin', $user->role->name);
    }
}
