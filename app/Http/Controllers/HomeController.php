<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use App\Models\Staff;

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
        $customersPengajuanLimit = Customer::where('status_limit_pembelian', 7)->get();

        return view('supervisor.dashboard',[
          'customersPengajuanLimit' => $customersPengajuanLimit,
          'role' => $role
      ]);
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


    public function lihatProfil(){
        $data = '';
        if(auth()->user()->tabel == 'staffs'){
            $data = Staff::where('id','=',auth()->user()->id_users)->first();
            return view($data->linkStaffRole->nama.'/profil.index',[
                'data' => $data
            ]);
        }
        else{
            $data = Customer::where('id','=',auth()->user()->id_users)->first();
            return view('customer/profil.index',[
                'data' => $data
            ]);
        }        
    }

    public function lihatPassword(){
        if(auth()->user()->tabel == 'staffs'){
            return view(auth()->user()->linkStaff->linkStaffRole->nama.'/profil.ubahpasswordlama');
        }
        else{
            return view('customer/profil.ubahpasswordlama');
        }
    }
}
