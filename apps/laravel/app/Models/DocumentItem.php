<?php

namespace App\Models;

use Database\Factories\DocumentItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentItem extends Model
{
    /** @use HasFactory<DocumentItemFactory> */
    use HasFactory;

    protected $fillable = [
        'document_id', 'service_id', 'service_name',
        'description', 'quantity', 'unit_price', 'subtotal',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
