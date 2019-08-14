<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseManagement extends Model
{

    protected $fillable = [
        'subject','user_id',
        'name','taxi_no',
        'contact_no','incident_location',
        'pickup_point_a','pickup_location_b',
        'case_type','case_status',
        'comments'
    ];

    protected $table = 'case_managements';

    public function Comments(){
        return $this->hasMany("App\Models\CaseComments",'case_id');
    }

    public function user(){
        return $this->belongsTo("App\Models\User","user_id");
    }
}
