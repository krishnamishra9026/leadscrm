<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\LeadUser;
use App\User;

class Comment extends Model
{
	protected $dates = ['deleted_at'];


	protected $fillable = ['user_id', 'lead_users_id', 'comment_type', 'comment'];

	public function leadUser()
	{
		return $this->belongsTo(LeadUser::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class,'user_id');
	}
}
