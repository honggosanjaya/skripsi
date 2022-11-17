<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Customer;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function index(){
      $districts = District::paginate(10);
      return view('supervisor.wilayah.index',[
          'districts' => $districts
      ]);
    }

    public function search(){
      $districts = District::where(strtolower('nama'),'like','%'.request('cari').'%')->paginate(10);
              
      return view('supervisor/wilayah.index',[
        'districts' => $districts
      ]);
    }

    public function create(){
      $districts = District::get();
      $temp = array();
      $dropdown = array();

      for($i=0; $i<count($districts); $i++){
        $get1 = '';
        $get2 = '';
        $value = 0;
        $get1 = $districts[$i]->nama;
        $value = $districts[$i]->id;
        array_push($temp, [$get1, $value]);        
      }

      for($j=0; $j<count($temp); $j++){
        if($districts[$j]->id_parent == null){
          $get1 = $districts[$j]->nama;
          $value = $districts[$j]->id;
          array_push($dropdown, [$get1, $value]);
        }

        else if($districts[$j]->id_parent != null){
          for($k=count($districts)-1; $k>=0; $k--){
            if($districts[$j]->id_parent == $temp[$k][1]){
              for($l=0;$l<count($dropdown);$l++){
                if($districts[$j]->id_parent == $dropdown[$l][1] && (stripos($dropdown[$l][0],$districts[$j]->nama)) === false){
                  $get2 = $dropdown[$l][0] . " - " .$districts[$j]->nama;
                  break;
                }
                elseif($districts[$j]->id_parent == $dropdown[$l][1] && (stripos($dropdown[$l][0],$districts[$j]->nama)) >= 0){
                  $get2 = $districts[$j]->nama;
                  break;
                }
                else{
                  $get2 = $temp[$k][0] . " - " .$districts[$j]->nama;
                }
              }

              $value = $districts[$j]->id;
              array_push($dropdown, [$get2,$value]);
            }
          }
        }
      }

      usort($dropdown, function($a, $b) {
        return $a[0] <=> $b[0];
      });
      
      return view('supervisor.wilayah.addwilayah', [
        'dropdown' => $dropdown,
      ]);
    }

    public function store(Request $request){
      $request->validate([
        'nama_wilayah' => 'required|max:255'                     
      ]);

      District::create([
        'nama' => $request->nama_wilayah,
        'id_parent' => $request->id_parent,
        'created_at' => now()
      ]);
      
      return redirect('/supervisor/wilayah')->with('addWilayahSuccess','Tambah wilayah berhasil');
    }

    public function edit(District $district){
      $data = $district;
      $districts = District::get();
      $temp = array();
      $dropdown = array();

      for($i=0; $i<count($districts); $i++){
        $get1 = '';
        $get2 = '';
        $value = 0;
        $get1 = $districts[$i]->nama;
        $value = $districts[$i]->id;
        array_push($temp, [$get1, $value]);        
      }

      for($j=0; $j<count($temp); $j++){
        if($districts[$j]->id_parent == null){
          $get1 = $districts[$j]->nama;
          $value = $districts[$j]->id;
          array_push($dropdown, [$get1, $value]);
        }

        else if($districts[$j]->id_parent != null){
          for($k=count($districts)-1; $k>=0; $k--){
            if($districts[$j]->id_parent == $temp[$k][1]){

              for($l=0;$l<count($dropdown);$l++){
                if($districts[$j]->id_parent == $dropdown[$l][1] && (stripos($dropdown[$l][0],$districts[$j]->nama)) === false){
                  $get2 = $dropdown[$l][0] . " - " .$districts[$j]->nama;
                  break;
                }
                elseif($districts[$j]->id_parent == $dropdown[$l][1] && (stripos($dropdown[$l][0],$districts[$j]->nama)) >= 0){
                  $get2 = $districts[$j]->nama;
                  break;
                }
                else{
                  $get2 = $temp[$k][0] . " - " .$districts[$j]->nama;
                }
              }

              $value = $districts[$j]->id;
              array_push($dropdown, [$get2,$value]);
            }
          }
        }
      }

      usort($dropdown, function($a, $b) {
        return $a[0] <=> $b[0];
      });

      return view('supervisor.wilayah.ubahwilayah', [
        'district' => $district,
        'dropdown' => $dropdown,
        'data' => $data
      ]);
    }

    public function update(Request $request, District $district){
      $request->validate([
        'nama_wilayah' => 'required|max:255'                                   
      ]);
                      
      $district->nama = $request->nama_wilayah;
      if($request->id_parent != $district->id){
        $district->id_parent = $request->id_parent;
      }        
      $district->save(); 

      return redirect('/supervisor/wilayah')->with('updateWilayahSuccess','Update Wilayah Berhasil');
    }

    public function lihat(){
      $parentCategories = District::where('id_parent',null)->get();
      return view('supervisor.wilayah.wilayahTree', compact('parentCategories'));
    }

    private function codeLama($id){
      $district = District::where('id', $id)->get()->first();  

      $descendantsIds = $district->descendants->pipe(function ($collection){
        $array = $collection->toArray();
        $ids = [];
        array_walk_recursive($array, function ($value, $key) use (&$ids) {
            if ($key === 'id') {
                $ids[] = $value;
            };
        });
        return $ids;
      });

      array_push($descendantsIds, $district->id);

      return $descendantsIds;
    }

    private function codeBaru($id){
      $descendantsIds = [];
      $parentchildIds = [];
      $parentchildIds[] = [(int)$id];

      do {
        $parent_child = District::whereIn('id_parent', end($parentchildIds))->select('id')->get();
        $id_parent_child = $parent_child->pluck('id');
        $parentchildIds[] = $id_parent_child;
      } while (count(end($parentchildIds)) > 0);

      $keys = array_keys($parentchildIds);
      for($i = 0; $i < count($parentchildIds); $i++) {
        foreach($parentchildIds[$keys[$i]] as $value) {
          array_push($descendantsIds, $value);
        }
      }
      
      return $descendantsIds;
    }

    // public function getCustByDistrictAPI($id){
    //   // ====== Code Baru ======
    //   $totalTimeBaru = 0;
    //   for($i=0; $i<10; $i++){
    //     $startTimeBaru = microtime(true);
    //     $descendantsIds = $this->codeBaru($id);
    //     $endTimeBaru = microtime(true);
    //     $totalTimeBaru += ($endTimeBaru - $startTimeBaru);
    //   }
    //   $avgTimeBaru = $totalTimeBaru/10;
    //   // ====== Code Lama ======
    //   $totalTimeLama = 0;
    //   for($i=0; $i<10; $i++){
    //     $startTimeLama = microtime(true);
    //     $descendantsIds = $this->codeLama($id); 
    //     $endTimeLama = microtime(true);
    //     $totalTimeLama += ($endTimeLama - $startTimeLama);
    //   }
    //   $avgTimeLama = $totalTimeLama/10;

    //   // ====================
    //   $customers =  Customer::whereHas('linkDistrict', function($q) use($descendantsIds) {
    //     $q->whereIn('id', $descendantsIds);
    //   })->get();

    //   $customersInvoice = Customer::whereHas('linkDistrict', function($q) use($descendantsIds) {
    //                 $q->whereIn('id', $descendantsIds);
    //               })
    //               ->whereHas('linkOrder', function($q){
    //                 $q->whereHas('linkOrderTrack', function($q){
    //                   $q->where('status_enum', '4');
    //                 });
    //               })->with(['linkOrder', 'linkOrder.linkInvoice', 'linkOrder.linkOrderTrack'])->get();

    //   return response()->json([
    //     'status' => 'success',
    //     'customers' => $customers,
    //     'customersInvoice' => $customersInvoice,
    //     'time' => [
    //       'lama' => $avgTimeLama . ' second',
    //       'baru' => $avgTimeBaru . ' second'
    //     ]
    //   ]);
    // }

    public function getCustByDistrictAPI($id){
      $startTime = microtime(true);

      // $descendantsIds = $this->codeLama($id);
      $descendantsIds = $this->codeBaru($id);

      $customers =  Customer::whereHas('linkDistrict', function($q) use($descendantsIds) {
        $q->whereIn('id', $descendantsIds);
      })->get();

      $customersInvoice = Customer::whereHas('linkDistrict', function($q) use($descendantsIds) {
                    $q->whereIn('id', $descendantsIds);
                  })
                  ->whereHas('linkOrder', function($q){
                    $q->whereHas('linkOrderTrack', function($q){
                      $q->where('status_enum', '4');
                    });
                  })->with(['linkOrder', 'linkOrder.linkInvoice', 'linkOrder.linkOrderTrack'])->get();

      return response()->json([
        'status' => 'success',
        'customers' => $customers,
        'customersInvoice' => $customersInvoice,
        'time' => number_format(( microtime(true) - $startTime), 5).'seconds'
      ]);
    }
}
