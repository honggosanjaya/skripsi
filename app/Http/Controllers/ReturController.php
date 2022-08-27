<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Retur;
use App\Models\Staff;
use App\Models\Item;
use App\Models\Customer;
use App\Models\District;
use App\Models\ReturType;
use App\Models\Order;
use App\Models\OrderTrack;
use App\Models\Invoice;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturController extends Controller
{
    public function index(){
      $returs = Retur::select('no_retur','id_customer','id_staff_pengaju', 'created_at','status_enum')        
      ->groupBy('no_retur','id_customer','id_staff_pengaju','created_at','status_enum')
      ->with(['linkCustomer','linkStaffPengaju'])
      ->orderBy('no_retur','DESC')->get();       
      
      return view('administrasi/retur.index',[
          'returs' => $returs
      ]);
    }

    public function pengajuanReturAPI(Request $request){
      $cartItems = $request->cartItems;
      $id_staff_pengaju = $request->id_staff_pengaju;
      $id_customer = $request->id_customer;
      $id_invoice = $request->id_invoice;
      $customer = Customer::find($id_customer);
      $data = [];
      $retur_count="RTR-".explode("-",Retur::orderBy("id", "DESC")->first()->no_retur ?? 'RTR-0')[1] + 1 ."-".date_format(now(),"YmdHis");
      
      foreach($cartItems as $item){
        array_push($data,[
          'id_customer' => $item['id_customer'],
          'id_staff_pengaju' => $id_staff_pengaju,
          'id_item' => $item['id'],
          'no_retur' => $retur_count,
          'id_invoice' => $id_invoice,
          'kuantitas' => $item['kuantitas'],
          'harga_satuan' => $item['harga_satuan'],
          'tipe_retur' => $customer->tipe_retur,
          'alasan' => $item['alasan'],
          'status_enum' => '0',
          'created_at'=>now()
        ]);
      }
      
      Retur::insert($data);

      return response()->json([
        "status" => "success",
        "message" => "berhasil mengajukan retur",
      ], 200); 
    }

    public function getTypeReturAPI(){
      return response()->json([
        "status" => "success",
        "data" => ReturType::all(),
      ], 200);
    }

    // public function search(){
    //   $returs = Retur::select('no_retur','id_customer','id_staff_pengaju', 'created_at','status')        
    //   ->groupBy('no_retur','id_customer','id_staff_pengaju','created_at','status')
    //   ->where(strtolower('no_retur'),'like','%'.request('cari').'%')
    //   ->with(['linkCustomer','linkStaffPengaju','linkStatus'])        
    //   ->paginate(10); 
              
    //   return view('administrasi.retur.index',[
    //       'returs' => $returs
    //   ]);
    // }

    public function confirmRetur(Request $request){
      $rules = ([
        'tipe_retur' => ['required'],
        'no_retur' => ['required'],
        'id_invoice' => ['required'],
      ]);

      $request->validate($rules);

      $retur = Retur::where('no_retur',  $request->no_retur);
      $retur->update([
        'tipe_retur' => $request->tipe_retur,
        'id_invoice' => $request->id_invoice,
        'id_staff_pengonfirmasi' => auth()->user()->id_users,
        'status_enum' => '1'
      ]);

      $harga_total = Retur::select('no_retur',DB::raw('SUM(harga_satuan * kuantitas) as harga'))
        ->groupBy('no_retur')->where('no_retur',  $request->no_retur)->first()->harga;

      $invoice = Invoice::find($request->id_invoice);
      
      if ($request->tipe_retur == 1) {
        // potongan
        $invoice->update(["harga_total"=>($invoice->harga_total - $harga_total)]);
        foreach($retur->get() as $r){
          $item=item::find($r->id_item);
          $item->update([
            "stok_retur" => ($item->stok_retur + $r->kuantitas)
          ]);
        }
      } elseif ($request->tipe_retur == 2){
        // tukar guling
        foreach($retur->get() as $r){
          $item=item::find($r->id_item);
          $item->update([
            "stok" => ($item->stok - $r->kuantitas),
            "stok_retur" => ($item->stok_retur + $r->kuantitas)
          ]);
        }
      }

      return redirect('/administrasi/retur') -> with('pesanSukses', 'Berhasil mengubah data');
    }

    public function viewRetur(Retur $retur){
      $districtTotal = District::count();
      $districts = District::all();
      $temp = array();
      
      for($i=0; $i<$districtTotal; $i++){
        $get1 = '';
        $get2 = '';
        $value = 0;
        
        if($districts[$i]->id_parent == null){
          $get1 = $districts[$i]->nama;
          $value = $districts[$i]->id;
          array_push($temp, [$get1, $value]);
        }
        else if($districts[$i]->id_parent != null){
          for($j=$districtTotal-1; $j>=0; $j--){
            if($districts[$i]->id_parent == $districts[$j]->id){
              $get2 = $temp[$j][0] . " - " .$districts[$i]->nama;
              $value = $districts[$i]->id;
              array_push($temp, [$get2,$value]);
            }
          }
        }
      }

      $joins = Retur::with('linkItem')->where('no_retur',$retur->no_retur)->get();
      $administrasi = Staff::select('nama')->where('id','=',auth()->user()->id_users)->first();
      $retur_type = ReturType::get();
      $total_harga = 0;
      for($k=0; $k<$joins->count();$k++){
        $total_harga = $total_harga + ($joins[$k]->kuantitas * $joins[$k]->harga_satuan);
      }
      $invoices = Order::orderBy('id','DESC')->whereHas('linkOrderTrack', function($q){
        $q->whereIn('status', [21,22,23,24]);
      })
      ->whereHas('linkInvoice', function($q) use($total_harga){
        $q->where('harga_total', '>=',$total_harga);
      })
      ->where('id_customer','=',$retur->linkCustomer->id)->with(['linkOrderTrack','linkInvoice'])
      ->get();
      
      return view('administrasi.retur.detail',[
        'retur' => $retur,
        'wilayah' => $temp[($retur->linkCustomer->id_wilayah)-1],
        'items' => $joins,
        'administrasi' => $administrasi,
        'tipeReturs' => $retur_type,
        'invoices' => $invoices,
        'total_harga' => $total_harga
      ]);
    }

    public function cetakRetur(Retur $retur){
      $districtTotal = District::count();
      $districts = District::all();
      $temp = array();
      
      for($i=0; $i<$districtTotal; $i++){
        $get1 = '';
        $get2 = '';
        $value = 0;
        
        if($districts[$i]->id_parent == null){
          $get1 = $districts[$i]->nama;
          $value = $districts[$i]->id;
          array_push($temp, [$get1, $value]);
        }
        else if($districts[$i]->id_parent != null){
          for($j=$districtTotal-1; $j>=0; $j--){
            if($districts[$i]->id_parent == $districts[$j]->id){
              $get2 = $temp[$j][0] . " - " .$districts[$i]->nama;
              $value = $districts[$i]->id;
              array_push($temp, [$get2,$value]);
            }
          }
        }
      }

      $joins = Retur::with('linkItem')->where('no_retur',$retur->no_retur)->get();
      $administrasi = Staff::select('nama')->where('id','=',auth()->user()->id_users)->first();
      $total_harga = 0;
      for($k=0; $k<$joins->count();$k++){
        $total_harga = $total_harga + ($joins[$k]->kuantitas * $joins[$k]->harga_satuan);
      }

      $pdf = PDF::loadview('administrasi/retur.cetakretur',[
        'retur' => $retur,
        'wilayah' => $temp[($retur->linkCustomer->id_wilayah)-1],
        'items' => $joins,
        'administrasi' => $administrasi,
        'total_harga' => $total_harga      
      ]);

      return $pdf->stream('retur-'.$retur->no_retur.'.pdf');
    }
}
