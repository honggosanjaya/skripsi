<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\LaporanPenagihan;
use App\Models\Order;
use App\Models\OrderTrack;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Util;
use Intervention\Image\ImageManagerStatic as Image;

class LapanganController extends Controller
{
  public function jadwalpengiriman(){
    $id_staff = auth()->user()->id_users;
    $perludikirims = Order::whereHas('linkOrderTrack',function($q) use($id_staff) {
                        $q->where('id_staff_pengirim', $id_staff);
                      })->where(function ($query) {
                        $query->whereHas('linkOrderTrack',function($qr) {
                          $qr->where('status_enum', '3');
                        });
                      })
                      ->with(['linkOrderTrack','linkInvoice','linkCustomer','linkOrderItem'])
                      ->orderBy('id','DESC')->get();
    // dd($perludikirims);

    $sudahsampais = Order::whereHas('linkOrderTrack',function($q) use($id_staff) {
                      $q->where('id_staff_pengirim', $id_staff);
                    })->where(function ($query) {
                      $query->whereHas('linkOrderTrack',function($qr) {
                        $qr->where('status_enum','>', '3')
                          ->where('status_enum','<=', '6')
                          ->whereBetween('waktu_sampai',[now()->subDays(2),now()]);
                      });
                    })
                    ->with(['linkOrderTrack','linkInvoice','linkCustomer','linkOrderItem'])
                    ->orderBy('id','DESC')->get();
    // dd($sudahsampais);

    return view('react.jadwalpengiriman',[
      'page' => 'Jadwal Pengiriman',
      'perludikirims' => $perludikirims,
      'sudahsampais' => $sudahsampais
    ]);            
  }

  public function konfirmasiPengirimanSampai(Request $request, $id){  
    // dd($request->all());
      if($request->bukti_galeri ?? null){
        $file = $request->file('bukti_galeri');
        $file_name=  'DLV-'.$id.'.'.$file->getClientOriginalExtension();
        Image::make($request->file('bukti_galeri'))->resize(350, null, function ($constraint) {
          $constraint->aspectRatio();
        })->save(public_path('storage/pengiriman/') . $file_name);
      }elseif($request->bukti_kamera ?? null){
        $file = $request->file('bukti_kamera');
        $file_name=  'DLV-'.$id.'.'.$file->getClientOriginalExtension();
        Image::make($request->file('bukti_kamera'))->resize(350, null, function ($constraint) {
          $constraint->aspectRatio();
        })->save(public_path('storage/pengiriman/') . $file_name);
      }

      Util::backupFile(public_path('storage/pengiriman/'.$file_name),'salesman-surya/storage/pengiriman/');

      OrderTrack::where('id_order', $id)->update([
        'foto_pengiriman' => $file_name,
        'status_enum' => '4',
        'waktu_sampai' => now()
      ]);

      $ordertracks = OrderTrack::all();
      $vehicleInRoads = array();

      foreach($ordertracks as $ordertrack){
        if($ordertrack->status_enum == '3'){
          array_push($vehicleInRoads, [
            'id_vehicle' => $ordertrack->id_vehicle
          ]);
        }
      }

      DB::table('vehicles')->update(['is_active' => true]);
      foreach ($vehicleInRoads as $vehicle) {
        Vehicle::where('id', $vehicle['id_vehicle'])->update([
          'is_active' => false
        ]);
      }

      return redirect('/lapangan/jadwal')->with('successMessage', 'Berhasil mengonfirmasi pengiriman sampai');
  }

  public function penagihan(){
    $Alltagihans = LaporanPenagihan::where('id_staff_penagih',auth()->user()->id_users)
                  ->orderBy('tanggal', 'ASC')
                  ->with('linkInvoice')
                  ->get();

    return view('shipper.penagihan',[
      'page' => 'LP3',
      'tagihans' => $Alltagihans,
    ]);    
  }

}
