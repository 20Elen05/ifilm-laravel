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
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropForeign('ratings_movie_id_foreign');
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->foreign('movie_id')->references('id')->on('movies');
        });
    }
};
