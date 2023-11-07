<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Movie extends Model
{
    use HasFactory;


    protected $guarded = [];

    protected $primaryKey = 'movie_id';

    /**
     * @return BelongsToMany
     */
    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'movie_genre', 'movie_id', 'genre_id');
    }

    /**
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * @return MorphMany
     */
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany // Fix the method name to start with a lowercase "b"
    {
        return $this->belongsToMany(Category::class, 'category_movie', 'movie_id', 'category_id');
    }

    /**
     * @return HasMany
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'movie_id', 'movie_id'); // Make sure the primary key is 'movie_id'
    }

    /**
     * @param $movieData
     * @param $movieDataRu
     * @param $categoryId
     * @return Movie
     */
    public static function createNewMovie($movieData, $movieDataRu, $categoryId)
    {
        $apiKey = "a348e7136197bd5186dd097b93931f79";

        $movieId = $movieData['id'];

        $existingMovie = self::where('movie_id', $movieId)->first();
        if ($existingMovie) {
            return $existingMovie;
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
                'title' => $movieDataRu['title'],
                'poster_path' => $movieDataRu['poster_path'],
                'overview' => $movieDataRu['overview'],
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
            MovieGenre::create([
                'movie_id' => $movie->movie_id,
                'genre_id' => $genreId
            ]);
        }

        CategoryMovie::create([
            'movie_id' => $movie->movie_id,
            'category_id' => $categoryId
        ]);

        $movie->save();

        return $movie;
    }
}
