<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\SearchFrequency;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        if ($request->movieIds) {
            return $this->fetchMoviesById($request->movieIds);
        }
        $matchingMovies = $this->fetchMoviesBySearchQuery($request->searchQuery);
        $this->updateSearchFrequency($request->searchQuery);
        return $matchingMovies;
    }

    private function fetchMoviesById($ids)
    {
        return Movie::whereIn('id', $ids)
            ->get();
    }

    private function fetchMoviesBySearchQuery($query)
    {
        return Movie::where('name', 'LIKE', '%' . $query . '%')
            ->get();
    }

    private function updateSearchFrequency($query)
    {
        if (!$query) {
            return;
        }
        $searchFrequency = SearchFrequency::firstOrCreate(
            ['query' => $query],
            ['count' => 0]
        );
        $searchFrequency->count += 1;
        $searchFrequency->save();
    }

    public function show(Movie $movie)
    {
        return $movie;
    }

    public function getPopularMovies()
    {
        return Movie::getPopular();
    }
}
