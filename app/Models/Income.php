<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Income extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'source', 'amount', 'description', 'received_at'];

    protected $casts = [
        'received_at' => 'date'
    ];
}
