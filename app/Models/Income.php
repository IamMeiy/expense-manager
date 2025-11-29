<?php

namespace App\Models;

use App\Models\Concerns\CheckProperUser;
use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Income extends Model
{
    use SoftDeletes, HasUuid, CheckProperUser;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['user_id', 'source', 'amount', 'description', 'received_at'];

    protected $casts = [
        'received_at' => 'date'
    ];
}
