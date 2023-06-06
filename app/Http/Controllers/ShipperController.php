<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;

class ShipperController extends Controller
{
  public function index(Request $request){
    $request->session()->increment('count');
    return view('shipper.dashboard',[
      'isDashboard' => true,
    ]);
  }

  public function profil(){
    $data = Staff::find(auth()->user()->id_users);
    return view('react.profil',[
      'page' => 'Profil Saya',
      'data' => $data
    ]);
  }

  public function changepassword(){
    return view('react.changepassword');
  }
}
