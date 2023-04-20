<?php

use App\Models\BlogPost;
use App\Models\Tag;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('taggables', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->morphs('taggable');

            $table->foreignIdFor(Tag::class)
                ->constrained('tags')
                ->cascadeOnDelete();
        });

        // Copy
        $rows = DB::table('blog_post_tag')
            ->select(['created_at', 'updated_at', 'blog_post_id as taggable_id', 'tag_id'])
            ->get()
            ->map(function ($item) {
                $item->taggable_type = BlogPost::class;

                return (array)$item;
            });

        DB::table('taggables')->insert($rows->toArray());

        Schema::dropIfExists('blog_post_tag');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('blog_post_tag', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignIdFor(BlogPost::class)
                ->constrained('blog_posts')
                ->cascadeOnDelete();

            $table->foreignIdFor(Tag::class)
                ->constrained('tags')
                ->cascadeOnDelete();

            $table->unique(['blog_post_id', 'tag_id']);
        });


        // Copy
        $rows = DB::table('taggables')
            ->select(['created_at', 'updated_at', 'taggable_id as blog_post_id', 'tag_id'])
            ->where('taggable_type', BlogPost::class)
            ->get()
            ->map(fn($item) => (array)$item);

        DB::table('taggables')->insert($rows->toArray());

        Schema::drop('taggables');
    }
};
