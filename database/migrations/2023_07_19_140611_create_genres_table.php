<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGenresTable extends Migration
{
    public function up(): void
    {
        Schema::create('genres', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->string('genre_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('genres');
    }
}
