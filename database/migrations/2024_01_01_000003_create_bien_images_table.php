<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bien_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bien_id')->constrained()->cascadeOnDelete();
            $table->string('url');
            $table->integer('ordre')->default(0);
            $table->string('legende')->nullable();
            $table->timestamps();
            
            $table->index(['bien_id', 'ordre']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bien_images');
    }
};
