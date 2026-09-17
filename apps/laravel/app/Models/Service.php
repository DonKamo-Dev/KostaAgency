<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceFactory> */
    use HasFactory;

    protected $fillable = ['name', 'description', 'unit_price'];

    public function documentItems()
    {
        return $this->hasMany(DocumentItem::class);
    }
}
