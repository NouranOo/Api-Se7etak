<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;


class Customer_Order extends Model  
{
    use Notifiable;
     protected $table="customer_orders";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id','prescription_image','status','feedback','total_cost','seen','notes',
        
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
        return $this->belongsTo('App\Models\Customer_Information','customer_id');
    }
    public function Customer_Order_Details(){
        return $this->hasMany('App\Models\Customer_Order_Detail','customer_order_id');
    }

   
   



}
