<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\StaffRole;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules;

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
      // $supervisors = [];
      // $staffs = Staff::all();
      // foreach($staffs as $staf){
      //   if($staf->linkStaffRole->nama == 'supervisor'){
      //     array_push($supervisors, $staf);
      //   }
      // }

      $role_spv = StaffRole::where('nama', 'supervisor')->first();
      $supervisors = Staff::where('role', $role_spv->id)->paginate(10);

      return view('owner.dataSupervisor.index', [
        'supervisors' => $supervisors,
        "title" => "Data Supervisor"
      ]);
    }

    public function create()
    {
      return view('supervisor.datastaf.create', [
        'stafs' => Staff::all(),
        'roles' => StaffRole::whereNotIn('nama', ['owner', 'supervisor'])->get(),
        'statuses' => Status::where('tabel', 'staffs')->get(),
        "title" => "Data Tim Markting - Add"
      ]);
    }

    public function createSupervisor()
    {
      return view('owner.dataSupervisor.create', [
        'roles' => StaffRole::where('nama', 'supervisor')->get(),
        'statuses' => Status::where('tabel', 'staffs')->get(),
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
        'status' => ['required'],
      ]);

      $validatedData = $request->validate($rules);

      $validatedData['password'] = Hash::make(12345678);
      $validatedData['role'] = $request->role;
      $validatedData['status'] = $request->status;
      $validatedData['created_at'] = now();

      if ($request->foto_profil) {
        $file_name = date('dFY') .'-'. $validatedData['nama'].'.' . $request->foto_profil->extension();
        $request->foto_profil->move(public_path('storage/staff'), $file_name);
        $validatedData['foto_profil'] = $file_name;
      }    

      $staff=Staff::insertGetId($validatedData);

      if (Staff::with('linkStaffRole')->find($staff)->linkStaffRole->nama=="supervisor") {
        if (Staff::where('role',2)->where('status',8)->count()>1) {
          $inactive=Staff::find($staff);
          $inactive->status=9;
          $inactive->save();
        }
      }

      $user = User::create([
        'id_users' => $staff,
        'email' => $request->email,
        'password' => $validatedData['password'],
        'tabel' => 'staffs',
      ]);

      event(new Registered($user));

      if($request->route == 'tambahsupervisor'){
        return redirect('/owner/datasupervisor') -> with('pesanSukses', 'Staf berhasil ditambahkan');
      }
      return redirect('/supervisor/datastaf') -> with('pesanSukses', 'Staf berhasil ditambahkan');
    }

    public function edit($id){
      return view('supervisor.datastaf.edit',[
        'staf' => Staff::where('id', $id)->first(),
        'roles' => StaffRole::whereNotIn('nama', ['owner', 'supervisor'])->get(),
        'statuses' => Status::where('tabel', 'staffs')->get(),
        "title" => "Data Tim Markting - Edit"
      ]);
    }

    public function editSupervisor(Staff $staff){
      return view('owner.dataSupervisor.edit',[
        'supervisor' => $staff,
        'roles' => StaffRole::where('nama', 'supervisor')->get(),
        'statuses' => Status::where('tabel', 'staffs')->get(),
        "title" => "Data Tim Markting - Edit"
      ]);
    }

    public function update(Request $request, $id){
      $rules = [
        'nama' => ['required', 'string', 'max:255'],
        'telepon' => ['required','string', 'max:15'],
        'foto_profil' => ['image','file','max:1024'],
        'role' => ['required'],
        'status' => ['required'],
      ];

      if($request->email !== Staff::where('id', $id)->first()->email){
        $rules['email'] = 'string|email|max:255|unique:users';
      }

      $validatedData = $request->validate($rules);
      $validatedData['role'] = $request->role;
      $validatedData['status'] = $request->status;

      if ($request->foto_profil) {
        $file_name = date('dFY') .'-'. $validatedData['nama'].'.' . $request->foto_profil->extension();
        $request->foto_profil->move(public_path('storage/staff'), $file_name);
        $validatedData['foto_profil'] = $file_name;
      }    
      
      Staff::where('id', $id)->update($validatedData);

      if($request->route == 'editsupervisor'){
        if($request->status == 8){
          if (Staff::where('role',2)->where('status',8)->count()>1) {
            $inactive=Staff::where('role',2)->where('status',8)->where('id','!=',$id)->first();
            $inactive->status=9;
            $inactive->save();
          }
        }
        return redirect('/owner/datasupervisor') -> with('pesanSukses', 'Data supervisor '. Staff::where('id', $id)->first()->nama.' berhasil diubah');
      }

      return redirect('/supervisor/datastaf') -> with('pesanSukses', 'data staf berhasil diubah');
    }

    public function editStatusStaf(Request $request, Staff $staf){
      $status = $staf->status;
      $nama_status = Status::where('id', $status)->first()->nama; 

      if($nama_status === 'active'){
        Staff::where('id', $staf->id)->update(['status' => 9]);
      }else if($nama_status === 'inactive'){
        Staff::where('id', $staf->id)->update(['status' => 8]);
        if($request->route == 'editstatussupervisor'){
          if (Staff::where('role',2)->where('status',8)->count()>1) {
            $inactive=Staff::where('role',2)->where('status',8)->where('id','!=',$staf->id)->first();
            $inactive->status=9;
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
}
