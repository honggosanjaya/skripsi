<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Retur;
use Illuminate\Support\Facades\Auth;

class ReturController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

      public function index()
      {
          $retur = Retur::with(['linkItem','linkSales','linkToko'])->get();
          
          // dd($retur);
          return view('admin/retur/retur', [
              'returs' => $retur,
              'nama_admin' => Auth::user()->nama                    
          ]);
      }


      public function terimaRetur($id)
      {
        $retur = Retur::where('id', $id)->first();

        Retur::where('id', $id)->update(['status' => '1']);

        return response()->json([
          'status_retur'=> $retur->status,
          'successMessage' => 'Retur '.$retur->linkItem->nama_barang.' Disetujui',
        ]);
      }

      public function tolakRetur($id)
      {
        $retur = Retur::where('id', $id)->first();

        Retur::where('id', $id)->update(['status' => '-1']);

        return response()->json([
          'status_retur'=> $retur->status,
          'successMessage' => 'Retur '.$retur->linkItem->nama_barang.' Ditolak',
        ]);
      }

    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
