<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->enum('visibility', ['public', 'private'])->default('private');
            $table->timestamps();

            $table->index(['user_id', 'visibility']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
