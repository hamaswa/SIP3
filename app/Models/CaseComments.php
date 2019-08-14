<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseComments extends Model
{
    protected $fillable = [
        'user_id','case_id','comment'
    ];


    public function user(){
        return $this->belongsTo("App\Models\User","user_id");
    }
}
