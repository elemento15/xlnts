<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleProductAttribute extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id'
    ];

    public $timestamps = false;

    public function sale_product()
    {
        return $this->belongsTo('App\SaleProduct');
    }
}
