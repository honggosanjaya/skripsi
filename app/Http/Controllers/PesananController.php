<?php

namespace App\Http\Controllers;

use App\Models\MetodePembayaran;
use App\Models\Order;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    public function index()
    {
        $toko = Toko::with(['linkJenisToko','linkOrder','linkTrip'])->get();
        // dd($toko[0]);
        return view('admin/pesanan', [
            'tokos' => $toko                       
        ]);
    }
}
