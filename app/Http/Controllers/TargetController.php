<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Target;

class TargetController extends Controller
{
    public function index(){
      $targets = Target::whereNull('tanggal_berakhir')->get();
      
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
        'tanggal_berakhir' => ['nullable']
      ]);

      $validatedData = $request->validate($rules);
      $validatedData['created_at'] = now();

      // Target::updateOrCreate(
      //   ['jenis_target' => $validatedData['jenis_target']],
      //   ['value' =>  $validatedData['value']]
      // );

      $targetLama = Target::where('jenis_target', $request->jenis_target)->first();

      if($targetLama != null){
        $targetLama->tanggal_berakhir = now();
        $targetLama->updated_at = now();
        $targetLama->save();
      }

      Target::insert($validatedData);

      return redirect('/supervisor/target') -> with('pesanSukses', 'Target berhasil ditambahkan');
    }

    public function getTargetAPI(){
      $targets = Target::whereNull('tanggal_berakhir')->get();
      return response()->json([
        'status' => 'success',
        'data' => $targets
      ]);
    }
}
