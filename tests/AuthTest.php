<?php

use MailThief\Facades\MailThief;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        \Artisan::call('migrate');
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_new_user_are_sent_a_activation_email()
    {
        // Block and intercept outgoing mail, important!
        MailThief::hijack();

        $this->post('auth/register', [
            'name' => 'john doe',
            'email' => 'johndoe@mail.com',
            'password' => 'secret',
        ]);

        // Check that an email was sent to this email address
        $this->assertTrue(MailThief::hasMessageFor('johndoe@mail.com'));

        // Make sure the email has the correct subject
        $this->assertEquals('Confirm your email address', MailThief::lastMessage()->subject);

        // Make sure the email was sent from the correct address
        // (`from` can be a list, so we return it as a collection)
        $this->assertEquals('no-reply@mail.com', MailThief::lastMessage()->from->first());
    }
}
