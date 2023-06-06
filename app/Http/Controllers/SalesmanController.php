<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Staff;
use Illuminate\Http\Request;

class SalesmanController extends Controller
{
  public function index(Request $request){
    $request->session()->increment('count');
    return view('salesman.dashboard',[
      'isDashboard' => true,
      'isSalesman' => true
    ]);
  }

  public function profil(){
    $data = Staff::find(auth()->user()->id_users);
    return view('react.profil',[
      'page' => 'Profil Saya',
      'data' => $data,
      'isSalesman' => true
    ]);
  }

  public function changepassword(){
    return view('react.changepassword');
  }

  public function riwayatInvoice(Request $request){
    $date_start = $request->date_start ?? now()->subDays(30)->format('Y-m-d');
    $date_end = $request->date_end ?? now()->format('Y-m-d');
    $id_staff = auth()->user()->id_users;

    $datas = Invoice::whereBetween('created_at', [$date_start, $date_end])
              ->whereHas('linkOrder', function($q) use($id_staff) {
                  $q->where('id_staff', $id_staff);
                })
              ->orderBy('created_at','DESC')
              ->with(['linkOrder', 'linkOrder.linkOrderItem', 'linkOrder.linkCustomer'])
              ->get();

    return view('salesman.riwayatInvoice',[
      'invoices' => $datas,
      'date_start' => $date_start,
      'date_end' => $date_end
    ]);
  }
}
