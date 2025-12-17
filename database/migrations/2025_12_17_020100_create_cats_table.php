<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('breed_id')->nullable()->constrained('cat_breeds')->nullOnDelete();
            $table->boolean('is_mix')->default(false);
            $table->foreignId('mix_breed_id')->nullable()->constrained('cat_breeds')->nullOnDelete();

            $table->string('name');
            $table->string('slug')->unique();
            $table->tinyInteger('age_months')->nullable();
            $table->string('gender')->nullable();
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->text('description')->nullable();
            $table->string('main_image')->nullable();
            $table->json('gallery')->nullable();
            $table->boolean('vaccinated')->default(false);
            $table->boolean('sterilized')->default(false);
            $table->enum('status', ['available','adopted','fostered','pending'])->default('available');
            $table->string('location')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cats');
    }
};



