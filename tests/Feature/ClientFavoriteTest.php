<?php

namespace Tests\Feature;

use App\Models\Movie;
use App\Models\User;
use Database\Seeders\FavoriteSeeder;
use Database\Seeders\MovieSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ClientFavoriteTest extends TestCase
{
    use RefreshDatabase;
    const FAVORITES_NUM = 10;

    protected function setUp(): void
    {
        parent::setUp();
        try {
            Artisan::call('migrate:install');
        } catch (QueryException $e) {
            // migration table already exists
        }
        app('migrator')->run(database_path('migrations'));
    }

    public function testFavoritesList()
    {
        $user = $this->createUserWithFavorites();
        $response = $this->actingAs($user)->get('/api/favorites');
        $response->assertStatus(200)
            ->assertJsonCount(self::FAVORITES_NUM);
    }

    private function createUserWithFavorites()
    {
        $user = User::factory()->create();
        $this->seed(MovieSeeder::class);
        $movies = Movie::all();
        $user->favorites()->attach(
            $movies->random(self::FAVORITES_NUM)->pluck('id')->toArray()
        );
        return $user;
    }

    public function testFavoritesEmptyList()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/api/favorites');
        $response->assertStatus(200)
            ->assertJson([]);
    }

    public function testFavoritesStore()
    {
        $movie = Movie::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/api/favorite/' . $movie->id);
        $response->assertStatus(200);
    }
}
