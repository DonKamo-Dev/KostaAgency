<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('case_studies', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->string('url_demo')->nullable();
            $table->string('categoria', 50)->default('web');
            $table->string('metrica_valor', 50)->nullable();
            $table->string('metrica_label', 100)->nullable();
            $table->json('tags')->nullable();
            $table->string('gradient_inicio', 50)->default('#374151');
            $table->string('gradient_fin', 50)->default('#1f2937');
            $table->string('imagen')->nullable();
            $table->boolean('activo')->default(true);
            $table->unsignedSmallInteger('orden')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('case_studies');
    }
};
