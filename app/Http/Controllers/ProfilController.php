<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function index(User $user)
    {
        //dd($user->nama);
        return view('adminSupervisor/editProfil', [
            'user' => $user
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:20',
            'nomor_telepon' => 'required|min:3|max:15',
            'email' => 'required|string|email|max:255',
            'foto_profil' => 'image|file|max:1024',
            'alamat' => 'string|max:255',
            'tanggal_lahir' => 'string|max:255',
            'agama' => 'string|max:255',
            'nama_wali' => 'string|max:20',
            'nomer_telepon_wali' => 'min:3|max:15',
            'status_wali' => 'string|max:255',

        ]);

        if($request->file('foto_profil')){
            if($request->oldProfil){
                Storage::delete($request->oldProfil);
            }
            $validatedData['foto_profil'] = $request->file('foto_profil')->store('profil');
        }

        User::where('id', $user->id)
            ->update($validatedData);      
        
        return redirect('/dashboard/pengguna');
    }
}
