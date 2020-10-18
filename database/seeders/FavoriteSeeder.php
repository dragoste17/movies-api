<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Database\Seeder;

class FavoriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $movies = Movie::all();
        User::all()->each(function ($user) use ($movies) {
            $this->attachRandomNumOfFavoriteMovies($movies, $user);
        });
    }

    private function attachRandomNumOfFavoriteMovies($movies, $user)
    {
        $favoriteMoviesNum = rand(0, 12);
        if ($favoriteMoviesNum === 0) {
            return;
        }
        $user->favorites()->attach(
            $movies->random($favoriteMoviesNum)->pluck('id')->toArray()
        );
    }
}
