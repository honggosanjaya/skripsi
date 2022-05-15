<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UbahPasswordController extends Controller
{
    public function index(User $user)
    {
        return view('adminSupervisor/editPasswordLama', [
            'user' => User::firstWhere('id',$user->id)
        ]);
    }

    public function indexPasswordBaru(User $user)
    {
        return view('adminSupervisor/editPasswordBaru', [
            'user' => User::firstWhere('id',$user->id)
        ]);
    }

    public function check(Request $request, User $user)
    {
        $validatePassword = $request->validate([
            'passwordLama' => 'required'
        ]);

        $dataUser = User::firstWhere('id',$user->id);
        
        if(Hash::check($validatePassword['passwordLama'],$dataUser->password)){
            return redirect('/dashboard/profil/ubahpasswordbaru/'.$dataUser->id)
            ->with('passwordSuccess','Password ditemukan, silakan ganti password baru');            
        }
        return back()->with('passwordError','Password tidak sama');
    }

    public function gantiPassword(Request $request, User $user)
    {
        //dd('muncul');
        $validateData = $request->validate([
            'password' => 'required'            
        ]);

        if($validateData['password'] != $request->konfirmasiPasswordBaru){
            return back()->with('matchError','Password tidak sama');
        }

        $validateData['password'] = Hash::make($validateData['password']);
        
        User::where('id',$user->id)
            ->update($validateData);

        return redirect('/dashboard');
    }
}
