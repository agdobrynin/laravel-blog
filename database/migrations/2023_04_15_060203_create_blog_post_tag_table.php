<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blog_post_tag', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignIdFor(\App\Models\BlogPost::class)
                ->constrained('blog_posts')
                ->cascadeOnDelete();

            $table->foreignIdFor(\App\Models\Tag::class)
                ->constrained('tags')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_post_tag');
    }
};
