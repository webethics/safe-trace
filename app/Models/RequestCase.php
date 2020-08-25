<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestCase extends Model
{
    use SoftDeletes;

    protected $table = 'requests';
   // public $timestamps = false;
     protected $dates = [
        'created_at',
        'updated_at',
        'completed_at',
        'deleted_at',
    ]; 
    protected $fillable = [
        'name',
        'company',
        'case_number',
        'url',
        'priority',
        'data_archive',
        'social_media',
        'other_info',
		'requested_user_id',
		'status_changed_by'
		
       
    ];
}
