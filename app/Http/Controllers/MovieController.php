<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $matchingMovies = Movie::where('name', 'LIKE', '%' . $request->searchQuery . '%')
            ->get();
        return $matchingMovies;
    }

    public function show(Movie $movie)
    {
        return $movie;
    }
}
