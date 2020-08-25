<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use SoftDeletes;

    protected $table = 'notification';
    //public $timestamps = false;
     protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ]; 
    protected $fillable = [
        'sender_id',
        'reciever_id',
        'requested_id',
        'notification_id',
        'status',
    ];
	
	public function notificationMessage(){
		return $this->hasOne('App\Models\NotificationMessage','id','notification_id');
	}
	
	public function requests(){
		return $this->hasOne('App\Models\RequestCase','id','requested_id');
	}
	public function sender(){
		return $this->hasOne('App\Models\User','id','sender_id');
	}
}
