<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Preserve historical documents by renumbering only later duplicates
        // before adding the database-level uniqueness guarantee.
        $duplicateGroups = DB::table('documents')
            ->select(['id', 'company_id', 'type', 'doc_number'])
            ->whereNotNull('doc_number')
            ->orderBy('company_id')
            ->orderBy('type')
            ->orderBy('doc_number')
            ->orderBy('id')
            ->get()
            ->groupBy(fn (object $document) => implode("\0", [
                $document->company_id,
                $document->type,
                $document->doc_number,
            ]))
            ->filter(fn ($documents) => $documents->count() > 1);

        foreach ($duplicateGroups as $duplicates) {
            $first = $duplicates->first();
            $numbers = DB::table('documents')
                ->where('company_id', $first->company_id)
                ->where('type', $first->type)
                ->pluck('doc_number');

            preg_match('/^(.*?)(\d+)$/', $first->doc_number, $matches);
            $prefix = $matches[1] ?? $first->doc_number.'-';
            $width = isset($matches[2]) ? strlen($matches[2]) : 4;
            $next = $numbers
                ->map(fn ($number) => preg_match('/(\d+)$/', $number, $match) ? (int) $match[1] : 0)
                ->max() + 1;

            foreach ($duplicates->skip(1) as $duplicate) {
                do {
                    $replacement = $prefix.str_pad((string) $next++, $width, '0', STR_PAD_LEFT);
                } while ($numbers->contains($replacement));

                DB::table('documents')->where('id', $duplicate->id)->update([
                    'doc_number' => $replacement,
                ]);

                $numbers->push($replacement);
            }
        }

        Schema::table('documents', function (Blueprint $table) {
            $table->unique(['company_id', 'type', 'doc_number']);
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropUnique(['company_id', 'type', 'doc_number']);
        });
    }
};
