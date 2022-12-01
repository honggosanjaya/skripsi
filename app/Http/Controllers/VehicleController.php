<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Order;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class VehicleController extends Controller
{
    public function index(){
        $vehicles = Vehicle::paginate(10);

        $agent = new Agent();
        if($agent->isMobile()){
          return view('mobile.administrasi.kendaraan.index', compact('vehicles'));
        }else{
          return view('administrasi.kendaraan.index', compact('vehicles'));
        }
    }

    public function search(){
        $vehicles =  Vehicle::where(strtolower('nama'),'like','%'.request('cari').'%')
        ->orWhere(strtolower('kode_kendaraan'),'like','%'.request('cari').'%')
        ->paginate(10);

        $agent = new Agent();
        if($agent->isMobile()){
          return view ('mobile.administrasi.kendaraan.index',[
            'vehicles' => $vehicles
          ]);
        }else{
          return view ('administrasi.kendaraan.index',[
            'vehicles' => $vehicles
          ]);
        }
    }

    public function create(){
      $agent = new Agent();
      if($agent->isMobile()){
        return view('mobile.administrasi.kendaraan.addkendaraan');
      }else{
        return view('administrasi.kendaraan.addkendaraan');
      }
    }

    public function store(Request $request){
        $request->validate([
            'nama_kendaraan' => 'required|max:255',
            'plat_kendaraan' => 'required|max:20',
            'kapasitas_harga' => 'nullable|numeric',
            'kapasitas_volume' => 'required|numeric',
            'tanggal_pajak' => 'nullable|date'
        ]);

        $searchPlats = Vehicle::where('kode_kendaraan',$request->plat_kendaraan)->get();
        if($searchPlats->count()>0){
            return redirect('/administrasi/kendaraan/tambah')->with('error','Plat Nomor sudah ada')->withInput();
        }

        Vehicle::create([
            'nama' => $request->nama_kendaraan,
            'kode_kendaraan' => $request->plat_kendaraan,
            'kapasitas_harga' => $request->kapasitas_harga,
            'kapasitas_volume' => $request->kapasitas_volume,
            'tanggal_pajak' => $request->tanggal_pajak
        ]); 

        return redirect('/administrasi/kendaraan')->with('successMessage','Tambah Kendaraan berhasil');
    }

    public function edit(Vehicle $vehicle){
      $agent = new Agent();
      if($agent->isMobile()){
        return view ('mobile.administrasi.kendaraan.ubahkendaraan',[
          'vehicle' => $vehicle
        ]);
      }else{
        return view ('administrasi.kendaraan.ubahkendaraan',[
          'vehicle' => $vehicle
        ]);
      }
    }

    public function update(Request $request, Vehicle $vehicle){
        $rules = $request->validate([
            'nama_kendaraan' => 'required|max:255',
            'plat_kendaraan' => 'required|max:20',
            'kapasitas_harga' => 'nullable|numeric',
            'kapasitas_volume' => 'required|numeric',
            'tanggal_pajak' => 'nullable|date'                  
        ]);

        $searchPlats = Vehicle::get();
        foreach($searchPlats as $searchPlat){
            if($request->plat_kendaraan == $searchPlat->kode_kendaraan && $searchPlat->id != $vehicle->id){
                return redirect('/administrasi/kendaraan/ubah/'.$vehicle->id)->with('error','Plat Nomor sudah ada');
            }
        }

        $vehicle->nama = $request->nama_kendaraan;
        $vehicle->kode_kendaraan = $request->plat_kendaraan;
        $vehicle->kapasitas_volume = $request->kapasitas_volume;
        $vehicle->kapasitas_harga = $request->kapasitas_harga;
        $vehicle->tanggal_pajak = $request->tanggal_pajak;
        $vehicle->save();
        
        return redirect('/administrasi/kendaraan')->with('successMessage','Update Kendaraan Berhasil');
    }

    public function detail(Vehicle $vehicle){
      $invoices = Invoice::whereHas('linkOrder',function($q) use($vehicle){
        $q->whereHas('linkOrderTrack', function($q) use($vehicle){
          $q->where('status_enum','2')->where('id_vehicle', $vehicle->id);
        });
      })
      ->with(['linkOrder', 'linkOrder.linkOrderItem', 'linkOrder.linkOrderItem.linkItem'])
      ->get();

      $data = [
        'vehicle' => $vehicle,
        'invoices' => $invoices
      ];

      $agent = new Agent();
      if($agent->isMobile()){
        return view ('mobile.administrasi.kendaraan.detailkendaraan',$data);
      }else{
        return view ('administrasi.kendaraan.detailkendaraan',$data);
      }
    }
}
