<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;

class SalesmanController extends Controller
{
  public function index(){
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
}
