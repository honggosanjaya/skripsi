<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Order;
use App\Models\Retur;
use App\Models\Item;
use App\Models\Vehicle;
use App\Models\Event;
use App\Models\History;
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
        $item = Item::count();
        $item_aktif = Item::where('status', 10)->count();
        $vehicle = Vehicle::count();
        $customer = Customer::count();
        $customer_aktif = Customer::where('status', 3)->count();

        $notifikasi = [];
        $notifikasi['trip'] = 
        Customer::with(['latestLinkTrip'])
            ->whereRaw('NOW() >= DATE_ADD(updated_at, INTERVAL + durasi_kunjungan DAY)')
            ->get();
        $notifikasi['retur'] = 
        Retur::where('status','13')
            ->with(['linkInvoice'])->get();

        $notifikasi['order_diajukan'] = 
        Order::whereHas('linkOrderTrack', function($q){
            $q->where('status',20);
        })->with(['linkOrderTrack'])->get();

        $notifikasi['order_selesai'] = 
        Order::whereHas('linkOrderTrack', function($q){
            $q->where('status',23)->where('id_staff_pengonfirmasi',auth()->user()->id_users);
        })->with(['linkOrderTrack'])->get();
        // dd($notifikasi);
       
        return view('administrasi/dashboard',[
          'role' => $role,
          'data' => [
            'jumlah_item' => $item,
            'jumlah_item_aktif' => $item_aktif,
            'jumlah_kendaraan' => $vehicle,
            'jumlah_customer' => $customer,
            'jumlah_customer_aktif' => $customer_aktif,
          ]
        ]);
    }
    public function indexShipper()
    {
        $role='indexShipper';
        return view('shipper/dashboard',compact('role'));
    }
    public function indexCustomer()
    {
      $customer = Customer::where('id', auth()->user()->id_users)->first();
      $event = Event::where('status', 16)->get();


      $kode_customers=Order::where('id_customer', auth()->user()->id_users)
      ->whereHas('linkOrderTrack',function($q) {
        $q->where('waktu_diteruskan', null);
      })
      ->with(['linkOrderTrack'])      
      ->get();

      $histories = History::where('id_customer', auth()->user()->id_users)->get();

        $role='indexCustomer';
        return view('customer.dashboard',[
          'role' => $role,
          'customer' => $customer,
          'events' => $event,
          'kode_customers' => $kode_customers,
          'histories' => $histories
        ]);
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
            $order_diajukan=Order::where('id_customer', auth()->user()->id_users)
            ->whereHas('linkOrderTrack',function($q) {
              $q->where('status', 19)->orWhere('status', 20);
            })
            ->with(['linkOrderTrack'])
            ->count();

            $order_dikonfirmasi=Order::where('id_customer', auth()->user()->id_users)
            ->whereHas('linkOrderTrack',function($q) {
              $q->where('status', 21);
            })
            ->with(['linkOrderTrack'])
            ->count();

            $order_dikirim=Order::where('id_customer', auth()->user()->id_users)
            ->whereHas('linkOrderTrack',function($q) {
              $q->where('status', 22);
            })
            ->with(['linkOrderTrack'])
            ->count();

            $order_diterima=Order::where('id_customer', auth()->user()->id_users)
            ->whereHas('linkOrderTrack',function($q) {
              $q->where('status', 23)->orWhere('status', 24);
            })
            ->with(['linkOrderTrack'])
            ->count();

            return view('customer.profil.index',[
                'data' => $data,
                'order' => [
                  'diajukan' => $order_diajukan,
                  'dikonfirmasi' => $order_dikonfirmasi,
                  'dikirim' => $order_dikirim,
                  'diterima' => $order_diterima
                ]
            ]);
        }        
    }

    public function lihatDetailProfil(){
        $data = Customer::where('id','=',auth()->user()->id_users)->first();
        return view('customer/profil.detailprofil',[
            'data' => $data
        ]);                
    }

    public function lihatPesanan(Customer $customer){
        $diajukans = Order::whereHas('linkOrderTrack', function($q){
            $q->where('status',19)->orWhere('status', 20);
        })->where('id_customer','=',$customer->id)->with(['linkOrderTrack','linkInvoice','linkOrderItem.linkItem'])
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
             
        return view('customer/profil.detailpesanan',[
            'diajukans' => $diajukans,
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
