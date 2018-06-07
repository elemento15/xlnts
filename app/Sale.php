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
        'client_id', 'has_invoice', 'comments'
    ];


    public function client()
    {
        return $this->belongsTo('App\Client');
    }
}
