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
use App\Mail\ConfirmationEmail;
use Illuminate\Support\Facades\Mail;

class RegisteredUserController extends Controller
{
  public function confirmEmail($id){
    User::where('id_users', $id)->update([
      'email_verified_at' => now()
    ]);

    $usertype = User::where('id_users', $id)->first()->tabel;

    if($usertype == 'staffs'){
      $role = Staff::where('id', $id)->first()->linkStaffRole->nama;
    } else{
      $role = 'customer';
    }

    return view('email.confirmemail',[
      'usertype' => $usertype,
      'role' => $role
    ]);
  }


    public function create()
    {
        if (session('role') == 'owner') {
            $roles = StaffRole::where('id',2)->get();
        } elseif (session('role') == 'supervisor'){
            $roles = StaffRole::get()->except([1,2]);
        } else{
            $roles = StaffRole::where('id',1)->get();
        }
        
        return view('auth.register',compact('roles'));
    }

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

        if (StaffRole::find($request->role)->nama=='owner') {
            if (Staff::where('role',1)->count()>0) {
                return redirect('/registerowner')->with('error','Maaf Untuk Role Owner Hanya Diperbolehkan Ada 1, Penambahan Untuk Role Ini DILARANG');
            }
        }

        if($request->file('foto_profil')){
            $file= $request->file('foto_profil');
            $nama_staff = str_replace(" ", "-", $request->nama);
            $filename = 'STF-' . $nama_staff . '-' .date_format(now(),"YmdHis"). '.' . $file->getClientOriginalExtension();
            $request->foto_profil= $filename;
            $file=$request->file('foto_profil')->storeAs('staff', $filename);
        }
       
        $staff= Staff::insertGetId([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telepon' => $request->telepon,
            'role' => $request->role,
            'status_enum' => '1',
            'foto_profil'=> $request->foto_profil,
            'created_at'=>now()
        ]);
        if (Staff::with('linkStaffRole')->find($staff)->linkStaffRole->nama=="supervisor") {
            if (Staff::where('role',2)->where('status_enum','1')->count()>1) {
                //jika spv sudah ada dan masih aktif maka status yang baru di non aktifkan
                $inactive=Staff::find($staff);
                $inactive->status_enum='-1';
                $inactive->save();
            }
        }
        
        $user = User::create([
            'id_users' => $staff,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tabel' => 'staffs',
        ]);

        event(new Registered($user));

        // Auth::login($user);

        $details = [
          'title' => 'Konfirmasi Owner UD Surya dan UD Mandiri',
          'body' => 'Anda hanya perlu mengonfirmasi email anda. Proses ini sangat singkat dan tidak rumit. Anda dapat melakukannya dengan sangat cepat.',
          'user' => Staff::find($staff)
        ];

        // Mail::to($request->email)->send(new ConfirmationEmail($details));  

        if (StaffRole::find($request->role)->nama=='owner') {
            return redirect('/login')->with('success','Registration successfull!');
        }
        
        return redirect('/register')->with('success','Registration successfull!');
    }
}
