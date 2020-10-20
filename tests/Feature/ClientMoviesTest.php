<?php

namespace Tests\Feature;

use App\Models\Movie;
use App\Models\User;
use Database\Seeders\MovieSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ClientMoviesTest extends TestCase
{
    use RefreshDatabase;

    const SEARCH = 'Si';
    const MOVIE_ID = 10;

    protected function setUp(): void
    {
        parent::setUp();
        try {
            Artisan::call('migrate:install');
        } catch (QueryException $e) {
            // migration table already exists
        }
        app('migrator')->run(database_path('migrations'));
        $this->seed(MovieSeeder::class);
    }

    public function testMoviesSearchPositiveMatch()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/api/movies?search=' . self::SEARCH);
        $response->assertStatus(200)
            ->assertJsonCount($this->getMatchingMoviesInDbNum())
            ->assertJsonMissing([
                'searchedMovies' => 'Not found'
            ]);
    }

    private function getMatchingMoviesInDbNum()
    {
        // Specify connection as internal api call will use mysql db
        return DB::connection('mysql')
            ->table('movies')
            ->where('name', 'LIKE', '%' . self::SEARCH . '%')
            ->count();
    }

    public function testMoviesSearchNoMatch()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/api/movies?search=1234');
        $response->assertStatus(200)
         ->assertJsonFragment([
             'searchedMovies' => 'Not found'
         ]);
    }

    public function testMoviesSearchNoSearchQuery()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/api/movies');
        $response->assertStatus(200)
            ->assertJsonCount(Movie::count())
            ->assertJsonMissing([
                'searchedMovies' => 'Not found'
            ]);
    }

    public function testMovieDetails()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/api/movie/' . self::MOVIE_ID);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => 10
            ]);
    }

    public function testNonExistingMovieDetails()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/api/movie/231');
        $response->assertStatus(404);
    }
}
