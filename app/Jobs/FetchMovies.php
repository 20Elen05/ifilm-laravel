<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use App\Models\Movie;
use App\Models\Genre;



class FetchMovies implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $this->fetchPopularMovies(390);
        $this->fetchTopRatedMovies(390);
        $this->fetchNowPlayingMovies(20);
    }

    private function fetchPopularMovies($count)
    {
        $this->fetchMoviesByType('popular', $count, 1);
    }

    private function fetchTopRatedMovies($count)
    {
        $this->fetchMoviesByType('top_rated', $count, 2);
    }

    private function fetchNowPlayingMovies($count)
    {
        $this->fetchMoviesByType('now_playing', $count, 3);
    }

    private function fetchMoviesByType($type, $count, $categoryId)
{

    try{
$apiKey = "a348e7136197bd5186dd097b93931f79";
    $page = 1;
    $moviesCount = 0;

    while ($moviesCount < $count) {
        $response = Http::get("https://api.themoviedb.org/3/movie/$type?api_key=$apiKey&language=en-US&page=$page");

        if ($response->successful()) {
            $films = $response->json()['results'];
            foreach ($films as $movieData) {
                $movie = new Movie();
                $movie->movie_id = $movieData['id'];
                $movie->title = $movieData['title'];
                $movie->poster_path = $movieData['poster_path'] ?? null;
                $movie->original_language = $movieData['original_language'];
                $movie->original_title = $movieData['original_title'];
                $movie->overview = $movieData['overview'];
                $movie->popularity = $movieData['popularity'];
                $movie->vote_average = $movieData['vote_average'];
                $movie->vote_count = $movieData['vote_count'];
                $movie->release_date = $movieData['release_date'];
                $movie->category_id = $categoryId;

                $movieResponse = Http::get("https://api.themoviedb.org/3/movie/{$movieData['id']}?api_key=$apiKey&language=en");
                if ($movieResponse->successful()) {
                    $film = $movieResponse->json();
                    $movie->runtime = $film['runtime'];
                    $countries = $film['production_countries'];
                    $movie->production_countries = implode(',', array_column($countries, 'name'));

                    $movie->budget = $film['budget'];
                }

                $movie->save();

                $genreIds = $movieData['genre_ids'];
                foreach ($genreIds as $genreId) {
                    $genre = Genre::find($genreId);

                    if ($genre) {
                        $movie->genres()->attach($genreId);
                    }
                }

                $moviesCount++;

                if ($moviesCount >= $count) {
                    break 2;
                }
            }

            $page++;
        } else {
            break;
        }
    }
    }catch(Exception $e) {
        dd($e);
    }

}

}
