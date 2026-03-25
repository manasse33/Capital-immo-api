<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('description_longue');
            $table->string('icon')->default('Home');
            $table->string('image')->nullable();
            $table->json('avantages');
            $table->string('cta')->default('En savoir plus');
            $table->integer('ordre')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('is_active');
            $table->index('ordre');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
