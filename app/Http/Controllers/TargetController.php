<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Target;

class TargetController extends Controller
{
    public function index(){
      $targets = Target::all();
      
      return view('supervisor.target.index', [
        'targets' => $targets,
      ]);
    }

    public function tambahTarget(){
      $jenis = [
        1 => 'omset',
        2 => 'tagihan',
        3 => 'kunjungan',
        4 => 'effective call',
      ];
      return view('supervisor.target.tambahtarget', [
        'jenis' => $jenis,
      ]);
    }

    public function storeTarget(Request $request){
      $rules = ([
        'jenis_target' => ['required'],
        'value' => ['required', 'numeric'],
      ]);

      $validatedData = $request->validate($rules);

      Target::updateOrCreate(
        ['jenis_target' => $validatedData['jenis_target']],
        ['value' =>  $validatedData['value']]
      );
      return redirect('/supervisor/target') -> with('pesanSukses', 'Target berhasil ditambahkan');
    }

    public function getTargetAPI(){
      $targets = Target::all();
      return response()->json([
        'status' => 'success',
        'data' => $targets
      ]);
    }
}