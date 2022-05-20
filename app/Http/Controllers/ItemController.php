<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Pengadaan;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function index()
    {
        return view('customer/produk',[
            'items' => Item::all()
        ]);
    }

    public function search()
    {
        $items = DB::table('items')->where(strtolower('nama'),'like','%'.request('cari').'%')->get();
       
        return view('customer/produk',[
            'items' => $items
        ]);
    }

    public function indexAdministrasi()
    {
        return view('administrasi/stok.index',[
            'items' => Item::all()
        ]);
        
    }

    public function cariStok()
    {
        $items = DB::table('items')->where(strtolower('nama'),'like','%'.request('cari').'%')->get();
       
        return view('administrasi/stok.index',[
            'items' => $items
        ]);
    }

    public function riwayatAdministrasi()
    {
        return view('administrasi/stok/riwayat.index',[
            'pengadaans' => Pengadaan::all()
        ]);
        
    }

    public function cariRiwayat()
    {
        $pengadaans = DB::table('pengadaans')->where(strtolower('no_nota'),'like','%'.request('cari').'%')->get();
       
        return view('administrasi/stok/riwayat.index',[
            'pengadaans' => $pengadaans
        ]);
    }
}
