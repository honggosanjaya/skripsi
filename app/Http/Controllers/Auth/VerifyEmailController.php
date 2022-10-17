<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Foundation\Auth\EmailVerificationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(EmailVerificationRequest $request)
    {
      if(config('app.enabled_email_confirmation')==true){
        if ($request->user()->tabel=='staffs') {
          $role = User::with('linkStaff.linkStaffRole')->find(auth()->user()->id)->linkStaff->linkStaffRole->nama;
        } else {
          $role = 'customer';
        }
      }

      if ($request->user()->hasVerifiedEmail()) {
          return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
      }

      if ($request->user()->markEmailAsVerified()) {
          event(new Verified($request->user()));
      }

      if(config('app.enabled_email_confirmation')==false){
        return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
      }

      if(config('app.enabled_email_confirmation')==true){
        if($role == 'salesman' || $role == 'shipper'){
          Auth::guard('web')->logout();
          $request->session()->invalidate();
          $request->session()->regenerateToken();
          return redirect()->intended('/spa/login');
        }else if($role == 'owner'){
          return redirect()->intended('/owner');
        }else if($role == 'supervisor'){
          return redirect()->intended('/supervisor');
        }else if($role == 'administrasi'){
          return redirect()->intended('/administrasi');
        }else if($role == 'customer'){
          return redirect()->intended('/customer');
        }        
      }
    }
}
