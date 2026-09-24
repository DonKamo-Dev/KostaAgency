<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('case_studies', function (Blueprint $table) {
            $table->string('source_type', 20)->nullable()->after('categoria');
            $table->foreignId('client_id')->nullable()->after('source_type')->constrained()->nullOnDelete();
            $table->foreignId('linked_case_study_id')->nullable()->after('client_id')->constrained('case_studies')->nullOnDelete();
        });

        DB::table('case_studies')->where('categoria', 'social')->update(['categoria' => 'films']);
    }

    public function down(): void
    {
        DB::table('case_studies')->where('categoria', 'films')->update(['categoria' => 'social']);

        Schema::table('case_studies', function (Blueprint $table) {
            $table->dropConstrainedForeignId('linked_case_study_id');
            $table->dropConstrainedForeignId('client_id');
            $table->dropColumn('source_type');
        });
    }
};
