<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('case_studies', function (Blueprint $table) {
            $table->longText('imagen_data')->nullable()->after('imagen');
            $table->string('imagen_mime', 100)->nullable()->after('imagen_data');
        });
    }

    public function down(): void
    {
        Schema::table('case_studies', function (Blueprint $table) {
            $table->dropColumn(['imagen_data', 'imagen_mime']);
        });
    }
};
