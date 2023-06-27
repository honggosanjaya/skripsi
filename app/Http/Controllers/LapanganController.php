<?php

namespace App\Http\Controllers;

use App\Models\CashAccount;
use App\Models\Customer;
use App\Models\GroupItem;
use App\Models\History;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\LaporanPenagihan;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTrack;
use App\Models\Reimbursement;
use App\Models\Staff;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Util;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Validator;

class LapanganController extends Controller
{
  public function jadwalpengiriman(){
    \Cart::session(auth()->user()->id.'retur')->clear();
    $id_staff = auth()->user()->id_users;
    $perludikirims = Order::whereHas('linkOrderTrack',function($q) use($id_staff) {
                        $q->where('id_staff_pengirim', $id_staff);
                      })->where(function ($query) {
                        $query->whereHas('linkOrderTrack',function($qr) {
                          $qr->where('status_enum', '3');
                        });
                      })
                      ->with(['linkOrderTrack','linkInvoice','linkCustomer','linkOrderItem'])
                      ->orderBy('id','DESC')->get();
    // dd($perludikirims);

    $sudahsampais = Order::whereHas('linkOrderTrack',function($q) use($id_staff) {
                      $q->where('id_staff_pengirim', $id_staff);
                    })->where(function ($query) {
                      $query->whereHas('linkOrderTrack',function($qr) {
                        $qr->where('status_enum','>', '3')
                          ->where('status_enum','<=', '6')
                          ->whereBetween('waktu_sampai',[now()->subDays(2),now()]);
                      });
                    })
                    ->with(['linkOrderTrack','linkInvoice','linkCustomer','linkOrderItem'])
                    ->orderBy('id','DESC')->get();
    // dd($sudahsampais);
    return view('react.jadwalpengiriman',[
      'page' => 'Jadwal Pengiriman',
      'linkback' => '/'.session('role'),
      'perludikirims' => $perludikirims,
      'sudahsampais' => $sudahsampais
    ]);            
  }

  public function konfirmasiPengirimanSampai(Request $request, $id){  
    // dd($request->all());
      if($request->bukti_galeri ?? null){
        $file = $request->file('bukti_galeri');
        $file_name=  'DLV-'.$id.'.'.$file->getClientOriginalExtension();
        Image::make($request->file('bukti_galeri'))->resize(350, null, function ($constraint) {
          $constraint->aspectRatio();
        })->save(public_path('storage/pengiriman/') . $file_name);
      }elseif($request->bukti_kamera ?? null){
        $file = $request->file('bukti_kamera');
        $file_name=  'DLV-'.$id.'.'.$file->getClientOriginalExtension();
        Image::make($request->file('bukti_kamera'))->resize(350, null, function ($constraint) {
          $constraint->aspectRatio();
        })->save(public_path('storage/pengiriman/') . $file_name);
      }

      Util::backupFile(public_path('storage/pengiriman/'.$file_name),'salesman-surya/storage/pengiriman/');

      OrderTrack::where('id_order', $id)->update([
        'foto_pengiriman' => $file_name,
        'status_enum' => '4',
        'waktu_sampai' => now()
      ]);

      $ordertracks = OrderTrack::all();
      $vehicleInRoads = array();

      foreach($ordertracks as $ordertrack){
        if($ordertrack->status_enum == '3'){
          array_push($vehicleInRoads, [
            'id_vehicle' => $ordertrack->id_vehicle
          ]);
        }
      }

      DB::table('vehicles')->update(['is_active' => true]);
      foreach ($vehicleInRoads as $vehicle) {
        Vehicle::where('id', $vehicle['id_vehicle'])->update([
          'is_active' => false
        ]);
      }

      return redirect('/lapangan/jadwal')->with('successMessage', 'Berhasil mengonfirmasi pengiriman sampai');
  }

  public function penagihan(){
    $tagihans = LaporanPenagihan::where('id_staff_penagih',auth()->user()->id_users)
                  ->orderBy('tanggal', 'ASC')
                  ->with('linkInvoice')
                  ->get();

    return view('shipper.penagihan',[
      'page' => 'LP3',
      'tagihans' => $tagihans,
      'linkback' => '/'.session('role'),
    ]);    
  }

  public function reimbursement(){
    $histories = Reimbursement::where('id_staff_pengaju', auth()->user()->id_users)
                ->orderBy('created_at','DESC')
                ->with(['linkStaffPengonfirmasi', 'linkCashAccount'])
                ->get();

    $cashaccounts = CashAccount::all();
    $temp = array();
    
    for($i=0; $i<count($cashaccounts); $i++){
      $get1 = '';
      $get2 = '';
      $value = 0;
      
      if($cashaccounts[$i]->account_parent == null){
        $get1 = $cashaccounts[$i]->nama;
        $value = $cashaccounts[$i]->id;
        $default = $cashaccounts[$i]->default;
        $account = $cashaccounts[$i]->account;
        array_push($temp, [$get1, $value, $default, $account]);
      }
  
      else if($cashaccounts[$i]->account_parent != null){
        for($j=count($cashaccounts)-1; $j>=0; $j--){
          if($cashaccounts[$i]->account_parent == $cashaccounts[$j]->account){
            $get2 = $temp[$j][0] . " - " .$cashaccounts[$i]->nama;
            $value = $cashaccounts[$i]->id;
            $default = $cashaccounts[$i]->default;
            $account = $cashaccounts[$i]->account;
            array_push($temp, [$get2, $value, $default, $account]);
          }
        }
      }
    }
    usort($temp, function($a, $b) {
        return $a[0] <=> $b[0];
    });

    return view('react.reimbursement',[
      'page' => 'Reimbursement',
      'histories' => $histories,
      'cashaccount' => $temp,
      'linkback' => '/'.session('role')
    ]);  
  }

  public function storeReimbursement(Request $request){
    // dd($request->all());
    $validator = Validator::make($request->all(), [
      'id_cash_account' => ['required'],
      'jumlah_uang' => ['required','numeric'],
      'keterangan_pengajuan' => ['nullable'],
    ]);

    if ($validator->fails()) {
      return redirect('/lapangan/reimbursement/')
                  ->withErrors($validator)
                  ->withInput();
    }
    
    $validatedData = $validator->validated();
    $validatedData['id_staff_pengaju'] = auth()->user()->id_users;
    $validatedData['status_enum'] = '0';
    $validatedData['created_at'] = now();
    $validatedData['updated_at'] = now();

    if ($request->gambar) {
      $file= $request->file('gambar');
      $nama_pengaju = Staff::find(auth()->user()->id_users)->nama;
      $file_name = 'RBS-' . $nama_pengaju. '-' .date_format(now(),"YmdHis"). '.' . $file->getClientOriginalExtension();
      Image::make($request->file('gambar'))->resize(350, null, function ($constraint) {
        $constraint->aspectRatio();
      })->save(public_path('storage/reimbursement/'). $file_name, 80);
      $validatedData['foto'] = $file_name;
      Util::backupFile(public_path('storage/reimbursement/'.$file_name),'salesman-surya/storage/reimbursement/');
    }  

    Reimbursement::insert($validatedData);
    return redirect('/lapangan/reimbursement')->with('successMessage', 'Berhasil mengajukan reimbursement');
  }

  public function retur(Request $request, $idCust){
    $id_invoice = $request->idinvoice ?? null;
    if($request->idinvoice ?? null){
      $linkback = '/salesman/trip/'.$idCust;
    }else{
      $linkback = '/lapangan/jadwal';
    }
    $latestOrderItem = [];
    $histories = History::select('id_item')->where('id_customer',$idCust)->get();
    
    foreach($histories as $h){
      $query = OrderItem::where('id_item',$h['id_item'])
      ->whereHas('linkOrder', function($q) use($idCust){
          $q->where('id_customer', $idCust);
        })
      ->join('order_tracks','order_items.id_order','=','order_tracks.id_order')
      ->select('order_items.id', 'order_items.id_order' ,'id_item', 'harga_satuan', 'order_tracks.waktu_diteruskan' ,'order_items.created_at')
      ->where('order_tracks.waktu_diteruskan', '!=', null)
      ->latest()->first();

      $latestOrderItem[$h->id_item] = array(
        $query,
      );
    }

    $orderItemUnconfirmed = OrderItem::whereHas('linkOrder',function($q) {
                  $q->where('status_enum', '-1');
                })
                ->whereHas('linkOrder',function($q) {
                  $q->whereHas('linkOrderTrack',function($q) {
                    $q->where('status_enum','!=', '-1');
                  });
                })
                ->select('id_item', DB::raw('SUM(kuantitas) as jumlah_blmkonfirmasi'))      
                ->groupBy('id_item')->pluck('jumlah_blmkonfirmasi','id_item')->all();


    $groupItems = GroupItem::whereHas('linkItem', function($q) {
                    $q->where('status_enum', '1')->where('stok','>',0);
                  })->get()->groupBy('id_group_item');

    $groupingItemStok = [];
    foreach($groupItems as $groupItem){
      $groupingStok = [];
      foreach($groupItem as $group){
        $item = Item::find($group->id_item);
        $groupStok = floor(($item->stok / ($group->value_item / $group->value)));
        array_push($groupingStok, (int)$groupStok);
      }
      $groupingItemStok[$groupItem[0]->id_group_item] = min($groupingStok);
    }

    return view('react.retur',[
      'page' => 'Retur',
      "history" => History::where('id_customer',$idCust)->with(['linkItem', 'linkItem.linkGroupingItem'])->get(),
      "customer" => Customer::where('id',$idCust)->with('linkCustomerType')->first(),
      "latestOrderItems" => $latestOrderItem,
      "orderRealTime" => $orderItemUnconfirmed,
      "groupingItemStok" => $groupingItemStok,
      'cartItems' => \Cart::session(auth()->user()->id.'retur')->getContent(),
      'id_invoice' => $id_invoice,
      'linkback' => $linkback
    ]);  
  }

  public function returAddToCart(Request $request){
    // dd($request->all());
    $cartItem = \Cart::session(auth()->user()->id.'retur')->get($request->id_item);

    if($cartItem !== null){
      if($request->kuantitas == 0){
        \Cart::session(auth()->user()->id.'retur')->remove($request->id_item);
      }else{
        \Cart::session(auth()->user()->id.'retur')->update(
          $request->id_item,
          [
              'quantity' => [
                  'relative' => false,
                  'value' => $request->kuantitas
              ],
              'attributes' => array(
                'alasan' => $request->alasan,
                'gambar' => $request->gambar
              )
          ]
        );
      }
    } else if($cartItem == null){
      \Cart::session(auth()->user()->id.'retur')->add([
        'id' => $request->id_item,
        'name' => $request->nama_item,
        'price' => $request->harga_satuan,
        'quantity' => $request->kuantitas,
        'attributes' => array(
          'alasan' => $request->alasan,
          'gambar' => $request->gambar
        )
      ]);
    }

    $cartItems = \Cart::session(auth()->user()->id.'retur')->getContent()->toArray();
    return response()->json([
      'status' => 'success',
      'message' => 'Produk berhasil ditambahkan ke keranjang',
      'data' => array_values($cartItems)
    ]);
  }

  public function returClearCart(){
    \Cart::session(auth()->user()->id.'retur')->clear();
    return response()->json([
      'status' => 'success',
      'message' => 'Berhasil menghapus keranjang',
    ]);
  }
  
}
