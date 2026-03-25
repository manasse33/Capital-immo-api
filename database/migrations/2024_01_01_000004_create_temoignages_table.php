<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('temoignages', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('initiale', 2)->nullable();
            $table->string('role');
            $table->text('message');
            $table->string('avatar')->nullable();
            $table->tinyInteger('note')->default(5);
            $table->boolean('is_active')->default(true);
            $table->integer('ordre')->default(0);
            $table->timestamps();
            
            $table->index('is_active');
            $table->index('ordre');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('temoignages');
    }
};
