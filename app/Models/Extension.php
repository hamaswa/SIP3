<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Extension
 * @package App\Models
 * @version May 11, 2018, 7:11 am UTC
 *
 * @property integer user_id
 * @property integer sub_user_id
 * @property  did_no
 * @property  extension_no
 */
class Extension extends Model
{

    public $table = 'extensions';
    


    public $fillable = [
        'user_id',
        'extension_no'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required',
        'extension_no' => 'required'
    ];

    public function User()
	{
		return $this->belongsTo('App\Models\User', 'user_id' , 'id');
	}

}
