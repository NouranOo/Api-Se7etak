<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;


class City extends Model  
{
    use Notifiable;
     protected $table="cites";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'city_ar','city_en'
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
        return $this->belongsTo('App\Models\Customer_Information','city_id');
    }

   
   



}
