<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('telephone');
            $table->string('email')->nullable();
            $table->string('objet');
            $table->text('message');
            $table->foreignId('bien_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reference_bien')->nullable();
            $table->boolean('is_read')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('is_read');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
