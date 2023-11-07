<?php declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create('category_movie', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('movie_id');
            $table->integer('category_id');
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        //
    }
};
