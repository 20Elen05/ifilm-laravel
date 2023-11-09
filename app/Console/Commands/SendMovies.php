<?php
namespace App\Console\Commands;

use App\Http\Contracts\MoviesRepositoryInterface;
use Illuminate\Console\Command;
use App\Jobs\FetchMovies;

class SendMovies extends Command
{
    protected $signature = 'movies:fetch';
    protected $description = 'Dispatches FetchMovies Job';

    public function handle()
    {
        FetchMovies::dispatch(app()->make(MoviesRepositoryInterface::class));
        $this->info('FetchMovies dispatched successfully!');
    }
}
