<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\FetchMovies;

class SendMovies extends Command
{
    protected $signature = 'movies:fetch';
    protected $description = 'Dispatches FetchMovies Job';

    public function handle()
    {
        FetchMovies::dispatch();
        $this->info('FetchMovies dispatched successfully!');
    }
}
