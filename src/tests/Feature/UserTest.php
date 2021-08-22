<?php

namespace Tests\Feature;

use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_page_is_shown_properly()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
