<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Report extends Model
{
	 use SoftDeletes;
	 public $timestamps = false;
	 
	  protected $dates = [
        'deleted_at',
    ]; 
     protected $fillable = [
        'request_id',
		'created_at',
		'zip_file_name',
		'zip_password'
    ];
	
	public function comments()
    {
        return $this->hasMany('App\Models\Comment')->orderBy('created_at', 'desc');
    }
	
	
	public function attachment()
    {
        return $this->hasMany('App\Models\RequestAttachment')->orderBy('created_at', 'desc');
    }
}
