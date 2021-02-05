<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class LeadUser extends Model
{

    protected $fillable = [
        'name','email','mobile','meta','lead_status','website','payment_status','date','time'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
