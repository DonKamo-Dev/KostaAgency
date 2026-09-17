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
            $table->unsignedBigInteger('company_id')->nullable()->after('id');
        });

        $companyId = $this->configuredCompanyId()
            ?? DB::table('companies')->orderBy('id')->value('id');

        if ($companyId !== null) {
            DB::table('case_studies')
                ->whereNull('company_id')
                ->update(['company_id' => $companyId]);
        }

        Schema::table('case_studies', function (Blueprint $table) {
            $table->index('company_id');
        });

        Schema::table('case_studies', function (Blueprint $table) {
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('case_studies', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
        });

        Schema::table('case_studies', function (Blueprint $table) {
            $table->dropIndex(['company_id']);
        });

        Schema::table('case_studies', function (Blueprint $table) {
            $table->dropColumn('company_id');
        });
    }

    private function configuredCompanyId(): ?int
    {
        $configured = config('kamo.public_company_id');

        if (! is_int($configured) && ! (is_string($configured) && ctype_digit($configured))) {
            return null;
        }

        $companyId = (int) $configured;

        if ($companyId < 1) {
            return null;
        }

        return DB::table('companies')->where('id', $companyId)->value('id');
    }
};
