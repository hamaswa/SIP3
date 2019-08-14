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
class Queuestat extends Model
{

    public $table = 'queuestats';
   
    public $fillable = [
        'queue', 'incall', 'answer', 'abandon', 'created_at'
    ];
}
