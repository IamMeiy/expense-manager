<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'expense_type_id',
        'payment_method_id',
        'date',
        'payee',
        'amount',
        'description',
        'invoice'
    ];

    protected $casts = [
        'date' => 'date'
    ];
}
