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
        $response = $this->getMatchingMovies($request->search);
        if ($this->isAtLeastOneMovieMatched($response)) {
            $matchingMovies = $response->json();
            return $matchingMovies;
        }
        $popularMoviesResponse = $this->getPopularMovies();
        if ($popularMoviesResponse->failed()) {
            return response()->json([
                'error' => 'Could not retrieve data.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()->json([
            'searchedMovies' => 'Not found',
            'popularMovies' => $popularMoviesResponse->json(),
        ]);
    }

    private function getMatchingMovies($searchQuery)
    {
        return Http::get('http://movies-api-nginx/api/internal/movies', [
            'apiKey' => config('auth.api_key'),
            'searchQuery' => $searchQuery
        ]);
    }

    private function isAtLeastOneMovieMatched($response)
    {
        return $response->ok() && !empty($response->json());
    }

    private function getPopularMovies()
    {
        return Http::get('http://movies-api-nginx/api/internal/movies/popular', [
            'apiKey' => config('auth.api_key'),
        ]);
    }

    public function show($movieId)
    {
        $response = $this->getMovieDetails($movieId);
        if ($response->failed()) {
            return response()->json([
                'error' => 'Movie does not exist.',
            ], Response::HTTP_NOT_FOUND);
        }
        $movieDetails = $response->json();
        return $movieDetails;
    }

    private function getMovieDetails($movieId)
    {
        return Http::get('http://movies-api-nginx/api/internal/movies/' . $movieId, [
            'apiKey' => config('auth.api_key'),
        ]);
    }
}
