<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Staff;
use App\Models\Customer;

class Session
{
    /**
     * Get the view / contents that represents the component.
     *
     */
    static function getSC($id)
    {
        $user=User::find($id);
        if ($user->tabel=='staffs') {
            $user=Staff::find($user->id_users);
        } else {
            $user=Customer::find($user->id_users);
        }
        
        return $user->makeHidden(['password']);
    }
}
