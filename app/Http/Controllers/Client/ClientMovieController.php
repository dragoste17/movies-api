<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ClientMovieController extends Controller
{
    public function index()
    {
        $matchingMovies = $this->getMatchingMovies();
        if (!empty($matchingMovies)) {
            return $matchingMovies;
        }
        $popularMovies = $this->getPopularMovies();
        return $popularMovies;
    }

    public function show($movieId)
    {
        $movie = $this->getMovieDetails($movieId);
        if (!$movie) {
            return response('Movie does not exist.', Response::HTTP_NOT_FOUND);
        }
        return $movie;
    }
}
