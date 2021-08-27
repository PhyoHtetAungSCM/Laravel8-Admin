<?php

namespace Tests\Feature;

use App\Models\PagePosition;
use App\Models\SiteUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\Client;
use Tests\TestCase;

class ClientPagePositionEditTest extends TestCase
{
    use RefreshDatabase, WithFaker, Client;

    /** @test */
    public function owner_user_can_edit_page_position()
    {
        $this->signIn('owner');

        $this->editPagePosition();
    }

    /** @test */
    public function admin_user_can_edit_page_position()
    {
        $this->signIn('admin');

        $this->editPagePosition();
    }

    /** @test */
    public function general_user_who_has_site_permission_can_edit_page_position()
    {
        $this->signIn('general');

        SiteUser::create(['site_id' => $this->site->id, 'user_id' => Auth::id()]);

        $this->editPagePosition();
    }

    /** @test */
    public function general_user_who_does_not_have_site_permission_cannot_edit_page_position()
    {
        $this->signIn('general');

        $response = $this->post(route('page_positions.update', $this->site->id), [
            'page_id' => $this->page->id,
            'position_id' => $this->pagePosition->id,
            'name' => $this->faker->text(25),
            'selector' => $this->faker->text(),
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function name_is_required()
    {
        $this->signIn();

        $this->post(route('page_positions.update', [
            'site_id' => $this->site->id,
            'page_id' => $this->page->id,
            'position_id' => $this->pagePosition->id,
            'selector' => $this->faker->text(),
        ]))->assertSessionHasErrors('name');
    }

    /** @test */
    public function selector_is_required()
    {
        $this->signIn();

        $this->post(route('page_positions.update', [
            'site_id' => $this->site->id,
            'page_id' => $this->page->id,
            'position_id' => $this->pagePosition->id,
            'name' => $this->faker->text(25),
        ]))->assertSessionHasErrors('selector');
    }

    /** @test */
    public function name_is_duplicated()
    {
        $this->signIn();

        $pagePosition = PagePosition::factory()->create([
            'page_id' => $this->page->id,
            'name' => $this->faker->text(25),
            'selector' => $this->faker->text(),
        ]);

        $this->put(route('page_positions.update', [
            'site_id' => $this->site->id,
            'page_id' => $this->page->id,
            'position_id' => $pagePosition->id,
            'name' => $this->pagePosition->name,
            'selector' => $this->faker->text(),
        ]))->assertSessionHasErrors('name');
    }

    /** @test */
    public function selector_is_duplicated()
    {
        $this->signIn();

        $pagePosition = PagePosition::factory()->create([
            'page_id' => $this->page->id,
            'name' => $this->faker->text(25),
            'selector' => $this->faker->text(),
        ]);

        $this->put(route('page_positions.update', [
            'site_id' => $this->site->id,
            'page_id' => $this->page->id,
            'position_id' => $pagePosition->id,
            'name' => $this->faker->text(25),
            'selector' => $this->pagePosition->selector,
        ]))->assertSessionHasErrors('selector');
    }

    /** @test */
    public function name_is_not_duplicated_with_same_position_id()
    {
        $this->signIn();

        $this->put(route('page_positions.update', [
            'site_id' => $this->site->id,
            'page_id' => $this->page->id,
            'position_id' => $this->pagePosition->id,
            'name' => $this->pagePosition->name,
            'selector' => $this->faker->text(),
        ]))->assertSessionHasNoErrors();
    }

    /** @test */
    public function selector_is_not_duplicated_with_same_position_id()
    {
        $this->signIn();

        $this->put(route('page_positions.update', [
            'site_id' => $this->site->id,
            'page_id' => $this->page->id,
            'position_id' => $this->pagePosition->id,
            'name' => $this->faker->text(25),
            'selector' => $this->pagePosition->selector,
        ]))->assertSessionHasNoErrors();
    }

    /**
     * crate page position
     *
     * @return void
     */
    private function editPagePosition()
    {
        $this->put(route('page_positions.update', [
            'site_id' => $this->site->id,
            'page_id' => $this->page->id,
            'position_id' => $this->pagePosition->id,
            'name' => $this->faker->text(25),
            'selector' => $this->faker->text(),
        ]))
            ->assertRedirect()
            ->assertSessionHasNoErrors();
    }
}
