<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\CustomerRegisterNotifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class RegisterController extends BaseController
{
    public function customerRegister(Request $request)
    {

        $user=new User();
        $user->status=0;
        $user->type=UserType::Customer;
        $user->name=$request->name;
        $user->email=$request->email;
        $user->company_id=$request->company_id;
        $user->device_token=$request->device_token;
        $user->password=bcrypt($request->password);
        $code=random_int(1000, 9999);
        $user->remember_token=$code;
        if ($user->save())
        {
            $msj="Seans App Lorem Lorem";
            $msj.="<br><br>";
            $msj.="Doğrulama Kodu: ".$code;
            $userX = [
                'subject' => 'Üyeliğinizi Tamamlayınız',
                'greeting' => 'Sayın, '.$request->email,
                'body' => $msj,
                'id' => $user->id
            ];
            $userN=User::find($user->id);
            Notification::send($userN,new CustomerRegisterNotifications($userX));
        }

    }

    public function emailVerify(Request $request)
    {
        $code=DB::table('users')->where('remember_token', $request->code)->first();
        if ($code->id!="")
        {
                $user=User::find($code->id);
                $user->status=1;
                $user->save();
        }
        return $this->sendResponse("Başarılı", 'Üyeliğiniz Onaylanmıştır');


    }
}
