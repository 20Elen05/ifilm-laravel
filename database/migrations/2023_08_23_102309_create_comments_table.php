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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('content');
            $table->integer('user_id');
            $table->integer('movie_id');
            $table->unsignedInteger('likes_count')->default(0);

        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
