<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules;
use Intervention\Image\ImageManagerStatic as Image;
use Util;

class EventController extends Controller
{
  public function dataKodeEventAPI($kode){
    $event = Event::where('kode', $kode)->where('status_enum','!=', '-2')->first();
  
    if($event!==null){
      return response()->json([
        'status' => 'success',
        'data' => $event,
      ]);
    } else{
      return response()->json([
        'status' => 'error',
        'message' => 'kode event tidak ditemukan'
      ]);
    }
  }

  public function index(){
    $events = Event::where('status_enum','!=', '-2')->orderBy('id', 'DESC')->paginate(10);
    return view('supervisor.event.index',[
      'events' => $events
    ]);
  }

  public function create(){
    $statuses = [
      1 => 'active',
      -1 => 'inactive',
      0 => 'belum mulai'
    ];
            
    return view('supervisor.event.addevent', [
      'statuses' => $statuses
    ]);
  }

  public function store(Request $request){
    $diskon = null;
    $potongan = null;

    $request->validate([
      'kode_event' => 'required|max:50|unique:events,kode',
      'nama_event' => 'required|max:255',
      'potongan_diskon' => 'required',
      'min_pembelian' => 'required',
      'tanggal_mulai' => 'required|date',
      'tanggal_selesai' => 'required|date',
      'gambar' => 'max:1024|mimes:jpg,png',
      'keterangan' => 'nullable'
    ]);
  
    if($request->gambar){
      $nama_event = str_replace(" ", "-", $request->nama);
      $file= $request->file('gambar');
      $filename=  'EVN-'.$nama_event.'.'.$file->getClientOriginalExtension();
      $request->gambar= $filename;
      Image::make($request->file('gambar'))->resize(350, null, function ($constraint) {
        $constraint->aspectRatio();
      })->save(public_path('storage/event/') . $filename);

      Util::backupFile(public_path('storage/event/'.$filename),'salesman-surya/storage/event/');
    }

    if($request->event_pilih_isian == "potongan"){
      $potongan = $request->potongan_diskon;
    }
    else{
      $diskon = $request->potongan_diskon;
    }
    
    Event::create([
      'id_staff' => auth()->user()->id_users,
      'nama' => $request->nama_event,
      'keterangan' => $request->keterangan,
      'diskon' => $diskon,
      'potongan' => $potongan,
      'min_pembelian' => $request->min_pembelian,
      'kode' => $request->kode_event,
      'date_start' => $request->tanggal_mulai,
      'date_end' => $request->tanggal_selesai,
      'status_enum' => $request->status_enum,
      'gambar' => $request->gambar
    ]);
    
    return redirect('/supervisor/event')->with('successMessage','Tambah Event berhasil');
  }

  public function edit(Event $event){
    $statuses = [
      1 => 'active',
      -1 => 'inactive',
      0 => 'belum mulai'
    ];

      $diskon_potongan = 0;
      $tipe = '';

      if($event->diskon != null){
          $diskon_potongan = $event->diskon;
          $tipe = 'diskon';
      }
      else{
          $diskon_potongan = $event->potongan;
          $tipe = 'potongan';
      }
      
      return view('supervisor.event.ubahevent', [
          'eventStatus' => $event,
          'statuses' => $statuses,
          'diskon_potongan' => $diskon_potongan,
          'tipe' => $tipe
      ]);
  }

  public function update(Request $request, Event $event){
      $diskon = null;
      $potongan = null;
      $foto = '';

      $request->validate([
        'kode_event' => 'required|max:50',
        'nama_event' => 'required|max:255',
        'potongan_diskon' => 'required',
        'min_pembelian' => 'required',
        'tanggal_mulai' => 'required|date',
        'tanggal_selesai' => 'required|date',
        'gambar' => 'max:1024|mimes:jpg,png',
        'keterangan' => 'nullable'
      ]);
      

      $searchKodes = Event::get();
      foreach($searchKodes as $searchKode){
        if($request->kode_event == $searchKode->kode && $searchKode->id != $event->id){
            return redirect('/supervisor/event/ubah/'.$event->id)->with('error','Kode sudah ada');
        }
      }

      if($request->file('gambar')){
        if($request->oldGambar){
            Storage::delete($request->oldGambar);
        }

        $nama_event = str_replace(" ", "-", $event->nama);
        $file= $request->file('gambar');
        $filename=  'EVN-'.$nama_event.'.'.$file->getClientOriginalExtension();
        $request->gambar= $filename;
        $foto = $request->gambar;
        Image::make($request->file('gambar'))->resize(350, null, function ($constraint) {
          $constraint->aspectRatio();
        })->save(public_path('storage/event/') . $filename);

        Util::backupFile(public_path('storage/event/'.$filename),'salesman-surya/storage/event/');
      }
      else{
        $foto = $request->oldGambar;
      }
              
      if($request->event_pilih_isian == "potongan"){
        $potongan = $request->potongan_diskon;
      }
      else{
        $diskon = $request->potongan_diskon;
      }
      
      $event->id_staff = auth()->user()->id_users;
      $event->nama = $request->nama_event;
      $event->keterangan = $request->keterangan;
      $event->diskon = $diskon;
      $event->potongan = $potongan;
      $event->min_pembelian = $request->min_pembelian;
      $event->kode = $request->kode_event;
      $event->date_start = $request->tanggal_mulai;
      $event->date_end = $request->tanggal_selesai;
      $event->status_enum = $request->status_enum;
      $event->gambar = $foto;

      $event->save();        
      
      return redirect('/supervisor/event')->with('successMessage','Ubah Event berhasil');
  }

  public function delete(Request $request, Event $event){
      $event->update(['status_enum'=>'-2']);        
      
      return redirect('/supervisor/event')->with('successMessage','data Event berhasil dihapus');
  }

  //Controller untuk Customer
  public function customerIndex(){
    $events = Event::where('status_enum','!=', '-2')->orderBy('id', 'DESC')->paginate(10);

    return view('customer.event',[
        'events' => $events
    ]);
  }

  public function customerSearch(){
    $events = Event::where('status_enum','!=', '-2')->where(strtolower('nama'),'like','%'.request('cari').'%')
    ->orWhere(strtolower('kode'),'like','%'.request('cari').'%')
    ->paginate(10);
            
    return view('customer.event',[
        'events' => $events
    ]);
  }
}
