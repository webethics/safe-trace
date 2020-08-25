<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
     
	  protected $dates = [
        'created_at',
        'updated_at',
    ]; 
     protected $fillable = [
        'report_id',
        'sender_id',
        'reciever_id',
        'comment',
        'request_status',

    ];
	
	public function reports()
    {
        return $this->belongsTo('App\Models\Report');
    }
}
