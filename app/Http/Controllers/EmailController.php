<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ConfirmationEmail;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
  public function index(){
		Mail::to("testing@malasngoding.com")->send(new ConfirmationEmail());
 
		return "Email telah dikirim";
	}
}
