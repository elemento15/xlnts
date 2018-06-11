<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VisitAttribute extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'visit_id','attribute_id','left_value','right_value'
    ];

    public $timestamps = false;

    public function attribute()
    {
        return $this->belongsTo('App\Attribute');
    }
}
