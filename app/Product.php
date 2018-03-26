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
}
