<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Betting extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'current_round',// incrementar a cada rodada
        'value_result',
        'extra_value',
        'value_fee',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getUserNameAttribute()
    {
        return $this->user->name;
    }

    public function rounds()
    {
        return $this->hasMany('App\Round');
    }
}
