<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sale_date' ,'client_id', 'has_invoice', 'iva_percent', 'comments'
    ];


    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    public function sale_products()
    {
        return $this->hasMany('App\SaleProduct');
    }
}
