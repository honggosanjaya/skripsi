<?php

namespace App\Http\Controllers;

use App\Models\Retur;
use App\Models\ReturType;
use App\Models\District;
use App\Models\Staff;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PDF;

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

        return view('administrasi/retur.detail',[
            'retur' => $retur,
            'wilayah' => $temp[($retur->linkCustomer->id_wilayah)-1],
            'items' => $joins,
            'administrasi' => $administrasi,
            'tipeReturs' => $retur_type
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
