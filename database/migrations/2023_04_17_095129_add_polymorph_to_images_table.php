<?php

use App\Models\BlogPost;
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
        Schema::table('images', function (Blueprint $table) {
            $table->morphs('imageable');
        });

        $blogPostImages = DB::table('images')->select(['id', 'blog_post_id'])->get();

        DB::transaction(function () use ($blogPostImages) {
            foreach ($blogPostImages as $image) {
                DB::update(
                    'update images set imageable_id = ?, imageable_type = ? where id = ?',
                    [$image->blog_post_id, BlogPost::class, $image->id]
                );
            }
        });

        Schema::table('images', fn(Blueprint $table) => $table->dropColumn('blog_post_id'));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('images', function (Blueprint $table) {
            $table->unsignedBigInteger('blog_post_id')->nullable();
        });

        $blogPostImages = DB::table('images')->select(['id', 'imageable_id'])
            ->where('imageable_type', BlogPost::class)->get();

        DB::transaction(function () use ($blogPostImages) {
            foreach ($blogPostImages as $image) {
                DB::update(
                    'update images set blog_post_id = ? where id = ?',
                    [$image->imageable_id, $image->id]
                );
            }
        });

        Schema::table('images', function (Blueprint $table) {
            $table->dropMorphs('imageable');
        });
    }
};
