<?php

namespace App\Models;

use Database\Factories\ClientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    /** @use HasFactory<ClientFactory> */
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
}
