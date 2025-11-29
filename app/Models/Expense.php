<?php

namespace App\Models;

use App\Models\Concerns\CheckProperUser;
use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use SoftDeletes, HasUuid, CheckProperUser;

    public $incrementing = false;
    protected $keyType = 'string';
    
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
