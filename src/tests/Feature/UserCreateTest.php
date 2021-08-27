<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserCreateTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function can_create_new_user()
    {
        $this->post(route('users.store'), [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        $this->assertEquals(1, User::count());
    }

    /** @test */
    public function name_is_required()
    {
        $this->post(route('users.store'), [
            'email' => $this->faker->email(),
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ])->assertSessionHasErrors('name');
    }

    /** @test */
    public function email_is_required()
    {
        $this->post(route('users.store'), [
            'name' => $this->faker->name(),
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ])->assertSessionHasErrors('email');
    }

    /** @test */
    public function password_is_required()
    {
        $this->post(route('users.store'), [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'password_confirmation' => '12345678',
        ])->assertSessionHasErrors('password');
    }

    /** @test */
    public function password_confirmation_is_required()
    {
        $this->post(route('users.store'), [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'password' => '12345678',
        ])->assertSessionHasErrors('password');
    }

    /** @test */
    public function password_must_contain_at_least_eight_characters()
    {
        $this->post(route('users.store'), [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'password' => '1234567',
            'password_confirmation' => '1234567',
        ])->assertSessionHasErrors('password');
    }

    /** @test */
    public function email_is_duplicated()
    {
        $user = User::factory()->create();

        $this->post(route('users.store'), [
            'name' => $this->faker->name(),
            'email' => $user->email,
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ])->assertSessionHasErrors('email');
    }

    /** @test */
    public function cannot_create_user_if_password_and_password_confirmation_are_not_the_same()
    {
        $this->post(route('users.store'), [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'password' => '12345678',
            'password_confirmation' => '87654321',
        ])->assertSessionHasErrors('password');
    }
}
