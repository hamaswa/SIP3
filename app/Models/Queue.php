<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Queue
 * @package App\Models
 * @version Jan 02, 2019, 9:51 am UTC
 *
 * @property integer id
 * @property integer queue
 * @property  queue_description
 */
class Queue extends Model
{

    public $table = 'queue';

    protected $fillable = [
        'queue', 'queue_description','user_id'
    ];


    public function user()
	{
		return $this->belongTo('App\Models\User');
	}

}
