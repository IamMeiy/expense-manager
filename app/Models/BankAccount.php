<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\CheckProperUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Database\Eloquent\Builder;

class BankAccount extends Model
{
    use SoftDeletes, HasUuid, CheckProperUser;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'account_type',
        'user_id',
        'bank_name',
        'account_number',
    ];

    public function savings()
    {
        return $this->hasMany(Saving::class);
    }
}
