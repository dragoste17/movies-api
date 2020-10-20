<?php

namespace Tests\Feature;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ClientFavoriteTest extends TestCase
{
    // use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:install');
        app('migrator')->run(database_path('migrations'));
    }

    public function testFavoritesList()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/api/favorites');
        $response->assertStatus(200);
    }

    public function testFavoritesStore()
    {
        $movie = Movie::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/api/favorite/' . $movie->id);
        $response->assertStatus(200);
    }
}
