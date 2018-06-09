<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description', 'type', 'group_id', 'price', 'has_attributes', 'comments'
    ];

    public function group()
    {
        return $this->belongsTo('App\Group');
    }

    public function attributes()
    {
        return $this->hasMany('App\AttributeProduct');
    }

    public function stock()
    {
        return $this->hasOne('App\Stock');
    }

    public function updateStock($type, $quantity)
    {
        $quantity = ($type == 'E') ? $quantity : ($quantity * -1);

        if ($this->type == 'P') {
            if ($stock = Stock::findByProduct($this->id)) {
                $stock->quantity += $quantity;
                $stock->save();
            } else {
                Stock::create([
                    'product_id' => $this->id,
                    'quantity' => $quantity
                ]);
            }
        }
    }
}
