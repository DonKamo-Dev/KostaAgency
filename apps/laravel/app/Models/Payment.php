<?php

namespace App\Models;

use Database\Factories\PaymentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /** @use HasFactory<PaymentFactory> */
    use HasFactory;

    protected $fillable = [
        'document_id', 'date', 'amount', 'method', 'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
