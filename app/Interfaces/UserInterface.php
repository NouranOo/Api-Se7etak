<?php

namespace App\Interfaces;

interface UserInterface
{

    public function Register($data);
    public function Login($data);
    public function LogOut($data);
    public function LoginFacebook($data);
    public function getAllAreas($data);
    public function getAllCities($data);
    public function getAllItems($data);
    public function searchItems($data);
    public function AddOrder($data);
    public function AddOrderByPrescriptionPhoto($data);
    
    
    
    



}
