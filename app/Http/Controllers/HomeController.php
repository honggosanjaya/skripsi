<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Order;
use App\Models\Retur;
use App\Models\Item;
use App\Models\Vehicle;
use App\Models\Invoice;
use App\Models\Event;
use App\Models\History;
use App\Models\Reimbursement;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function indexOwner(){
        $role='indexOwner';
        return view('owner.dashboard',compact('role'));
    }

    public function indexSupervisor(){
        $role='indexSupervisor';
        $customersPengajuanLimit = Customer::where('status_limit_pembelian_enum', 0)->get();

        return view('supervisor.dashboard',[
          'customersPengajuanLimit' => $customersPengajuanLimit,
          'role' => $role
        ])->with('datadua', [
          'lihat_notif_spv' => true,
          'jml_pengajuan' => count($customersPengajuanLimit),
        ]);
    }

    public function indexSalesman(){
        $role='indexSalesman';
        return view('salesman/dashboard',compact('role'));
    }

    public function indexAdministrasi(Request $request){
        $role='indexAdministrasi';
        $item = Item::count();
        $item_aktif = Item::where('status_enum', '1')->count();
        $vehicle = Vehicle::count();
        $customer = Customer::count();
        $customer_aktif = Customer::where('status_enum', '1')->count();

        $notifikasi = [];
        $notifikasi['trip'] = 
        Customer::with(['latestLinkTrip'])
            ->whereRaw('NOW() >= DATE_ADD(updated_at, INTERVAL + durasi_kunjungan DAY)')
            ->get();

        $notifikasi['retur'] = 
         Retur::where('status_enum','0')->select('no_retur','id_invoice','id_customer','id_staff_pengaju', 'created_at','status_enum')        
            ->groupBy('no_retur','id_invoice','id_customer','id_staff_pengaju','created_at','status_enum')
            ->with(['linkCustomer','linkStaffPengaju','linkInvoice'])
            ->orderBy('no_retur','DESC')->get();

        $notifikasi['order_diajukan_salesman'] = 
        Order::whereHas('linkOrderTrack', function($q){
            $q->where('status_enum','1');
        })->with(['linkOrderTrack'])->get();

        $notifikasi['order_diajukan_customer'] = 
        Order::whereHas('linkOrderTrack', function($q){
          $q->where('status_enum','0');
        })->with(['linkOrderTrack'])->get();

        $notifikasi['order_selesai'] = 
        Order::whereHas('linkOrderTrack', function($q){
            $q->where('status_enum','4')->where('id_staff_pengonfirmasi',auth()->user()->id_users);
        })->with(['linkOrderTrack'])->get();

        $notifikasi['pengajuan_limit'] = Customer::where('status_limit_pembelian_enum', '!=', null)->get();

        $notifikasi['reimbursement'] = Reimbursement::where('status_enum', '0')->where('status_enum', '1')->get();

        $kendaraans = Vehicle::all();
        $today = date_create(now());
        $pajakVehicles = [];

        foreach($kendaraans as $kendaraan){
          if($kendaraan->tanggal_pajak){
            $date2 = date_create($kendaraan->tanggal_pajak);
            $diff = date_diff($today, $date2);
            if($diff->format("%R%a")<15){
              array_push($pajakVehicles, [
                'id_vehicle' => $kendaraan->id,
                'tanggal_pajak' => $kendaraan->tanggal_pajak,
                'nama_vehicle' => $kendaraan->nama,
                'dateDiff' => $diff->format("%R%a")
              ]);
            }
          }
        }

        $notifikasi['pajak_kendaraan'] = $pajakVehicles;

        $invoices = Invoice::whereHas('linkOrder',function($q){
          $q->whereHas('linkOrderTrack', function($q){
            $q->where('status_enum','4');
          });
        })->get();

        $jatuhTempo = [];

        foreach($invoices as $invoice){
          if($invoice->jatuh_tempo != null){
            $waktuSampai = $invoice->linkOrder->linkOrderTrack->waktu_sampai;
            $tanggalSampai = date("Y-m-d",strtotime($waktuSampai));
            $tanggalSampai2 = date_create($tanggalSampai);

            $interval = date_add($tanggalSampai2, date_interval_create_from_date_string($invoice->jatuh_tempo . " days"));
            $tanggalJatuhTempo = date_format($interval,"Y-m-d");

            $dateJatuhTempo = date_create($tanggalJatuhTempo);
            $diffJatuhTempo = date_diff($today, $dateJatuhTempo);

            if($diffJatuhTempo->format("%R%a")<15){
              array_push($jatuhTempo, [
                'id_invoice' => $invoice->id,
                'id_order' => $invoice->id_order,
                'nomor_invoice' => $invoice->nomor_invoice,
                'harga_total' => $invoice->harga_total,
                'tanggalJatuhTempo' => $tanggalJatuhTempo,
                'dateDiff' => $diffJatuhTempo->format("%R%a")
              ]);
            }
          }
        }

        $notifikasi['jatuh_tempo'] = $jatuhTempo;
        // dd($jatuhTempo);

        $request->session()->increment('count');
        return view('administrasi.dashboard',[
          'role' => $role,
          'data' => [
            'jumlah_item' => $item,
            'jumlah_item_aktif' => $item_aktif,
            'jumlah_kendaraan' => $vehicle,
            'jumlah_customer' => $customer,
            'jumlah_customer_aktif' => $customer_aktif,
          ],
          'notifikasi' => $notifikasi
        ])->with('datadua', [
          'lihat_notif' => true,
          'jml_trip' => count($notifikasi['trip']),
          'jml_retur' => count($notifikasi['retur']),
          'jml_order' => count($notifikasi['order_diajukan_salesman']) + count($notifikasi['order_diajukan_customer']) + count($notifikasi['order_selesai']),
          'jml_pengajuan_limit' => count($notifikasi['pengajuan_limit']),
          'jml_reimbursement' => count($notifikasi['reimbursement']),
          'jml_pajak' => count($pajakVehicles),
          'jml_jatuhTempo' => count($jatuhTempo)
        ]);
    }

    public function indexShipper(){
        $role='indexShipper';
        return view('shipper/dashboard',compact('role'));
    }

    public function indexCustomer(Request $request){
      $customer = Customer::where('id', auth()->user()->id_users)->first();
      $event = Event::where('status_enum', '1')->get();


      $kode_customers=Order::where('id_customer', auth()->user()->id_users)
      ->whereHas('linkOrderTrack',function($q) {
        $q->where('waktu_diteruskan', null);
      })
      ->with(['linkOrderTrack'])      
      ->get();

      $histories = History::where('id_customer', auth()->user()->id_users)->get();
      $request->session()->increment('count');

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
          $q->where('status_enum', '0')->orWhere('status_enum', '1');
        })
        ->with(['linkOrderTrack'])
        ->count();

        $order_dikonfirmasi=Order::where('id_customer', auth()->user()->id_users)
        ->whereHas('linkOrderTrack',function($q) {
          $q->where('status_enum', '2');
        })
        ->with(['linkOrderTrack'])
        ->count();

        $order_dikirim=Order::where('id_customer', auth()->user()->id_users)
        ->whereHas('linkOrderTrack',function($q) {
          $q->where('status_enum', '3');
        })
        ->with(['linkOrderTrack'])
        ->count();

        $order_diterima=Order::where('id_customer', auth()->user()->id_users)
        ->whereHas('linkOrderTrack',function($q) {
          $q->where('status_enum', '4')->orWhere('status_enum', '5')->orWhere('status_enum', '6');
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
      return view('customer.profil.detailprofil',[
        'data' => $data
      ]);                
    }

    public function lihatPesanan(Customer $customer){
        $diajukans = Order::whereHas('linkOrderTrack', function($q){
            $q->where('status_enum','0')->orWhere('status_enum', '1');
        })->where('id_customer','=',$customer->id)->with(['linkOrderTrack','linkInvoice','linkOrderItem.linkItem'])
        ->get();

        $dikonfirmasiAdministrasi = Order::whereHas('linkOrderTrack', function($q){
            $q->where('status_enum','2');
        })->where('id_customer','=',$customer->id)->with(['linkOrderTrack.linkStaffPengonfirmasi','linkInvoice','linkOrderItem.linkItem'])
        ->get();

        $dalamPerjalanan = Order::whereHas('linkOrderTrack', function($q){
            $q->where('status_enum','3');
        })->where('id_customer','=',$customer->id)->with(['linkOrderTrack','linkInvoice','linkOrderItem.linkItem'])
        ->get();

        $telahSampai = Order::whereHas('linkOrderTrack', function($q){
            $q->where('status_enum','4')->orWhere('status_enum','5')->orWhere('status_enum','6');
        })->where('id_customer','=',$customer->id)->with(['linkOrderTrack','linkInvoice','linkOrderItem.linkItem'])
        ->get();

        $ditolak = Order::whereHas('linkOrderTrack', function($q){
            $q->where('status_enum','-1');
        })->where('id_customer','=',$customer->id)->with(['linkOrderTrack','linkInvoice','linkOrderItem.linkItem'])
        ->get();      
             
        return view('customer.profil.detailpesanan',[
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
        return view('customer.profil.ubahpasswordlama');
      }
    }
}
