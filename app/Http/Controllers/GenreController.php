<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\GenreResource;
use App\Models\Genre;
use Illuminate\Http\Resources\Json\JsonResource;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {
        return GenreResource::collection(Genre::paginate());
    }
}
