<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index()
    {
        return view('register.index');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|max:255',
            'nomor_telepon' => ['required', 'min:3', 'max:15'],
            'email' => 'required|email:dns|unique:users',
            'role' => 'required',
            'password' => 'required|min:5|max:255'
        ]);

        if($request->file('foto_profil')){
            $validatedData['foto_profil'] = $request->file('foto_profil')->store('profil');
        }

        $validatedData['password'] = Hash::make($validatedData['password']);
        
        User::create($validatedData);

        return redirect('/login');
    }
}


