<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenresSeeder extends Seeder
{
    public function run()
    {
        DB::table('genres')->insert([
            [
                'id' => 28,
                'genre_name' => 'Action'
            ],
            [
                'id' => 12,
                'genre_name' => 'Adventure'
            ],
            [
                'id' => 16,
                'genre_name' => 'Animation'
            ],
            [
                'id' => 35,
                'genre_name' => 'Comedy'
            ],
            [
                'id' => 80,
                'genre_name' => 'Crime'
            ],
            [
                'id' => 99,
                'genre_name' => 'Documentary'
            ],
            [
                'id' => 18,
                'genre_name' => 'Drama'
            ],
            [
                'id' => 10751,
                'genre_name' => 'Family'
            ],
            [
                'id' => 14,
                'genre_name' => 'Fantasy'
            ],
            [
                'id' => 36,
                'genre_name' => 'History'
            ],
            [
                'id' => 27,
                'genre_name' => 'Horror'
            ],
            [
                'id' => 10402,
                'genre_name' => 'Music'
            ],
            [
                'id' => 9648,
                'genre_name' => 'Mystery'
            ],
            [
                'id' => 10749,
                'genre_name' => 'Romance'
            ],
            [
                'id' => 878,
                'genre_name' => 'Science Fiction'
            ],
            [
                'id' => 10770,
                'genre_name' => 'TV Movie'
            ],
            [
                'id' => 53,
                'genre_name' => 'Thriller'
            ],
            [
                'id' => 10752,
                'genre_name' => 'War'
            ],
            [
                'id' => 37,
                'genre_name' => 'Western'
            ],
        ]);
    }
}