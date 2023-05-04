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
        Schema::create('cache_stats', function (Blueprint $table) {
            $table->string('key')->primary()->unique();
            $table->string('tags')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('hit')->default(0);
            $table->unsignedBigInteger('mis')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache_stats');
    }
};
