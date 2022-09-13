<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    protected $fillable = [
        'betting_id',
        'title',
        'date_start',
        'date_end',
    ];

    public function betting()
    {
        return $this->belongsTo('App\Betting');
    }

    // seguir padrão de acessores get+nome+Attribute
    public function getBettingTitleAttribute()
    {
        return $this->betting->title;
    }
}
