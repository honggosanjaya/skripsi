<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Staff;
use App\Models\StaffRole;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        if (session('role') == 'owner') {
            $roles = StaffRole::where('id',2)->get();
        } else {
            $roles = StaffRole::get()->except([1,2]);
        }
        
        return view('auth.register',compact('roles'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'telepon' => ['required', 'min:3', 'max:15'],
            'foto_profil' => 'max:1024',
            'role' => ['required'],
        ]);

        if($request->file('foto_profil')){
            $file= $request->file('foto_profil');
            $filename=  date('Y-m-d').'-'.$request->nama.'-'.$request->email.'.'.$file->getClientOriginalExtension();
            $request->foto_profil= $filename;
            $file=$request->file('foto_profil')->storeAs('staff', $filename);
        }
       
        $staff= Staff::insertGetId([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telepon' => $request->telepon,
            'role' => $request->role,
            'status' => 8,
            'foto_profil'=> $request->foto_profil
        ]);

        if (Staff::where('role',2)->where('status',8)->count()>1) {
            //jika spv sudah ada dan masih aktif maka status yang baru di non aktifkan
            $inactive=Staff::find($staff);
            $inactive->status=9;
            $inactive->save();
        }
        
        $user = User::create([
            'id_users' => $staff,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tabel' => 'staffs',
        ]);

        event(new Registered($user));

        // Auth::login($user);

        return redirect('/register')->with('success','Registration successfull!');
    }
}
