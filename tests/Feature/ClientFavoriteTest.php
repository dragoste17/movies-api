<?php

namespace Tests\Feature;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ClientFavoriteTest extends TestCase
{
    use RefreshDatabase;
    const FAVORITES_NUM = 10;
    const NON_EXISTING_MOVIE_ID = 234230;

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
        $response = $this->actingAs($user)->get('api/' . config('app.api_latest') . '/favorites');
        $response->assertStatus(200)
            ->assertJsonCount(self::FAVORITES_NUM);
    }

    public function testFavoritesEmptyList()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('api/' . config('app.api_latest') . '/favorites');
        $response->assertStatus(200)
            ->assertJson([]);
    }

    public function testFavoritesListWithDeletedMovie()
    {
        /**
         * Case where movie was favorited but now doesn't exist in DB.
         * We favorite FAVORITES_NUM + 1 movie and expect FAVORITES_NUM of movies
         * to be returned as the extra movie does not exist in DB.
         */
        $user = $this->createUserWithFavorites();
        $this->favoriteNonExistingMovie($user);
        $response = $this->actingAs($user)->get('api/' . config('app.api_latest') . '/favorites');
        $response->assertStatus(200)
            ->assertJsonCount(self::FAVORITES_NUM);
    }

    private function createUserWithFavorites()
    {
        $user = User::factory()->create();
        $movieIds = DB::connection('mysql')
            ->table('movies')
            ->get()
            ->pluck('id')
            ->all();
        $user->favorites()->attach(
            Arr::random($movieIds, self::FAVORITES_NUM)
        );
        return $user;
    }

    private function favoriteNonExistingMovie($user)
    {
        $user->favorites()->attach(
            self::NON_EXISTING_MOVIE_ID
        );
    }

    public function testFavoritesStore()
    {
        $movie = Movie::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('api/' . config('app.api_latest') . '/favorite/' . $movie->id);
        $response->assertStatus(200);
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'movie_id' => $movie->id,
        ]);
    }
}
