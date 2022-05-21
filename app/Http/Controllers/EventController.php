<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Status;
use Illuminate\Http\Request;

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
            $file= $request->file('gambar');
            $filename=  $request->tanggal_mulai.'-'.$request->kode_event.'.'.$file->getClientOriginalExtension();
            $request->gambar= $filename;
            $file=$request->file('gambar')->storeAs('event', $filename);
        }
        
        
    }

    public function edit(Event $event){
        $eventStatuses = Status::where('tabel','=','events')->get();
        return view('supervisor/event.ubahevent', [
            'eventStatus' => $event,
            'selections' => $eventStatuses
        ]);
    }

    public function update(){
        
    }
}
