<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class BankAccount extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'account_type',
        'user_id',
        'bank_name',
        'account_number',
    ];

    public static function booted()
    {
        static::addGlobalScope('user', function (Builder $builder) {
            if (Auth::check()) {
                $builder->where('user_id', Auth::id());
            }
        });
    }
}
