<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MovementProduct extends Model
{
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'movement_id','product_id','quantity'
    ];

    public $timestamps = false;

    public function movement()
    {
        return $this->belongsTo('App\Movement');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
