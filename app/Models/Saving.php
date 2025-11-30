<?php

namespace App\Models;

use App\Models\Concerns\CheckProperUser;
use App\Models\Concerns\HasUuid;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Saving extends Model
{
    use SoftDeletes, HasUuid, CheckProperUser;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'bank_account_id',
        'saved_at',
        'amount',
        'description',
        'transfered_amount',
        'transfer_reason',
    ];

    public function bank_account()
    {
        return $this->belongsTo(BankAccount::class);
    }
}
