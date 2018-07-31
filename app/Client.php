<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'rfc', 'email', 'phone', 'mobile', 'birthday', 'comments'
    ];

    public function visits()
    {
        return $this->hasMany('App\Visit');
    }

    public function sales()
    {
        return $this->hasMany('App\Sale');
    }
}
