<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $favorites = Favorite::whereUserId($request->user()->id)->get();
        if ($favorites->isEmpty()) {
            return response()->json([]);
        }
        $favoriteMovieIds = $favorites->pluck('movie_id')->toArray();
        $response = $this->getMoviesById($favoriteMovieIds);
        if ($response->failed()) {
            return response()->json([
                'error' => 'Could not retrieve movies.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $response->json();
    }

    private function getMoviesById($ids)
    {
        return Http::get('http://movies-api-nginx/api/internal/movies', [
            'apiKey' => config('auth.api_key'),
            'movieIds' => $ids,
        ]);
    }

    public function store(Request $request, $movieId)
    {
        $favorite = new Favorite;
        $favorite->user_id = $request->user()->id;
        $favorite->movie_id = $movieId;
        $favorite->save();
    }
}
