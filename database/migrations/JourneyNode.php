<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('journey_nodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journey_id')->constrained()->cascadeOnDelete();
            $table->foreignId('post_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('order_index');
            $table->string('name');
            $table->string('type')->nullable(); // sight, food, stay, transport, etc.
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('transport_mode')->nullable(); // walk, bus, train...
            $table->string('transport_time')->nullable(); // "25 min", "2h"
            $table->text('accommodation_info')->nullable();
            $table->text('remarks')->nullable(); // tips, fees, notes
            $table->timestamps();

            $table->index(['journey_id', 'order_index']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journey_nodes');
    }
};