<?php
namespace App\Repositories;

use App\Helpers\ApiResponse;
use App\helpers\FCMHelper;
use App\Helpers\GeneralHelper;
use App\Interfaces\UserInterface;
use App\Models\Customer_Information;
use App\Models\Area;
use App\Models\City;
use App\Models\Item;
use App\Models\Customer_Order;
use App\Models\Customer_Order_Detail;

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;


class UserRepository implements UserInterface
{

    public $apiResponse;
    public $generalhelper;
    public function __construct(GeneralHelper $generalhelper, ApiResponse $apiResponse)
    {
        $this->generalhelper = $generalhelper;

        $this->apiResponse = $apiResponse;

    }
    /** auth section
     *
     */
    public function Register($data)
    {

        try {
            $data['ApiToken'] = base64_encode(str_random(40));
            $data['Password'] = app('hash')->make($data['Password']);
            $Customer_Information = new Customer_Information();
            $Customer_Information->customer_email = $data['customer_email'];
            $Customer_Information->password=$data['Password'];
            if($data['UserName']){
                $Customer_Information->customer_name = $data['UserName'];
            }
            if($data['Mobile']){
                $Customer_Information->customer_mobile = $data['Mobile'];
            }
            if($data['Address']){
                $Customer_Information->customer_address = $data['Address'];
            }
            $Customer_Information->ApiToken = $data['ApiToken']; 
            $Customer_Information->late = $data['late']; 
            $Customer_Information->lang = $data['lang']; 
            $Customer_Information->city_id = $data['city_id']; 
            $Customer_Information->area_id = $data['area_id']; 

            $Customer_Information->save();
            
            
        } catch (Exception $ex) {
            return $this->apiResponse->setError("Missing data ", $ex)->setData();
        }
        return $this->apiResponse->setSuccess("Customer created succesfully")->setData($Customer_Information);

    }

    public function Login($data)
    {
        try {
            $customer = Customer_Information::where('customer_email', $data['Email'])->first();

            if ($customer) {
                $check = Hash::check($data['Password'], $customer->password);
                if ($check) {
                
                    try {
                        $customer->update(['ApiToken' => base64_encode(str_random(40))]);
                        $customer->save();
                    } catch (\Illuminate\Database\QueryException $ex) {
                        return $this->apiResponse->setError($ex->getMessage())->setData();
                    }
                    return $this->apiResponse->setSuccess("Login Successfuly")->setData($customer);

                }else {
                    return $this->apiResponse->setError("Password not Correct!")->setData();
                }

            } else {
                return $this->apiResponse->setError("Your Email not found!")->setData();
            }
        }catch (Exception $ex) {
            return $this->apiResponse->setError("Missing data ", $ex)->setData();
        }

    }
   

    public function LogOut($data)
    {
        try{
            $customer = Customer_Information::where('ApiToken', $data['ApiToken'])->first();

            if ($customer) {

                try {
                    $customer->update(['ApiToken' => "NULL"]);
                    $customer->save();
                } catch (\Illuminate\Database\QueryException $ex) {
                    return $this->apiResponse->setError($ex->getMessage())->setData();
                }
                return $this->apiResponse->setSuccess("LogOut Successfuly")->setData();

            } else {
                return $this->apiResponse->setError("UnAuthorized! (invalid ApiToken)")->setData();
            }
        }catch (Exception $ex) {
            return $this->apiResponse->setError("Missing data ", $ex)->setData();
        }

    }
   
    public function LoginFacebook($data)
    {
        try{

        
            $fbId = Customer_Information::where('FacbookId', $data['FacbookId'])->first();

            if ($fbId) {
                return $this->apiResponse->setSuccess("User Found ")->setData($fbId);
            } else {
                $data['ApiToken'] = base64_encode(str_random(40));
                try {
                    $customer = Customer_Information::create($data);
                    $customer->FacbookId = $data['FacbookId'];
                    $customer->save();
                } catch (Exception $ex) {
                    return $this->apiResponse->setError("Missing data ", $ex)->setData();
                }

                return $this->apiResponse->setSuccess("Customer Created successfuly ")->setData($customer);
            }
        }catch (Exception $ex) {
            return $this->apiResponse->setError("Missing data ", $ex)->setData();
        }
    }

    public function getAllAreas($data)
    {
        try{
            $areas= Area::all();

        }catch (Exception $ex) {
            return $this->apiResponse->setError("Missing data ", $ex)->setData();
        }
        return $this->apiResponse->setSuccess("Areas fitched successfuly ")->setData($areas);
        
    }
    public function getAllCities($data)
    {
        try{
            $cities= City::all();

        }catch (Exception $ex) {
            return $this->apiResponse->setError("Missing data ", $ex)->setData();
        }
        return $this->apiResponse->setSuccess("Cities fitched successfuly ")->setData($cities);
        
    }
    public function getAllItems($data)
    {
        try{
            $items= Item::all();

        }catch (Exception $ex) {
            return $this->apiResponse->setError("Missing data ", $ex)->setData();
        }
        return $this->apiResponse->setSuccess("Medicines fitched successfuly ")->setData($items);
        
    }
    public function searchItems($data)
    {
        try{
           $key = $data['key'];
           $items =  Item::where('name_arb', 'LIKE', "%{$key}%")->orwhere('name_eng', 'LIKE', "%{$key}%")->orwhere('description_arb', 'LIKE', "%{$key}%")->orwhere('description_eng','LIKE',"%{$key}%")->get();
      
        }catch (Exception $ex) {
            return $this->apiResponse->setError("Missing data ", $ex)->setData();
        }
        return $this->apiResponse->setSuccess("Medicines fitched successfuly ")->setData($items);
        
    }
    public function AddOrder($data)
    {
        try{
            $customer = GeneralHelper::getcurrentUser();
            $Customer_Order = new Customer_Order();
            if($customer){
                $Customer_Order->customer_id = $customer->id;
                $Customer_Order->total_cost=$data['total_cost'];
                $Customer_Order->status="Pending";
                
                // if($data['prescription_image']){
                //     $Customer_Order->prescription_image = $data['prescription_image'];
                // }
                // if($data['IsPrescription']){
                //     $Customer_Order->IsPrescription = $data['IsPrescription'];
                // }
                if($data['feedback']){
                    $Customer_Order->feedback = $data['feedback'];
                }
                if($data['notes']){
                    $Customer_Order->notes = $data['notes'];
                }
                $Customer_Order->save();
                // dd($Customer_Order->id);
                // $customer_order_id=$Customer_Order->id;
            
        //------------Create Order_Details Of Cart------------------
                $Customer_Order_Detail = new Customer_Order_Detail();
                $object = json_decode($data['list'],true);
                // dd($object );
                foreach($object as $obj){
                    $Customer_Order_Detail->product_id=$obj['product_id'];
                    $Customer_Order_Detail->estimate_cost=$obj['estimate_cost'];
                    $Customer_Order_Detail->customer_id=$customer->id;
                    $Customer_Order_Detail->customer_order_id=$Customer_Order->id;
                    $Customer_Order_Detail->quantity=$obj['quantity'];
                    $Customer_Order_Detail->save();
                    // dd($Customer_Order_Detail);
                    // dd($Customer_Order_Detail);
                }
        
        }
      
        }catch (Exception $ex) {
            return $this->apiResponse->setError("Missing data ", $ex)->setData();
        }
        return $this->apiResponse->setSuccess("Added Order successfuly ")->setData(['Customer_Order'=>$Customer_Order,
                                                                                    'Customer_Order_Detail'=>$Customer_Order_Detail]);
        
    }

    public function AddOrderByPrescriptionPhoto($data)
    {
        try{
            $customer = GeneralHelper::getcurrentUser();
            $Customer_Order = new Customer_Order();
            
            if($customer){
                $Customer_Order->customer_id = $customer->id;
               
                $Customer_Order->status="Pending";
                
                if($data['prescription_image']){
                    $Customer_Order->prescription_image = $data['prescription_image'];
                    $Customer_Order->IsPrescription = 1;
                }

                if($data['feedback']){
                    $Customer_Order->feedback = $data['feedback'];
                }
                if($data['notes']){
                    $Customer_Order->notes = $data['notes'];
                }
                $Customer_Order->save();
            }
            

         
      
        }catch (Exception $ex) {
            return $this->apiResponse->setError("Missing data ", $ex)->setData();
        }
        return $this->apiResponse->setSuccess("Added Order successfuly ")->setData($Customer_Order);
        
    }

   
    
}

