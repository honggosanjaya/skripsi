<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Retur;
use App\Models\Staff;
use App\Models\Customer;
use App\Models\District;
use App\Models\ReturType;
use App\Models\Order;
use App\Models\OrderTrack;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturController extends Controller
{
    public function index(){
        $returs = Retur::select('no_retur','id_customer','id_staff_pengaju', 'created_at','status')        
        ->groupBy('no_retur','id_customer','id_staff_pengaju','created_at','status')
        ->with(['linkCustomer','linkStaffPengaju','linkStatus'])
        ->paginate(5);       
        
        return view('administrasi/retur.index',[
            'returs' => $returs
        ]);
    }

    public function pengajuanReturAPI(Request $request){
      $cartItems = $request->cartItems;
      $id_staff_pengaju = $request->id_staff_pengaju;
      $id_customer = $request->id_customer;
      $customer = Customer::find($id_customer);
      $data = [];
      foreach($cartItems as $item){
        array_push($data,[
          'id_customer' => $item['id_customer'],
          'id_staff_pengaju' => $id_staff_pengaju,
          'id_item' => $item['id'],
          'no_retur' => (Retur::orderBy("no_retur", "DESC")->first()->no_retur ?? 0) + 1,
          'kuantitas' => $item['kuantitas'],
          'harga_satuan' => $item['harga_satuan'],
          'tipe_retur' => $customer->tipe_retur,
          'alasan' => $item['alasan'],
          'status' => 13,
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

    public function search(){
        $returs = Retur::select('no_retur','id_customer','id_staff_pengaju', 'created_at','status')        
        ->groupBy('no_retur','id_customer','id_staff_pengaju','created_at','status')
        ->where(strtolower('no_retur'),'like','%'.request('cari').'%')
        ->with(['linkCustomer','linkStaffPengaju','linkStatus'])        
        ->paginate(5); 
                
        return view('administrasi/retur.index',[
            'returs' => $returs
        ]);
        
    }
    public function confirmRetur(Request $request){
      $rules = ([
        'tipe_retur' => ['required'],
        'no_retur' => ['required'],
        'id_invoice' => ['required'],
      ]);

      $validatedData = $request->validate($rules);
      $retur=Retur::where('no_retur',  $request->no_retur);
      $retur->update([
        'tipe_retur' => $request->tipe_retur,
        'id_invoice' => $request->id_invoice,
        'status' => 12
      ]
      );
      $harga_total=Retur::select('no_retur',DB::raw('SUM(harga_satuan * kuantitas) as harga'))
        ->groupBy('no_retur')->where('no_retur',  $request->no_retur)->first()->harga;
      $invoice=Invoice::find($request->id_invoice);
      if ($request->tipe_retur==1) {
        $invoice->update(["harga_total"=>($invoice->harga_total - $harga_total)]);
      }
    
      return redirect('/administrasi/retur') -> with('pesanSukses', 'Berhasil mengubah data');
    }

    public function viewRetur(Retur $retur){
      $districtTotal = District::count();
      $districts = District::all();
      $temp = array();
      
      for($i=0; $i<$districtTotal; $i++)
      {
        $get1 = '';
        $get2 = '';
        $value = 0;
        
        if($districts[$i]->id_parent == null)
        {
          $get1 = $districts[$i]->nama;
          $value = $districts[$i]->id;
          array_push($temp, [$get1, $value]);
        }
        else if($districts[$i]->id_parent != null)
        {
          for($j=$districtTotal-1; $j>=0; $j--)
          {
            if($districts[$i]->id_parent == $districts[$j]->id)
            {
              $get2 = $temp[$j][0] . " - " .$districts[$i]->nama;
                      $value = $districts[$i]->id;
              array_push($temp, [$get2,$value]);
            }
          }
        }
      }

      $joins = Retur::join('items','returs.id_item','=','items.id')
              ->where('returs.no_retur','=',$retur->no_retur)->get();
      $administrasi = Staff::select('nama')->where('id','=',auth()->user()->id_users)->first();
      $retur_type = ReturType::get();
      $invoices = Order::whereHas('linkOrderTrack', function($q){
        $q->whereIn('status', [21,22,23,24]);
      })->where('id_customer','=',$retur->linkCustomer->id)->with(['linkOrderTrack','linkInvoice'])
      ->get();

      return view('administrasi/retur.detail',[
        'retur' => $retur,
        'wilayah' => $temp[($retur->linkCustomer->id_wilayah)-1],
        'items' => $joins,
        'administrasi' => $administrasi,
        'tipeReturs' => $retur_type,
        'invoices' => $invoices
      ]);
    }

    public function cetakRetur(Retur $retur){
        $districtTotal = District::count();
        $districts = District::all();
        $temp = array();
        
        for($i=0; $i<$districtTotal; $i++)
        {
            $get1 = '';
	        $get2 = '';
	        $value = 0;
	        
            if($districts[$i]->id_parent == null)
	        {
		        $get1 = $districts[$i]->nama;
		        $value = $districts[$i]->id;
		        array_push($temp, [$get1, $value]);
	        }
	        else if($districts[$i]->id_parent != null)
	        {
		        for($j=$districtTotal-1; $j>=0; $j--)
		        {
			        if($districts[$i]->id_parent == $districts[$j]->id)
			        {
				        $get2 = $temp[$j][0] . " - " .$districts[$i]->nama;
                        $value = $districts[$i]->id;
				        array_push($temp, [$get2,$value]);
			        }
		        }
        	}
            
        }

        $joins = Retur::join('items','returs.id_item','=','items.id')
                ->where('returs.no_retur','=',$retur->no_retur)->get();
        $administrasi = Staff::select('nama')->where('id','=',auth()->user()->id_users)->first();


        $pdf = PDF::loadview('administrasi/retur.cetakretur',[
            'retur' => $retur,
            'wilayah' => $temp[($retur->linkCustomer->id_wilayah)-1],
            'items' => $joins,
            'administrasi' => $administrasi      
          ]);
  
        return $pdf->download('retur-'.$retur->no_retur.'.pdf');
        // return view('administrasi/retur.detail',[
        //     'retur' => $retur,
        //     'wilayah' => $temp[($retur->linkCustomer->id_wilayah)-1],
        //     'items' => $joins,
        //     'administrasi' => $administrasi
        // ]);
    }
}
