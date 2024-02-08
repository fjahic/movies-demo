<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Favorite;
use App\Models\Follow;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    private array $movies;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(20)->create();

        $genresData = File::json(__DIR__ . '/../fixtures/genres.json');

        foreach ($genresData['genres'] as $genreItem) {
            Genre::create($genreItem);
        }

        $moviesData = File::json(__DIR__ . '/../fixtures/movies.json');

        $this->movies = [];
        foreach ($moviesData as $movieItem) {
            $movie = Movie::firstOrCreate([
                'title' => $movieItem['title'],
                'storyline' => $movieItem['storyline'],
                'release_year' => $movieItem['release_year'],
                'director' => $movieItem['director'],
            ]);

            foreach ($movieItem['genres'] as $genre) {
                Genre::where('name', $genre)->first()->movies()->attach($movie->id);
            }

            $this->movies[] = $movie;
        }

        foreach ($users as $user) {
            Review::factory(20)
                ->for($user)
                ->sequence(fn(Sequence $sequence) => ['movie_id' => $this->getNextMovie()->id])
                ->create();

            Favorite::factory(20)
                ->for($user)
                ->sequence(fn(Sequence $sequence) => ['movie_id' => $this->getNextMovie()->id])
                ->create();

            Follow::factory(20)
                ->for($user)
                ->sequence(fn(Sequence $sequence) => ['movie_id' => $this->getNextMovie()->id])
                ->create();
        }
    }

    public function getNextMovie(): Movie
    {
        $next = next($this->movies);

        if ($next === false) {
            $next = reset($this->movies);
        }

        return $next;
    }
}
