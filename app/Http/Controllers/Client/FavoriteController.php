<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->favorites;
    }

    public function store(Request $request, Movie $movie)
    {
        $request->user()->addToFavorites($movie);
    }
}
