<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function check(Request $request, User $user)
    {
        $validatePassword = $request->validate([
            'passwordLama' => 'required'
        ]);
        $dataUser = '';
        if($user->tabel == 'staffs'){
            $dataUser = Staff::firstWhere('id',$user->id_users);
        }
        else{
            $dataUser = Customer::firstWhere('id',$user->id_users);
        }
        
        if(Hash::check($validatePassword['passwordLama'],$dataUser->password) && $user->tabel == 'staffs'){
            return redirect($user->linkStaff->linkStaffRole->nama.'/profil/ubahpasswordbaru/'.$user->id_users)
            ->with('passwordSuccess','Password ditemukan, silakan ganti password baru');            
        }
        else if(Hash::check($validatePassword['passwordLama'],$dataUser->password) && $user->tabel == 'customers'){
            return redirect('customer/profil/ubahpasswordbaru/'.$user->id_users)
            ->with('passwordSuccess','Password ditemukan, silakan ganti password baru');   
        }
        return back()->with('passwordError','Password tidak sama');
    }

    public function passwordBaru(User $user){
        if($user->tabel == 'staffs'){
            return view($user->linkStaff->linkStaffRole->nama.'/profil.ubahpasswordbaru');
        }
        else{
            return view('customer/profil.ubahpasswordbaru');
        }
    }

    public function gantiPassword(Request $request, User $user)
    {
        //dd('muncul');
        $validateData = $request->validate([
            'password' => 'required',                      
        ]);
        
        if($validateData['password'] != $request->konfirmasiPasswordBaru){
            return back()->with('matchError','Password tidak sama');
        }

        $validateData['password'] = Hash::make($validateData['password']);
        
        if($user->tabel == 'staffs'){
            $user->password = $validateData['password'];
            $data = Staff::where('id','=',auth()->user()->id_users)->first();

            $user->save();
            Staff::where('id',$user->id_users)
            ->update($validateData);
            return redirect($user->linkStaff->linkStaffRole->nama.'/profil')
            ->with('passwordSuccess','Password berhasil diubah');
        }
        else{
            $user->password = $validateData['password'];
            $data = Customer::where('id','=',auth()->user()->id_users)->first();

            $user->save();
            Customer::where('id',$user->id_users)
            ->update($validateData);
            return redirect('customer/profil')
            ->with('passwordSuccess','Password berhasil diubah');
        }       
        
    }
}
