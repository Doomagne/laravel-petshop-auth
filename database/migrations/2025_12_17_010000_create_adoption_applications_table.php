<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('adoption_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dog_id')->constrained('dogs')->cascadeOnDelete();

            $table->string('full_name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->text('address');
            $table->text('message')->nullable();

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_note')->nullable();

            $table->timestamps();

            // prevent duplicate applications for the same dog by the same user
            $table->unique(['user_id', 'dog_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adoption_applications');
    }
};



