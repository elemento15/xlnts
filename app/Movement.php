<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mov_date','movement_concept_id','type','comments'
    ];

    public function movement_concept()
    {
        return $this->belongsTo('App\MovementConcept');
    }

    public function products()
    {
        return $this->hasMany('App\MovementProduct');
    }

    public function updateStock()
    {
        foreach ($this->products as $key => $item) {
            $item->product->updateStock($this->type, $item->quantity);
        }
    }

    public function reverseStock()
    {
        $type = ($this->type == 'E') ? 'S' : 'E';

        foreach ($this->products as $key => $item) {
            $item->product->updateStock($type, $item->quantity);
        }
    }
}
