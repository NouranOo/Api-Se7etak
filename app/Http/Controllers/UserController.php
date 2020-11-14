<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\GeneralHelper;
use App\Http\Controllers\Controller;
use App\Interfaces\UserInterface;
use App\Models\Customer_Information;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use validator;

class UserController extends Controller
{

    public $user;
    public $apiResponse;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserInterface $user, ApiResponse $apiResponse)
    {
        $this->user = $user;
        $this->apiResponse = $apiResponse;
    }

    /*
     * Auth section
     */
    public function Register(Request $request)
    {
        $rules = [
            'ApiKey' => 'required',
            'customer_email' => 'required|unique:customer_information',
            'Password' => 'required',
            'UserName' => '',
            'Mobile' => '',
            'Address' => '',
            'late' => 'required',
            'lang' => 'required',
            'city_id' => 'required',
            'area_id' => 'required',


        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send();

        }
        $api_key = env('APP_KEY');
        if ($api_key != $request->ApiKey) {
            return $this->apiResponse->setError("Unauthorized!")->send();
        }

        $data = $request->all();
        $result = $this->user->Register($data);
        return $result->send();
    }

    public function Login(Request $request)
    {
        $rules = [
            'Email' => 'required',
            'Password' => 'required',
            'ApiKey' => 'required',
            'Token' => '',

        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send(); //new way to send responce

        }
        $api_key = env('APP_KEY');
        if ($api_key != $request->ApiKey) {
            return $this->apiResponse->setError("Unauthorized!")->send();
        }
        $data = $request->all();
        $result = $this->user->Login($request->all());
        return $result->send();

    }
  
    public function LogOut(Request $request)
    {
        $rules = [

            'ApiKey' => 'required',
            'ApiToken' => 'required',

        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send(); //new way to send responce

        }
        $api_key = env('APP_KEY');
        if ($api_key != $request->ApiKey) {
            return $this->apiResponse->setError("Unauthorized!")->send();
        }
        $result = $this->user->LogOut($request->all());
        return $result->send();

    }
   
   
    public function LoginFacebook(Request $request) 

    {
        $rules = [
            'FacbookId' => 'required',

        ];
        $fbId = Customer_Information::where('FacbookId', $request->FacbookId)->first();

        if (!is_null($fbId)) {

            $rules = [

                'ApiKey' => 'required',
                'Token' => '',

            ];

        } else {
            $rules = [
                
                'ApiKey' => 'required',
                'Token' => 'required',
                'Email' => '',
                'UserName' => ' ',
                'Mobile' => '',
                'Address' => '',
                'late' => 'required',
                'lang' => 'required',
                'city_id' => 'required',
                'area_id' => 'required',
               
            ];

        }

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send(); //new way to send responce

        }
        $api_key = env('APP_KEY');
        if ($api_key != $request->ApiKey) {
            return $this->apiResponse->setError("Unauthorized!")->send();  
        }
        $data = $request->all();
        $result = $this->user->LoginFacebook($data);
        return $result->send();
    }
    public function getAllAreas(Request $request)
    {
        $rules = [

            'ApiKey' => 'required',
            'ApiToken' => 'required',

        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send(); //new way to send responce

        }
        
        $result = $this->user->getAllAreas($request->all());
        return $result->send();
    }
    public function getAllCities(Request $request)
    {
        $rules = [

            'ApiKey' => 'required',
            'ApiToken' => 'required',

        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send(); //new way to send responce

        }
      
        $result = $this->user->getAllCities($request->all());
        return $result->send();
    }
    public function getAllItems(Request $request)
    {
        $rules = [

            'ApiKey' => 'required',
            'ApiToken' => 'required',

        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send(); //new way to send responce

        }
      
        $result = $this->user->getAllItems($request->all());
        return $result->send();
    }
    public function searchItems(Request $request)
    {
        $rules = [

            'ApiKey' => 'required',
            'ApiToken' => 'required',
            'key' => 'required',


        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send(); //new way to send responce

        }
      
        $result = $this->user->searchItems($request->all());
        return $result->send();
    }
    public function AddOrder(Request $request)
    {
        $rules = [

            'ApiKey' => 'required',
            'ApiToken' => 'required',
            'list' => 'required',
            // 'customer_id'=>'required',
            // 'prescription_image'=>'',
            // 'IsPrescription'=>'',
            'feedback'=>'',
            'total_cost'=>'required',
            'notes'=>'',


        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send(); //new way to send responce

        }
        $data = $request->except('prescription_image');

        if ($request->hasFile('prescription_image')) {

            $file = $request->file("prescription_image");
            $filename = str_random(6) . '_' . time() . '_' . $file->getClientOriginalName();
            $path = 'ProjectFiles/OrderPhotos';
            $file->move($path, $filename);
            $data['prescription_image'] = $path . '/' . $filename;
        }
        $result = $this->user->AddOrder($data);
        return $result->send();
    }
    public function AddOrderByPrescriptionPhoto(Request $request)
    {
        $rules = [

            'ApiKey' => 'required',
            'ApiToken' => 'required',
            'prescription_image'=>'',
            'feedback'=>'',
            'notes'=>'',

            
        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send(); //new way to send responce

        }
        $data = $request->except('prescription_image');

        if ($request->hasFile('prescription_image')) {

            $file = $request->file("prescription_image");
            $filename = str_random(6) . '_' . time() . '_' . $file->getClientOriginalName();
            $path = 'ProjectFiles/OrderPhotos';
            $file->move($path, $filename);
            $data['prescription_image'] = $path . '/' . $filename;
        }
        $result = $this->user->AddOrderByPrescriptionPhoto($data);
        return $result->send();
    }
    
    
}
