<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        if ($request->movieIds) {
            return $this->fetchMoviesById($request->movieIds);
        }
        $matchingMovies = $this->fetchMoviesBySearchQuery($request->searchQuery);
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

    public function show(Movie $movie)
    {
        return $movie;
    }
}
