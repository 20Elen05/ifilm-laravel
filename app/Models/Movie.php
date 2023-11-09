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

}
