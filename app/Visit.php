<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'visit_date', 'visit_type_id', 'client_id'
    ];

    public function visit_type()
    {
        return $this->belongsTo('App\VisitType');
    }

    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    public function visit_attributes()
    {
        return $this->hasMany('App\VisitAttribute');
    }
}
