<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'min', 'max', 'steps', 'description', 'display_order'
    ];
}
