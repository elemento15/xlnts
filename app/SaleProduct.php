<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleProduct extends Model
{
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id'
    ];

    public function sale()
    {
        return $this->belongsTo('App\Sale');
    }

    public function attributes()
    {
        return $this->hasMany('App\SaleProductAttribute');
    }
}
