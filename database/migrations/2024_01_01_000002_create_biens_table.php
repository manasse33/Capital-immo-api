<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biens', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('slug', 191)->unique();
            $table->text('description');
            $table->bigInteger('prix');
            $table->integer('surface');
            $table->integer('pieces')->default(0);
            $table->integer('chambres')->default(0);
            $table->integer('salle_de_bain')->default(0);
            $table->integer('etage')->nullable();
            $table->enum('type', ['maison', 'villa', 'appartement', 'local', 'terrain']);
            $table->enum('transaction', ['vente', 'location']);
            $table->string('zone', 100);
            $table->string('quartier', 100);
            $table->string('reference', 50)->unique();
            $table->enum('statut', ['disponible', 'vendu', 'reserve'])->default('disponible');
            $table->boolean('en_vedette')->default(false);
            $table->json('caracteristiques')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('vue_count')->default(0);
            $table->timestamps();
            
            $table->index(['type', 'transaction', 'statut']);
            $table->index(['zone', 'quartier']);
            $table->index('en_vedette');
            $table->index('prix');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biens');
    }
};
