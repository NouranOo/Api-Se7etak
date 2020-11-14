<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;


class Area extends Model  
{
    use Notifiable;
     protected $table="areas";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name_ar','name_en'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
     
    ];
    protected $casts = [
    
    ];

    public function Customer_Information(){
        return $this->belongsTo('App\Models\Customer_Information','area_id');
    }

   
   



}
