<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->enum('type', ['solid_text', 'galaxy_space'])->default('solid_text');
            $table->unsignedSmallInteger('order_index')->default(0);
            // galaxy_space: {background: '#0d0d1a', viewport: {x: 0, y: 0, zoom: 1.0}}
            // solid_text:   {background: '#ffffff', padding: 'normal'}
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['project_id', 'order_index']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slides');
    }
};
