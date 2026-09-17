<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // A document number is now unique for the whole private workspace.
        $seen = [];
        foreach (DB::table('documents')->select(['id', 'type', 'doc_number'])->orderBy('id')->cursor() as $document) {
            $key = $document->type.'|'.$document->doc_number;

            if (isset($seen[$key])) {
                DB::table('documents')->where('id', $document->id)->update([
                    'doc_number' => $document->doc_number.'-'.$document->id,
                ]);
            }

            $seen[$key] = true;
        }

        Schema::table('documents', function (Blueprint $table) {
            // MySQL may use this composite index to enforce the company FK.
            $table->dropForeign(['company_id']);
            $table->dropUnique(['company_id', 'type', 'doc_number']);
        });

        $this->dropCompanyColumn('clients', true);
        $this->dropCompanyColumn('services');
        $this->dropCompanyColumn('expenses');
        $this->dropCompanyColumn('documents', false, false);
        $this->dropCompanyColumn('ai_meta_quotes', true);
        $this->dropCompanyColumn('case_studies', true);

        Schema::table('documents', function (Blueprint $table) {
            $table->unique(['type', 'doc_number']);
        });

        Schema::dropIfExists('company_user');
        Schema::dropIfExists('companies');
    }

    public function down(): void
    {
        // Restoring tenant ownership cannot be done safely after consolidating its data.
    }

    private function dropCompanyColumn(
        string $tableName,
        bool $hasStandaloneIndex = false,
        bool $dropForeign = true,
    ): void
    {
        if (! Schema::hasColumn($tableName, 'company_id')) {
            return;
        }

        Schema::table($tableName, function (Blueprint $table) use ($hasStandaloneIndex, $dropForeign) {
            if ($dropForeign) {
                $table->dropForeign(['company_id']);
            }

            if ($hasStandaloneIndex) {
                $table->dropIndex(['company_id']);
            }

            $table->dropColumn('company_id');
        });
    }
};
