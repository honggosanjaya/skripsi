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
        'stafs' => Staff::whereNotIn('role', [1, 2])
                ->OrderBy('status_enum', 'ASC')
                ->orderBy('id', 'DESC')
                ->paginate(10),
        "title" => "List Tim Marketing".config('app.company_name')
      ]);
    }

    public function datasupervisor(){
      $role_spv = StaffRole::where('nama', 'supervisor')->first();
      $supervisors = Staff::where('role', $role_spv->id)
                      ->OrderBy('status_enum', 'ASC')
                      ->orderBy('id', 'DESC')
                      ->paginate(10);

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
      $request->validate([
        'nama' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'telepon' => ['required', 'min:3', 'max:15'],
        'foto_profil' => 'max:1024',
        'role' => ['required'],
      ]);

      if($request->file('foto_profil')){
        $file= $request->file('foto_profil');
        $nama_staff = str_replace(" ", "-", $request->nama);
        $filename = 'STF-' . $nama_staff . '-' .date_format(now(),"YmdHis"). '.' . $file->getClientOriginalExtension();
        Image::make($request->file('foto_profil'))->resize(350, null, function ($constraint) {
          $constraint->aspectRatio();
        })->save(public_path('storage/staff/') . $filename);
        $request->foto_profil= $filename;
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
        'password' => Hash::make('12345678'),
        'tabel' => 'staffs',
    ]);

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

      $dateStart = $request->date." 00:00:00";
      $dateEnd = $request->date." 23:59:59";

      $dateTrip = Trip::whereBetween('waktu_masuk', [$dateStart, $dateEnd])
      ->where('id_staff', $id)
      ->with(['linkCustomer', 'linkCustomer.linkDistrict'])->get();

      if(!$request->date ?? null){
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
