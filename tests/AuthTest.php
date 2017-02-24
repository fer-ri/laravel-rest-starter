<?php

use MailThief\Facades\MailThief;

class AuthTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->migrate();
    }

    public function test_login()
    {
        $user = $this->asUser();

        $this->post('/auth/login', [
            'email' => $user->email,
            'password' => 'secret',
        ]);

        $this->seeJsonStructure(['token']);
    }

    public function test_register_and_email_activation()
    {
        // Block and intercept outgoing mail, important!
        MailThief::hijack();

        $this->post('auth/register', [
            'name' => 'john doe',
            'email' => 'johndoe@mail.com',
            'password' => 'secret',
        ]);

        $this->seeJsonStructure([
            'data' => [
                'uuid', 'name', 'email', 'created_at',
            ],
        ]);

        // Check that an email was sent to this email address
        $this->assertTrue(MailThief::hasMessageFor('johndoe@mail.com'));

        // Make sure the email has the correct subject
        $this->assertEquals('Confirm your email address', MailThief::lastMessage()->subject);

        // Make sure the email was sent from the correct address
        // (`from` can be a list, so we return it as a collection)
        $this->assertEquals('no-reply@mail.com', MailThief::lastMessage()->from->first());
    }

    public function test_activate_new_user()
    {
        $user = factory(App\Models\User::class)->create();

        $this->get('/auth/activate?activation_code='.$user->activation_code);

        $this->seeJsonStructure(['token']);
    }

    public function test_get_current_user()
    {
        $this->migrate();

        $user = $this->asUser();

        $this->get('/auth/me', $this->headers($user));

        $this->seeJsonStructure([
            'data' => [
                'uuid', 'name', 'email', 'created_at',
            ],
        ]);
    }

    public function test_logout()
    {
        $this->migrate();

        $user = $this->asUser();

        $headers = $this->headers($user);

        $this->get('/auth/logout', $headers);

        $this->seeStatusCode(204);

        $this->get('/auth/me', $headers);

        $this->seeStatusCode(401);
    }

    public function test_access_using_private_token()
    {
        $this->migrate();

        $user = $this->asUser([
            'private_token' => str_random(60),
        ]);

        $this->get('/auth/me?private_token='.$user->private_token);

        $this->seeJsonStructure([
            'data' => [
                'uuid', 'name', 'email', 'created_at',
            ],
        ]);

        $this->get('/auth/me', [
            'Accept' => 'application/json',
            'PRIVATE-TOKEN' => $user->private_token,
        ]);

        $this->seeJsonStructure([
            'data' => [
                'uuid', 'name', 'email', 'created_at',
            ],
        ]);
    }

    // Wait for bug fix from MailThief when using with PasswordBroker
    // public function test_recovery_and_email_recovery()
    // {
        // Block and intercept outgoing mail, important!
        // MailThief::hijack();

        // $this->migrate();

        // $user = $this->asUser();

        // $this->post('/auth/recovery', ['email' => $user->email]);

        // $this->seeStatusCode(204);

        // Check that an email was sent to this email address
        // $this->assertTrue(MailThief::hasMessageFor($user->email));

        // Make sure the email has the correct subject
        // $this->assertEquals('Password recovery', MailThief::lastMessage()->subject);

        // Make sure the email was sent from the correct address
        // (`from` can be a list, so we return it as a collection)
        // $this->assertEquals(env('MAIL_SENDER'), MailThief::lastMessage()->from->first());
    // }

    // Still find out why new token same with current token that already blacklisted
    // public function test_refresh_token()
    // {
    //     $user = $this->asUser();

    //     $this->get('/auth/refresh-token', $this->headers($user));

    //     $this->seeJsonStructure(['token']);
    // }
}
