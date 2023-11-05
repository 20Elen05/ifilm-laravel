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
        try {
            $apiKey = "a348e7136197bd5186dd097b93931f79";
            $response = Http::get("https://api.themoviedb.org/3/movie/$type?api_key=$apiKey&language=en");
            $responseRu = Http::get("https://api.themoviedb.org/3/movie/$type?api_key=$apiKey&language=ru");

            if ($response->successful() && $responseRu->successful()) {
                $films = $response->json()['results'];
                $filmsRu = $responseRu->json()['results'];

                foreach ($films as $index => $movieData) {
                    $movieDataRu = $filmsRu[$index];

                    Movie::createNewMovie($movieData, $movieDataRu, $categoryId);
                }
            }

        } catch (Exception $e) {
            dd($e);
        }
    }
}
