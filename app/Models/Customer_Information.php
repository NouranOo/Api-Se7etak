<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;


class Customer_Information extends Model  
{
    use Notifiable;
     protected $table="customer_information";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_name','customer_address','customer_mobile','customer_email','status','late',
        'lang','city_id','area_id','user_id','pharmacy_id', 'payment_id', 'insurance_number','is_primecost','start_date',
        'Current_withdrawals','Max_withdrawals','national_id','ApiToken','Token','FacbookId'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];
    protected $casts = [
    
    ];

    public function Area(){
        return $this->hasOne('App\Models\Area','area_id');
    }
    public function City(){
        return $this->hasOne('App\Models\City','city_id');
    }
    public function Customer_Orders(){
        return $this->hasMany('App\Models\Customer_Order','customer_id');
    }
    public function Customer_Order_Details(){
        return $this->hasMany('App\Models\Customer_Order_Detail','customer_id');
    }

   
   



}
