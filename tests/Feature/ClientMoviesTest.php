<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClientMoviesTest extends TestCase
{
    public function testMoviesSearchPositiveMatch()
    {
        $this->loginWithFakeUser();
        $response = $this->get('/api/movies?search=Si');
        $response->dump();
        $response->assertStatus(200);
    }

    public function testMoviesSearchNoMatch()
    {
        $this->loginWithFakeUser();
        $response = $this->get('/api/movies?search=1234');
        $response->dump();
        $response->assertStatus(200);
    }

    public function testMoviesSearchNoSearchQuery()
    {
        $this->loginWithFakeUser();
        $response = $this->get('/api/movies');
        $response->dump();
        $response->assertStatus(200);
    }

    private function loginWithFakeUser()
    {
        $user = new User;
        $user->id = rand(100000, 200000);
        $user->name = 'Test User';
        $this->be($user);
    }
}
