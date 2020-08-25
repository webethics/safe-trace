<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{

	protected $table = 'audit_log';

    protected $dates = [
        'created_at',
        'updated_at',
    ];
	 protected $fillable = [
        'event_log_id',
        'username',
        'request_id',
        'filename',
        'comment',
        'ipaddress',
		'attempted_password',
		'changed_fields',
	];
	
	public function eventlogs()
    {
		
        return $this->belongsTo('\App\Models\EventLog','event_log_id');
    }
	  
}
