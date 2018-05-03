<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id', 'quantity'
    ];

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public static function findByProduct($product_id)
    {
    	$stock = self::where('product_id', $product_id)->first();
    	return ($stock) ? $stock : false;
    }
}
