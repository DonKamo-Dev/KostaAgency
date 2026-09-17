<?php

namespace App\Services;

use App\Models\Document;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

final class RegisterDocumentPayment
{
    public function handle(Document $document, array $data): Payment
    {
        return DB::transaction(function () use ($document, $data) {
            $doc = Document::where('id', $document->id)->lockForUpdate()->first();

            abort_unless(in_array($doc->status, ['pending', 'paid']), 422, 'No se pueden registrar pagos sobre un documento anulado.');

            $balance = (float) $doc->total - (float) $doc->paid;
            $amount  = (float) $data['amount'];

            if ($amount <= 0) {
                abort(422, 'El monto del pago debe ser mayor a cero.');
            }

            if ($amount > $balance) {
                abort(422, 'El pago excede el saldo pendiente.');
            }

            $payment = Payment::create([
                'document_id' => $doc->id,
                'date'        => $data['date'],
                'amount'      => $data['amount'],
                'method'      => $data['method'],
                'notes'       => $data['notes'] ?? '',
            ]);

            $newPaid  = (float) $doc->paid + $amount;
            $status   = $newPaid >= (float) $doc->total ? 'paid' : 'pending';

            $doc->update(['paid' => $newPaid, 'status' => $status]);

            return $payment;
        });
    }
}
