<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventLog extends Model
{
      protected $table = 'event_log';
	  
	 public function auditlogs()
    {
		//echo "gf"; die;
        return $this->hasMany('\App\Models\AuditLog');
    }
}
