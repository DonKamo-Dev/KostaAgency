<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('tipo_cliente', 50)->nullable()->after('servicio');
            $table->string('como_nos_conocio', 50)->nullable()->after('presupuesto');
            $table->string('estado', 30)->default('nuevo')->after('mensaje');
            $table->text('notas')->nullable()->after('estado');
            $table->json('metadata')->nullable()->after('notas');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['tipo_cliente', 'como_nos_conocio', 'estado', 'notas', 'metadata']);
        });
    }
};
