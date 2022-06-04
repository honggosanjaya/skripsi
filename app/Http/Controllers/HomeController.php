<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Order;

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

    public function lihatPesanan(Customer $customer){
        $diajukanCustomer = Order::whereHas('linkOrderTrack', function($q){
            $q->where('status',19);
        })->where('id_customer','=',$customer->id)->with(['linkOrderTrack','linkInvoice','linkOrderItem.linkItem'])
        ->get();

        $diajukanSalesman = Order::whereHas('linkOrderTrack', function($q){
            $q->where('status',20);
        })->where('id_customer','=',$customer->id)->with(['linkOrderTrack','linkInvoice','linkOrderItem.linkItem','linkStaff'])
        ->get();

        $dikonfirmasiAdministrasi = Order::whereHas('linkOrderTrack', function($q){
            $q->where('status',21);
        })->where('id_customer','=',$customer->id)->with(['linkOrderTrack.linkStaffPengonfirmasi','linkInvoice','linkOrderItem.linkItem'])
        ->get();

        $dalamPerjalanan = Order::whereHas('linkOrderTrack', function($q){
            $q->where('status',22);
        })->where('id_customer','=',$customer->id)->with(['linkOrderTrack','linkInvoice','linkOrderItem.linkItem'])
        ->get();

        $telahSampai = Order::whereHas('linkOrderTrack', function($q){
            $q->where('status',23)->orWhere('status',24);
        })->where('id_customer','=',$customer->id)->with(['linkOrderTrack','linkInvoice','linkOrderItem.linkItem'])
        ->get();

        $ditolak = Order::whereHas('linkOrderTrack', function($q){
            $q->where('status',25);
        })->where('id_customer','=',$customer->id)->with(['linkOrderTrack','linkInvoice','linkOrderItem.linkItem'])
        ->get();      
        //dd($ditolak);
             
        return view('customer/profil.detailpesanan',[
            'diajukanCustomers' => $diajukanCustomer,
            'diajukanSalesmans' => $diajukanSalesman,
            'dikonfirmasiAdministrasis' => $dikonfirmasiAdministrasi,
            'dalamPerjalanans' => $dalamPerjalanan,
            'telahSampais' => $telahSampai,
            'ditolaks' => $ditolak
        ]);          
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
