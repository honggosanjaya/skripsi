<?php
namespace App\Helpers;
 
use Illuminate\Support\Facades\DB;
 
class Util 
{
  static function backupFile($dir,$path)
  {
      $target_url = 'https://cdn.suralaya.web.id/'; // Write your URL here
                  
      $cFile = curl_file_create($dir);
      $post = ['sendimage'=> $cFile,'path'=>$path]; // Parameter to be sent
      
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $target_url);
      curl_setopt($ch, CURLOPT_POST,1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $result=json_decode(curl_exec($ch));
      // dd($result);
      curl_close($ch);
  }
}