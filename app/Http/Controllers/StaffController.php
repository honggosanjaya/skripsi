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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return view('supervisor.datastaf.index', [
        'stafs' => Staff::whereNotIn('role', [1, 2])->paginate(5),
        "title" => "List Tim Marketing UD Mandiri"
      ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('supervisor.datastaf.create', [
        'stafs' => Staff::all(),
        'roles' => StaffRole::whereNotIn('nama', ['owner', 'supervisor'])->get(),
        'statuses' => Status::where('tabel', 'staffs')->get(),
        "title" => "Data Tim Markting - Add"
      ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $rules = ([
        'nama' => ['required', 'string', 'max:255'],
        'email' => ['string', 'email', 'max:255', 'unique:users'],
        'telepon' => ['nullable','string', 'max:15'],
        'foto_profil' => 'image|file|max:1024',
        'role' => ['required'],
        'status' => ['required'],
      ]);

      $validatedData = $request->validate($rules);

      $validatedData['password'] = Hash::make(12345678);
      $validatedData['role'] = $request->role;
      $validatedData['status'] = $request->status;
      $validatedData['created_at'] = now();

      if ($request->foto_profil) {
        $file_name = time() . '.' . $request->foto_profil->extension();
        $request->foto_profil->move(public_path('storage/staff'), $file_name);
        $validatedData['foto_profil'] = $file_name;
      }    

      $staff=Staff::insertGetId($validatedData);

      $user = User::create([
        'id_users' => $staff,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'tabel' => 'staffs',
      ]);

      event(new Registered($user));

      return redirect('/supervisor/datastaf') -> with('pesanSukses', 'Staf berhasil ditambahkan' );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      return view('supervisor.datastaf.edit',[
        'staf' => Staff::where('id', $id)->first(),
        'roles' => StaffRole::whereNotIn('nama', ['owner', 'supervisor'])->get(),
        'statuses' => Status::where('tabel', 'staffs')->get(),
        "title" => "Data Tim Markting - Edit"
      ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $rules = [
        'nama' => ['required', 'string', 'max:255'],
        'telepon' => ['nullable','string', 'max:15'],
        'foto_profil' => 'image|file|max:1024',
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
        $file_name = time() . '.' . $request->foto_profil->extension();
        $request->foto_profil->move(public_path('storage/staff'), $file_name);
        $validatedData['foto_profil'] = $file_name;
      }    
      
      Staff::where('id', $id)->update($validatedData);

      return redirect('/supervisor/datastaf') -> with('pesanSukses', 'data staf berhasil diubah' );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function supervisorEditStatusStaf(Staff $staf)
    {
      $status = $staf->status;
      $nama_status = Status::where('id', $status)->first()->nama; 

      if($nama_status === 'active'){
        Staff::where('id', $staf->id)->update(['status' => 9]);
      }else if($nama_status === 'inactive'){
        Staff::where('id', $staf->id)->update(['status' => 8]);
      }

      return redirect('/supervisor/datastaf') -> with('pesanSukses', 'Berhasil ubah status' );
    }
}
