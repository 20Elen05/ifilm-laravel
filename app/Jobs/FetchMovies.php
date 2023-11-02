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
use App\Models\MovieGenre;
use App\Models\CategoryMovie;


class FetchMovies implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle() {
        $this->fetchAndSyncMovies('popular', 1);
        $this->fetchAndSyncMovies('top_rated', 2);
        $this->fetchAndSyncMovies('now_playing', 3);
    }
    private function fetchAndSyncMovies($type, $categoryId) {

        try{
            $apiKey = "a348e7136197bd5186dd097b93931f79";
            $movieCollection = collect();
            $movieGenreCollection = collect();
            $categoryCollection = collect();

            $response = Http::get("https://api.themoviedb.org/3/movie/$type?api_key=$apiKey&language=en");
            $responseRu = Http::get("https://api.themoviedb.org/3/movie/$type?api_key=$apiKey&language=ru");
            if ($response->successful() && $responseRu->successful()) {
                $films = $response->json()['results'];
                $filmsRu = $responseRu->json()['results'];

                foreach ($films as $index => $movieData) {
                    $movieId = $movieData['id'];

                    $existingMovie = Movie::where('movie_id', $movieId)->first();
                    if ($existingMovie) {
                        continue;
                    }

                    $movie = new Movie();
                    $movie->movie_id = $movieId;
                    $movie->original_language = $movieData['original_language'];
                    $movie->original_title = $movieData['original_title'];
                    $movie->popularity = $movieData['popularity'];
                    $movie->vote_average = $movieData['vote_average'];
                    $movie->vote_count = $movieData['vote_count'];
                    $movie->release_date = $movieData['release_date'];
                    $movie->content = json_encode([
                        'en' => [
                            'title' => $movieData['title'],
                            'poster_path' => $movieData['poster_path'],
                            'overview' => $movieData['overview'],
                        ],
                        'ru' => [
                            'title' => $filmsRu[$index]['title'],
                            'poster_path' => $filmsRu[$index]['poster_path'],
                            'overview' => $filmsRu[$index]['overview'],
                        ]
                    ]);
                    $movieResponse = Http::get("https://api.themoviedb.org/3/movie/{$movieData['id']}?api_key=$apiKey&language=en");
                    if ($movieResponse->successful()) {
                        $film = $movieResponse->json();
                        $movie->runtime = $film['runtime'];
                        $countries = $film['production_countries'];
                        $movie->production_countries = implode(',', array_column($countries, 'name'));
                        $movie->budget = $film['budget'];
                    }

                    $genreIds = $movieData['genre_ids'];
                    $existingGenres = Genre::whereIn('id', $genreIds)->get()->pluck('id');
                    foreach ($existingGenres as $genreId) {
                        $movieGenreCollection->add([
                            'movie_id' => $movie->movie_id,
                            'genre_id' => $genreId
                        ]);
                    }

                    $categoryCollection->add([
                        'movie_id' => $movie->movie_id,
                        'category_id' => $categoryId
                    ]);

                    $movieCollection->add($movie);
                }
            }
            Movie::insert($movieCollection->toArray());
            MovieGenre::insert($movieGenreCollection->toArray());
            CategoryMovie::insert($categoryCollection->toArray());
        }catch(Exception $e) {
            dd($e);
        }
    }
}
