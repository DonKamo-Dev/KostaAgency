<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    /** @use HasFactory<\Database\Factories\ExpenseFactory> */
    use HasFactory;

    protected $fillable = [
        'date', 'category', 'description', 'amount', 'receipt_path'
    ];

    protected $casts = [
        'date' => 'date',
    ];

}
