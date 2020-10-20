<?php

namespace Tests\Feature;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ClientMoviesTest extends TestCase
{
    use RefreshDatabase;

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

    public function testMoviesSearchPositiveMatch()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/api/movies?search=Si');
        $response->assertStatus(200);
    }

    public function testMoviesSearchNoMatch()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/api/movies?search=1234');
        $response->assertStatus(200);
    }

    public function testMoviesSearchNoSearchQuery()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/api/movies');
        $response->assertStatus(200);
    }

    public function testMovieDetails()
    {
        $user = User::factory()->create();
        $movie = Movie::factory()->create();
        $response = $this->actingAs($user)->get('/api/movie/' . $movie->id);
        $response->assertStatus(200);
    }

    public function testNonExistingMovieDetails()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/api/movie/231');
        $response->assertStatus(404);
    }
}
