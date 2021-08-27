<?php

namespace Tests\Feature;

use App\Models\PagePosition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Client;
use Tests\TestCase;

class ClientPagePositionDeleteTest extends TestCase
{
    use RefreshDatabase, Client;

    /** @test */
    public function owner_user_can_delete_page_position()
    {
        $this->signIn('owner');

        $this->deletePagePosition();
    }

    /** @test */
    public function admin_user_can_delete_page_position()
    {
        $this->signIn('admin');

        $this->deletePagePosition();
    }

    /** @test */
    public function general_user_cannot_delete_page_position()
    {
        $this->signIn('general');

        $pagePosition = PagePosition::factory()->create([
            'page_id' => $this->page->id,
        ]);

        $response = $this->delete(route('page_positions.destory', [$this->site->id, $pagePosition->id]), [
            'delete' => 'delete',
        ]);

        $response->assertStatus(403);
    }

    /**
     * delete page position
     *
     * @return response
     */
    private function deletePagePosition()
    {
        $pagePosition = PagePosition::factory()->create([
            'page_id' => $this->page->id,
        ]);

        $this->delete(route('page_positions.destory', [$this->site->id, $pagePosition->id]), [
            'delete' => 'delete',
        ]);

        $this->assertSoftDeleted('page_positions', ['id' => $pagePosition->id]);
    }
}
