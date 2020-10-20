<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    const MAX_NUM_OF_POPULAR_SEARCHES = 5;
    const MAX_NUM_OF_POPULAR_MOVIES = 20;


    public static function getPopular()
    {
        $popularSearches = self::getMostSearchedQueries();
        if ($popularSearches->isEmpty()) {
            return self::randomlyPickPopularMovies();
        }
        $popularMovies = self::searchMoviesBy($popularSearches);
        return $popularMovies;
    }

    private static function getMostSearchedQueries()
    {
        return SearchFrequency::orderBy('count', 'desc')
            ->take(self::MAX_NUM_OF_POPULAR_SEARCHES)
            ->get();
    }

    private static function randomlyPickPopularMovies()
    {
        return Movie::inRandomOrder()
            ->limit(self::MAX_NUM_OF_POPULAR_MOVIES)
            ->get();
    }

    private static function searchMoviesBy($popularSearches)
    {
        $popularMovies = collect();
        foreach ($popularSearches as $search) {
            $matchedMovies = self::getMatchedMovies($search->query);
            $popularMovies = $popularMovies->merge($matchedMovies);
            if ($popularMovies->count() > self::MAX_NUM_OF_POPULAR_MOVIES) {
                return $popularMovies->take(self::MAX_NUM_OF_POPULAR_MOVIES);
            }
        }
        return $popularMovies;
    }

    private static function getMatchedMovies($query)
    {
        return Movie::where('name', 'LIKE', '%' . $query . '%')
            ->get();
    }
}
