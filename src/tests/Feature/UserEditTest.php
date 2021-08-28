<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserEditTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function can_edit_user()
    {
        $user = User::factory()->create();

        $this->put(route('users.update', $user->id), [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();
    }

    /** @test */
    public function name_is_required()
    {
        $user = User::factory()->create();

        $this->put(route('users.update', $user->id), [
            'name' => '',
        ])
            ->assertRedirect()
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function email_is_required()
    {
        $user = User::factory()->create();

        $this->put(route('users.update', $user->id), [
            'email' => '',
        ])
            ->assertRedirect()
            ->assertSessionHasErrors('email');
    }

    /** @test */
    public function email_is_duplicated()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $this->put(route('users.update', $user1->id), [
            'email' => $user2->email,
        ])
            ->assertRedirect()
            ->assertSessionHasErrors('email');
    }
}
