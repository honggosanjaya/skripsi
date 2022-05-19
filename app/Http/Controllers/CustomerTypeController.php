<?php

namespace App\Http\Controllers;

use App\Models\CustomerType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CustomerTypeController extends Controller
{
    public function index()
    {
        return view('supervisor/jeniscustomer.index',[
            'jenises' => CustomerType::all()            
        ]);
    }

    public function search()
    {
        $jenises = DB::table('customer_types')->where(strtolower('nama'),'like','%'.request('cari').'%')->get();
       
        return view('supervisor/jeniscustomer.index',[
            'jenises' => $jenises
        ]);
    }

    public function create()
    {
        return view('supervisor/jeniscustomer.addjenis');
    }

    public function store(Request $request)
    {
        //Validation untuk diskon belum sempurna
        $request->validate([
            'nama_jenis' => 'required|max:255',
            'diskon' => 'required',
            'keterangan' => 'required'            
        ]);

        CustomerType::create([
            'nama' => $request->nama_jenis,
            'diskon' => $request->diskon,
            'keterangan' => $request->keterangan
        ]); 
        
        return redirect('/supervisor/jenis')->with('addJenisSuccess','Tambah user berhasil');
    }

    public function edit(CustomerType $customertype)
    {
        return view('supervisor/jeniscustomer.ubahjenis',[
            'customertype' => $customertype
        ]);
    }

    public function update(Request $request, CustomerType $customertype)
    {
        $rules = $request->validate([
            'nama' => 'required|max:255',
            'diskon' => 'required',
            'keterangan' => 'required'                   
        ]);

        CustomerType::Where('id', $customertype->id)
            ->update($rules);

        return redirect('/supervisor/jenis')->with('updateDataSuccess','Update Jenis Customer '. $customertype->nama .' Berhasil');        
    }
}
