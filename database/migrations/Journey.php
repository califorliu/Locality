<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('journeys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('summary')->nullable();
            $table->string('main_city')->nullable();
            $table->string('main_country')->nullable();
            $table->unsignedInteger('days')->nullable();
            $table->string('visibility')->default('public'); // public/private
            $table->string('cover_image_path')->nullable();
            $table->timestamps();

            $table->index(['main_city', 'main_country']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journeys');
    }
};