<?php

namespace App\Services;

use App\Models\Document;
use Illuminate\Support\Facades\DB;

final class DocumentNumberGenerator
{
    private const PREFIXES = [
        'quote' => 'COT',
        'invoice' => 'FAC',
        'bill' => 'CC',
    ];

    public function next(string $type): string
    {
        $prefix = self::PREFIXES[$type] ?? strtoupper(substr($type, 0, 3));

        $lastNumber = DB::transaction(function () use ($type) {
            $last = Document::where('type', $type)
                ->lockForUpdate()
                ->orderByDesc('id')
                ->value('doc_number');

            if ($last && preg_match('/(\d+)$/', $last, $m)) {
                return (int) $m[1];
            }

            return 0;
        });

        return $prefix.'-'.str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    }
}
