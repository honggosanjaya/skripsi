<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index(){
        $events = Event::paginate(5);
        return view('supervisor/event.index',[
            'events' => $events
        ]);
    }

    public function search(){
        $events = Event::where(strtolower('nama'),'like','%'.request('cari').'%')->paginate(5);
               
        return view('supervisor/event.index',[
            'events' => $events
        ]);
    }

    public function create(){
        $eventStatuses = Status::where('tabel','=','events')->get();
                
        return view('supervisor/event.addevent', [
            'eventStatuses' => $eventStatuses
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
            'gambar' => 'max:1024',
            'keterangan' => 'required'
        ]);
      
        if($request->file('gambar')){
            $file= $request->file('gambar');
            $filename=  $request->tanggal_mulai.'-'.$request->kode_event.'.'.$file->getClientOriginalExtension();
            $request->gambar= $filename;
            $file=$request->file('gambar')->storeAs('event', $filename);
        }

        if($request->event_pilih_isian == "potongan"){
            $potongan = $request->potongan_diskon;
        }
        else{
            $diskon = $request->potongan_diskon;
        }
        
        Event::create([
            'id_staff' => auth()->user()->id,
            'nama' => $request->nama_event,
            'keterangan' => $request->keterangan,
            'diskon' => $diskon,
            'potongan' => $potongan,
            'min_pembelian' => $request->min_pembelian,
            'kode' => $request->kode_event,
            'date_start' => $request->tanggal_mulai,
            'date_end' => $request->tanggal_selesai,
            'status' => $request->status,
            'gambar' => $request->gambar
        ]);
        
        return redirect('/supervisor/event')->with('addEventSuccess','Tambah Event berhasil');
    }

    public function edit(Event $event){
        $eventStatuses = Status::where('tabel','=','events')->get();
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
        
        return view('supervisor/event.ubahevent', [
            'eventStatus' => $event,
            'selections' => $eventStatuses,
            'diskon_potongan' => $diskon_potongan,
            'tipe' => $tipe
        ]);
    }

    public function update(Request $request, Event $event){
        $diskon = null;
        $potongan = null;

        $request->validate([
            'kode_event' => 'required|max:50',
            'nama_event' => 'required|max:255',
            'potongan_diskon' => 'required',
            'min_pembelian' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'gambar' => 'max:1024',
            'keterangan' => 'required'
        ]);

        if($request->file('gambar')){
            if($request->oldGambar){
                Storage::delete($request->oldGambar);
            }
            $file= $request->file('gambar');
            $filename=  $request->tanggal_mulai.'-'.$request->kode_event.'.'.$file->getClientOriginalExtension();
            $request->gambar= $filename;
            $file=$request->file('gambar')->storeAs('event', $filename);
        }
        
        if($request->event_pilih_isian == "potongan"){
            $potongan = $request->potongan_diskon;
        }
        else{
            $diskon = $request->potongan_diskon;
        }
        
        $event->id_staff = auth()->user()->id;
        $event->nama = $request->nama_event;
        $event->keterangan = $request->keterangan;
        $event->diskon = $diskon;
        $event->potongan = $potongan;
        $event->min_pembelian = $request->min_pembelian;
        $event->kode = $request->kode_event;
        $event->date_start = $request->tanggal_mulai;
        $event->date_end = $request->tanggal_selesai;
        $event->status = $request->status;
        $event->gambar = $request->gambar;

        $event->save();        
        
        return redirect('/supervisor/event')->with('updateEventSuccess','Tambah Event berhasil');
    }
}
