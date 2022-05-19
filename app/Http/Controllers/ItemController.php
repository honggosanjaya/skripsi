<?php

namespace App\Http\Controllers;

use App\Models\Item;
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
}
