<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\History;


use App\Helpers\Session;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function test($id){
        $history = History::where('id_customer',$id)->with('linkItem')->first()->linkitem->nama;
        dd($history );
        dd(Session::getId($id));
        return null;
    }
}
