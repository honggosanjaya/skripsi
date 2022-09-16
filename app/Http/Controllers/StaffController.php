<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\StaffRole;
use App\Models\User;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules;
use Intervention\Image\ImageManagerStatic as Image;
use App\Mail\ConfirmationEmail;
use Illuminate\Support\Facades\Mail;

class StaffController extends Controller
{
    public function index()
    {
      return view('supervisor.datastaf.index', [
        'stafs' => Staff::whereNotIn('role', [1, 2])->paginate(10),
        "title" => "List Tim Marketing UD Mandiri"
      ]);
    }

    public function datasupervisor(){
      $role_spv = StaffRole::where('nama', 'supervisor')->first();
      $supervisors = Staff::where('role', $role_spv->id)->paginate(10);

      return view('owner.dataSupervisor.index', [
        'supervisors' => $supervisors,
        "title" => "Data Supervisor"
      ]);
    }

    public function create()
    {
      $statuses = [
        1 => 'active',
        -1 => 'inactive',
      ];

      return view('supervisor.datastaf.create', [
        'stafs' => Staff::all(),
        'roles' => StaffRole::whereNotIn('nama', ['owner', 'supervisor'])->get(),
        'statuses' => $statuses,
        "title" => "Data Tim Markting - Add"
      ]);
    }

    public function createSupervisor()
    {
      $statuses = [
        1 => 'active',
        -1 => 'inactive',
      ];
      
      return view('owner.dataSupervisor.create', [
        'roles' => StaffRole::where('nama', 'supervisor')->get(),
        'statuses' => $statuses,
        "title" => "Data Supervisor - Add"
      ]);
    }

    public function store(Request $request){
      $rules = ([
        'nama' => ['required', 'string', 'max:255'],
        'email' => ['string', 'email', 'max:255', 'unique:users'],
        'telepon' => ['required','string', 'max:15'],
        'foto_profil' => ['image','file','max:1024'],
        'role' => ['required'],
        'status_enum' => ['required'],
      ]);

      $validatedData = $request->validate($rules);

      $validatedData['password'] = Hash::make(12345678);
      $validatedData['role'] = $request->role;
      $validatedData['status_enum'] = $request->status_enum;
      $validatedData['created_at'] = now();

      if ($request->foto_profil) {
        $nama_staff = str_replace(" ", "-", $validatedData['nama']);
        $file= $request->file('foto_profil');
        $file_name = 'STF-'. $nama_staff.'-' .date_format(now(),"YmdHis").'.'.  $file->getClientOriginalExtension();
        Image::make($request->file('foto_profil'))->resize(350, null, function ($constraint) {
          $constraint->aspectRatio();
        })->save(public_path('storage/staff/') . $file_name);
        $validatedData['foto_profil'] = $file_name;
      }    

      $staff=Staff::insertGetId($validatedData);

      if (Staff::with('linkStaffRole')->find($staff)->linkStaffRole->nama=="supervisor") {
        if (Staff::where('role',2)->where('status_enum','1')->count()>1) {
          $inactive=Staff::find($staff);
          $inactive->status_enum='-1';
          $inactive->save();
        }
      }

      $user = User::create([
        'id_users' => $staff,
        'email' => $request->email,
        'password' => $validatedData['password'],
        'tabel' => 'staffs',
      ]);

      $details = [
        'title' => 'Konfirmasi Supervisor Marketing',
        'body' => 'Anda hanya perlu mengonfirmasi email anda. Proses ini sangat singkat dan tidak rumit. Anda dapat melakukannya dengan sangat cepat.',
        'user' => Staff::find($staff)
      ];
      
      // Mail::to($request->email)->send(new ConfirmationEmail($details));  

      event(new Registered($user));

      if($request->route == 'tambahsupervisor'){
        return redirect('/owner/datasupervisor') -> with('pesanSukses', 'Staf berhasil ditambahkan');
      }
      return redirect('/supervisor/datastaf') -> with('pesanSukses', 'Staf berhasil ditambahkan');
    }

    public function edit($id){
      $statuses = [
        1 => 'active',
        -1 => 'inactive',
      ];

      return view('supervisor.datastaf.edit',[
        'staf' => Staff::where('id', $id)->first(),
        'roles' => StaffRole::whereNotIn('nama', ['owner', 'supervisor'])->get(),
        'statuses' => $statuses,
        "title" => "Data Tim Markting - Edit"
      ]);
    }

    public function editSupervisor(Staff $staff){
      $statuses = [
        1 => 'active',
        -1 => 'inactive',
      ];
      
      return view('owner.dataSupervisor.edit',[
        'supervisor' => $staff,
        'roles' => StaffRole::where('nama', 'supervisor')->get(),
        'statuses' => $statuses,
        "title" => "Data Tim Markting - Edit"
      ]);
    }

    public function update(Request $request, $id){
      $rules = [
        'nama' => ['required', 'string', 'max:255'],
        'telepon' => ['required','string', 'max:15'],
        'foto_profil' => ['image','file','max:1024'],
        'role' => ['required'],
        'status_enum' => ['required'],
      ];

      if($request->email !== Staff::where('id', $id)->first()->email){
        $rules['email'] = 'string|email|max:255|unique:users';
      }

      $validatedData = $request->validate($rules);
      $validatedData['role'] = $request->role;
      $validatedData['status_enum'] = $request->status_enum;

      if ($request->foto_profil) {
        if($request->oldGambar){
          \Storage::delete('/staff/'.$request->oldGambar);
        }

        $nama_staff = str_replace(" ", "-", $validatedData['nama']);
        $file= $request->file('foto_profil');
        $file_name = 'STF-'. $nama_staff.'-' .date_format(now(),"YmdHis").'.'.  $file->getClientOriginalExtension();
        Image::make($request->file('foto_profil'))->resize(350, null, function ($constraint) {
          $constraint->aspectRatio();
        })->save(public_path('storage/staff/') . $file_name);
        $validatedData['foto_profil'] = $file_name;
      }    
      
      Staff::where('id', $id)->update($validatedData);

      if($request->route == 'editsupervisor'){
        if($request->status_enum == '1'){
          if (Staff::where('role',2)->where('status_enum','1')->count()>1) {
            $inactive=Staff::where('role',2)->where('status_enum','1')->where('id','!=',$id)->first();
            $inactive->status_enum='-1';
            $inactive->save();
          }
        }
        return redirect('/owner/datasupervisor') -> with('pesanSukses', 'Data supervisor '. Staff::where('id', $id)->first()->nama.' berhasil diubah');
      }

      return redirect('/supervisor/datastaf') -> with('pesanSukses', 'data staf berhasil diubah');
    }

    public function editStatusStaf(Request $request, Staff $staf){
      $status = $staf->status_enum;

      if($status === '1'){
        Staff::where('id', $staf->id)->update(['status_enum' => '-1']);
      }else if($status === '-1'){
        Staff::where('id', $staf->id)->update(['status_enum' => '1']);
        if($request->route == 'editstatussupervisor'){
          if (Staff::where('role',2)->where('status_enum','1')->count()>1) {
            $inactive=Staff::where('role',2)->where('status_enum','1')->where('id','!=',$staf->id)->first();
            $inactive->status_enum='-1';
            $inactive->save();
          }
        }
      }

      if($request->route == 'editstatussupervisor'){
        return redirect('/owner/datasupervisor') -> with('pesanSukses', 'Berhasil ubah status '.$staf->nama);
      }

      return redirect('/supervisor/datastaf') -> with('pesanSukses', 'Berhasil ubah status '.$staf->nama);
    }

    public function cariSupervisor(){
      $supervisors = Staff::where('role', 2)->where(strtolower('nama'),'like','%'.request('cari').'%')->paginate(10);
             
      return view('owner.datasupervisor.index',[
          'supervisors' => $supervisors
      ]);
    }

    public function getHistoryTripApi(Request $request, $id){
      $alltrip = Trip::where('id_staff', $id)->with(['linkCustomer', 'linkCustomer.linkDistrict'])->get();
      $date = $request->date;
      $dateTrip = Trip::where('id_staff', $id)->whereDate('created_at', '=', $date)->with(['linkCustomer', 'linkCustomer.linkDistrict'])->get();

      if($date == null){
        return response()->json([
          'data' => $alltrip,
          'status' => 'success'
        ]);
      }else{
        return response()->json([
          'data' => $dateTrip,
          'status' => 'success'
        ]);
      }
    }

    public function detailStaff(Staff $staff){
      return view('supervisor.datastaf.detail', [
        'staff' => $staff,
      ]);
    }

    public function detailDatasupervisor(Staff $staff){
      return view('owner.dataSupervisor.detail', [
        'staff' => $staff,
      ]);
    }
}
