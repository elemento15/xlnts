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
        'visit_id', 'type', 'attribute_id', 'value'
    ];

    public $timestamps = false;

    public function attribute()
    {
        return $this->belongsTo('App\Attribute');
    }
}
