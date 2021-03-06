<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VisitType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'code'
    ];

    public static function findByCode($code)
    {
    	$type = self::where('code', $code)->first();
    	return ($type) ? $type : false;
    }
}
