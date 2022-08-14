<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        if (auth()->user()->tabel=='staffs') {
            $role=User::with('linkStaff.linkStaffRole')->find(auth()->user()->id)->linkStaff->linkStaffRole->nama;
        } else {
            $role='customer';
        }
        
        $request->session()->put('role', $role);
        $request->session()->put('password', $request->input()['password']);
        $request->session()->put('count', 1);

        $request->session()->put('id',User::with('linkStaff.linkStaffRole')->find(auth()->user()->id)->id);
        if ($role!='customer') {
            if(User::with('linkStaff.linkStaffRole')->find(auth()->user()->id)->linkStaff->status==9){
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect('/login')->with('error','Status Anda Dinyatakan Tidak Aktif Hubungi Supervisor Atau Owner Untuk Keterangan Lebih Lanjut');
            }
        }
        if($request->session()->get('role')=='owner'){
            return redirect()->intended('/owner');
        }
        if($request->session()->get('role')=='supervisor'){
            return redirect()->intended('/supervisor');
        }
        if($request->session()->get('role')=='salesman'){
            Auth::guard('web')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();
            return redirect()->intended('/spa/login');
        }
        if($request->session()->get('role')=='shipper'){
            Auth::guard('web')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();
            return redirect()->intended('/spa/login');
        }
        if($request->session()->get('role')=='administrasi'){
            return redirect()->intended('/administrasi');
        }
        if($request->session()->get('role')=='customer'){
            return redirect()->intended('/customer');
        }
        return view('/');

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
