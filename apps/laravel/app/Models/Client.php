<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    /** @use HasFactory<\Database\Factories\ClientFactory> */
    use HasFactory;

    protected $fillable = ['name', 'tax_id', 'email', 'phone', 'address'];

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function recurringDocuments()
    {
        return $this->hasMany(RecurringDocument::class);
    }
}
