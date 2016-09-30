<?php

class AuthorizationTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->migrate();
    }

    public function test_authorization_user_is_super_admin()
    {
        $user = $this->createSuperAdmin();

        $normalUser = $this->asUser();

        $this->assertTrue($user->isSuperAdmin());

        $this->assertFalse($normalUser->isSuperAdmin());
    }

    public function test_authorization_super_admin_policy()
    {
        $user = $this->createSuperAdmin();

        $normalUser = $this->asUser();

        $gate = Gate::forUser($user);

        $this->assertFalse($gate->allows('destroy', $user));

        $this->assertTrue($gate->allows('destroy', $normalUser));

        $this->assertTrue($gate->allows('index', $user));

        $this->assertTrue($gate->allows('store', $user));

        $this->assertTrue($gate->allows('show', $user));

        $this->assertTrue($gate->allows('update', $user));
    }

    public function test_authorization_user_policy()
    {
        $normalUser = $this->asUser();

        $superAdmin = $this->createSuperAdmin();

        $otherUser = $this->asUser();

        $gate = Gate::forUser($normalUser);

        $this->assertFalse($gate->allows('destroy', $normalUser));

        $this->assertFalse($gate->allows('destroy', $otherUser));

        $this->assertFalse($gate->allows('destroy', $superAdmin));
    }

    protected function createSuperAdmin()
    {
        $user = $this->asUser();

        $role = App\Models\Role::create(['name' => 'super-admin']);

        $user->attachRole($role);

        return $user;
    }
}
