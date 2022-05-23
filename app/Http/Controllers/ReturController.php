<?php

namespace App\Http\Controllers;

use App\Models\Retur;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReturController extends Controller
{
    public function index(){
        $returs = Retur::paginate(5);

        return view('administrasi/retur.index',[
            'returs' => $returs
        ]);
    }
}
