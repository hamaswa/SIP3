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
class Realtime extends Model
{

    public $table = 'realtimes';
   
    public $fillable = [
        'extension', 'ext_status', 'ext_status_text', 'info', 'created_at'
    ];
}
