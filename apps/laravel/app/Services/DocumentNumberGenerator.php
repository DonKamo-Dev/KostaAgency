<?php

namespace App\Services;

use App\Models\Document;
use Closure;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

final class DocumentNumberGenerator
{
    private const PREFIXES = [
        'quote' => 'COT',
        'invoice' => 'FAC',
        'bill' => 'CC',
    ];

    private const STARTS = [
        'quote' => 2400,
        'invoice' => 5800,
        'bill' => 0,
    ];

    public function next(string $type): string
    {
        return DB::transaction(fn () => $this->nextLocked($type));
    }

    /**
     * Generate and persist a document atomically, retrying a concurrent
     * document-number collision against the database unique constraint.
     */
    public function create(string $type, Closure $callback, int $attempts = 3): Document
    {
        for ($attempt = 1; $attempt <= $attempts; $attempt++) {
            try {
                return DB::transaction(fn () => $callback($this->nextLocked($type)));
            } catch (QueryException $exception) {
                if ($attempt === $attempts || ! DB::connection()->causedByUniqueConstraint($exception)) {
                    throw $exception;
                }
            }
        }

        throw new \LogicException('Unable to generate a unique document number.');
    }

    private function nextLocked(string $type): string
    {
        $prefix = self::PREFIXES[$type] ?? strtoupper(substr($type, 0, 3));
        $last = Document::where('type', $type)
            ->lockForUpdate()
            ->orderByDesc('id')
            ->value('doc_number');

        $lastNumber = self::STARTS[$type] ?? 0;
        if ($last && preg_match('/(\d+)$/', $last, $matches)) {
            $lastNumber = (int) $matches[1];
        }

        return $prefix.'-'.str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    }
}
