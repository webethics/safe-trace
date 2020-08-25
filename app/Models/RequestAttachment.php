<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestAttachment extends Model
{
	 
	 protected $table = 'request_attachment';
	  
     protected $fillable = [
        'report_id',
        'filename',
        'original_name',

    ];
	
	public function reports()
    {
        return $this->belongsTo('App\Models\Report');
    }
}
