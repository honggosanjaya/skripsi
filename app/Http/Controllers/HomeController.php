<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function indexOwner()
    {
        $role='indexOwner';
        return view('owner/dashboard',compact('role'));
    }
    public function indexSupervisor()
    {
        $role='indexSupervisor';
        return view('supervisor/dashboard',compact('role'));
    }
    public function indexSalesman()
    {
        $role='indexSalesman';
        return view('salesman/dashboard',compact('role'));
    }
    public function indexAdministrasi()
    {
        $role='indexAdministrasi';
        return view('administrasi/dashboard',compact('role'));
    }
    public function indexShipper()
    {
        $role='indexShipper';
        return view('shipper/dashboard',compact('role'));
    }
    public function indexCustomer()
    {
        $role='indexCustomer';
        return view('customer/dashboard',compact('role'));
    }
}
