<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function index(){
        $districts = District::paginate(10);
        return view('supervisor/wilayah.index',[
            'districts' => $districts
        ]);
    }

    public function search(){
        $districts = District::where(strtolower('nama'),'like','%'.request('cari').'%')->paginate(10);
               
        return view('supervisor/wilayah.index',[
            'districts' => $districts
        ]);
    }

    public function create(){
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
        usort($temp, function($a, $b) {
            return $a[0] <=> $b[0];
        });

        return view('supervisor/wilayah.addwilayah', [
            'districts' => $temp
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'nama_wilayah' => 'required|max:255'                     
        ]);

        District::create([
            'nama' => $request->nama_wilayah,
            'id_parent' => $request->id_parent,
            'created_at' => now()
        ]);
        
        return redirect('/supervisor/wilayah')->with('addWilayahSuccess','Tambah Wilayah berhasil');
    }

    public function edit(District $district){
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
        usort($temp, function($a, $b) {
            return $a[0] <=> $b[0];
        });

        return view('supervisor/wilayah.ubahwilayah', [
            'district' => $district,
            'selections' => $temp
        ]);
    }

    public function update(Request $request, District $district){
        $request->validate([
            'nama_wilayah' => 'required|max:255'                                   
        ]);
                       
        $district->nama = $request->nama_wilayah;
        if($request->id_parent != $district->id){
            $district->id_parent = $request->id_parent;
        }        
        $district->save(); 

        return redirect('/supervisor/wilayah')->with('updateWilayahSuccess','Update Wilayah Berhasil');
    }

    public function lihat(){
        $parentCategories = District::where('id_parent',null)->get();
        //dd($parentCategories);
        return view('supervisor/wilayah.wilayahTree', compact('parentCategories'));
    }
}
