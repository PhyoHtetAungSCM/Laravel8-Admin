<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserDeleteTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function can_delete_user()
    {
        $user = User::factory()->create();

        $this->delete(route('users.destroy', $user->id))
            ->assertRedirect()
            ->assertSessionHasNoErrors();
    }
}
