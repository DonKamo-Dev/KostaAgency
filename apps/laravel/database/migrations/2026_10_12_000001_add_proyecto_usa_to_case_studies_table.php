<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('case_studies', 'proyecto_usa')) {
            Schema::table('case_studies', function (Blueprint $table) {
                $table->boolean('proyecto_usa')->default(false)->after('activo');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('case_studies', 'proyecto_usa')) {
            Schema::table('case_studies', function (Blueprint $table) {
                $table->dropColumn('proyecto_usa');
            });
        }
    }
};
