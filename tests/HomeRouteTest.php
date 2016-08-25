<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HomeRouteTest extends TestCase
{
    public function test_hello_page()
    {
        $this->get('/hello')
            ->seeJson(['hello' => 'world']);
    }

    public function test_restricted_page_without_token()
    {
        $this->get('/restricted-area')
            ->assertResponseStatus(401);
    }

    public function test_restricted_page()
    {
        $this->migrate();

        $user = $this->asUser();

        $this->get('/restricted-area', $this->headers($user));

        $this->seeStatusCode(200);

        $this->seeJson(['restricted' => 'area']);
    }
}
