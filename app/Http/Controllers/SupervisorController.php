<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class SupervisorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('supervisor/pengguna',[
            'users' => User::all()
        ]);
    }

    public function search()
    {
        $user = DB::table('users')->where('nama','like','%'.request('cari').'%')->get();
       
        return view('supervisor/pengguna',[
            'users' => $user
        ]);
    }

    public function create()
    {
        return view('supervisor/addPengguna');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:20',
            'nomor_telepon' => 'required|min:3|max:15',
            'role' => 'required',
            'foto_profil' => 'image|file|max:1024',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required',
        ]);

        if($request->file('foto_profil')){
            $request->foto_profil = $request->file('foto_profil')->store('profil');
        }
      
        User::create([
            'nama' => $request->nama,
            'nomor_telepon' => $request->nomor_telepon,
            'role' => $request->role,
            'foto_profil' => $request->foto_profil,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/dashboard/pengguna')->with('addDataSuccess','Tambah user berhasil');
    }

    public function show(User $user)
    {
        return view('supervisor/editPengguna',[
            'user' => $user
        ]);
    }

    public function edit(User $user)
    {
        return view('supervisor/editPengguna',[
            'user' => $user
        ]);
    }

    public function update(Request $request, User $user)
    {
        $rules = $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_telepon' => 'required|min:3|max:15',
            'status' => 'required',
            'role' => 'required',
            'email' => 'required|string|email|max:255',                          
        ]);

        User::Where('id', $user->id)
            ->update($rules);

        return redirect('/dashboard/pengguna')->with('updateDataSuccess','Update User '. $user->nama .' Berhasil');        
    }

    
}
