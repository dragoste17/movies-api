<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class ClientMovieController extends Controller
{
    public function index(Request $request)
    {
        $matchingMovies = $this->getMatchingMovies($request->search);
        if (!empty($matchingMovies)) {
            return $matchingMovies;
        }
        $popularMovies = $this->getPopularMovies();
        return $popularMovies;
    }

    private function getMatchingMovies($searchQuery)
    {
        return Http::get('http://movies-api-nginx/api/internal/movies', [
            'apiKey' => config('auth.api_key'),
            'searchQuery' => $searchQuery
        ]);
    }

    public function show($movieId)
    {
        $movie = $this->getMovieDetails($movieId);
        if (!$movie) {
            return response('Movie does not exist.', Response::HTTP_NOT_FOUND);
        }
        return $movie;
    }

    private function getMovieDetails($movieId)
    {
        return Http::get('http://movies-api-nginx/api/internal/movies/' . $movieId, [
            'apiKey' => config('auth.api_key'),
        ]);
    }
}
