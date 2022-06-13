<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderTrack;
use App\Models\OrderItem;
use App\Models\Staff;
use App\Models\Item;
use App\Models\Trip;
use App\Models\Event;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Vehicle;
use App\Models\Status;
use App\Models\History;

class ReportController extends Controller
{
    public function penjualan(){
        $data['mostSellItem']=Item::get();
        dd($data);
        return view('supervisor.report.penjualan',compact('data'));
    }
    public function kinerja(){
        // $data['mostSellItem']=Item::get();
        // return view('supervisor.report.kinerja',compact('data'));
        return view('supervisor.report.kinerja');
    }
}
