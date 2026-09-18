<?php

namespace App\Models;

use Database\Factories\DocumentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    /** @use HasFactory<DocumentFactory> */
    use HasFactory;

    protected $fillable = [
        'client_id', 'type', 'date', 'due_date',
        'subtotal', 'tax', 'total', 'paid', 'status',
        'related_doc_id', 'notes', 'pdf_path', 'doc_number',
    ];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(DocumentItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function relatedDocument()
    {
        return $this->belongsTo(Document::class, 'related_doc_id');
    }

    public function getBalanceAttribute()
    {
        return $this->total - $this->paid;
    }
}
