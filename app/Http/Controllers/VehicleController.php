<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(){
        $vehicles = Vehicle::paginate(10);
        return view ('administrasi/kendaraan.index',[
            'vehicles' => $vehicles
        ]);
    }

    public function search(){
        $vehicles =  Vehicle::where(strtolower('nama'),'like','%'.request('cari').'%')
        ->orWhere(strtolower('kode_kendaraan'),'like','%'.request('cari').'%')
        ->paginate(10);
       
        return view('administrasi/kendaraan.index',[
            'vehicles' => $vehicles
        ]);
    }

    public function create(){
        return view('administrasi/kendaraan.addkendaraan');
    }

    public function store(Request $request){
        $request->validate([
            'nama_kendaraan' => 'required|max:255',
            'plat_kendaraan' => 'required|max:20',
            'kapasitas_harga' => 'required|numeric',
            'kapasitas_volume' => 'required|numeric'
        ]);

        $searchPlats = Vehicle::where('kode_kendaraan',$request->plat_kendaraan)->get();
        if($searchPlats->count()>0){
            return redirect('/administrasi/kendaraan/tambah')->with('error','Plat Nomor sudah ada')->withInput();
        }

        Vehicle::create([
            'nama' => $request->nama_kendaraan,
            'kode_kendaraan' => $request->plat_kendaraan,
            'kapasitas_harga' => $request->kapasitas_harga,
            'kapasitas_volume' => $request->kapasitas_volume
        ]); 

        return redirect('/administrasi/kendaraan')->with('addKendaraanSuccess','Tambah Kendaraan berhasil');
    }

    public function edit(Vehicle $vehicle){
        return view ('administrasi/kendaraan.ubahkendaraan',[
            'vehicle' => $vehicle
        ]);
    }

    public function update(Request $request, Vehicle $vehicle){
        $rules = $request->validate([
            'nama_kendaraan' => 'required|max:255',
            'plat_kendaraan' => 'required|max:20',
            'kapasitas_harga' => 'required|numeric',
            'kapasitas_volume' => 'required|numeric'                  
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
        
        $vehicle->save();
        
        return redirect('/administrasi/kendaraan')->with('updateKendaraanSuccess','Update Kendaraan Berhasil');
    }
}
