<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('breed_id')->nullable()->constrained('dog_breeds')->nullOnDelete();
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dogs');
    }
};
