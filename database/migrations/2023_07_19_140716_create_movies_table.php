<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up() {
    Schema::create('movies', function (Blueprint $table) {
        $table->id();
        $table->bigInteger('movie_id');
        $table->string('title');
        $table->string('poster_path')->nullable();
        $table->string('original_language');
        $table->string('original_title');
        $table->text('overview');
        $table->float('popularity');
        $table->float('vote_average');
        $table->unsignedInteger('vote_count');
        $table->date('release_date');
        $table->integer('category_id');
        $table->integer('runtime');
        $table->string('production_countries');
        $table->integer('budget');
        $table->timestamps();
});
}

public function down()
{
    Schema::dropIfExists('movies');
}
};
