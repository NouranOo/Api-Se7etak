<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;


class Item extends Model  
{
    use Notifiable;
     protected $table="items";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name_arb','name_eng','description_arb','description_eng','notes','photo','isFreezed',
        'isExpiring','label','local_barcode','international_barcode','commerical_barcode', 'fixed_Price', 'tax','discount','extra_discount',
        'total_cost','unit','pack','supplier_id','type_id','category_id'
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
    public function Customer_Order_Details(){
        return $this->hasMany('App\Models\Customer_Order_Detail','product_id');
    }

   
   



}
