<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;


class Customer_Order_Detail extends Model  
{
    use Notifiable;
     protected $table="customer_order_details";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id','estimate_cost','customer_id','customer_order_id','quantity','packge','part',
        
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
    public function Items(){
        return $this->belongsTo('App\Models\Item','product_id');
    }
    public function Customer_Order(){
        return $this->belongsTo('App\Models\Customer_Order','customer_order_id');
    }

   
   



}
