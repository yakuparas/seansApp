<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Notifications\CustomerRegisterNotifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends BaseController
{
    public function sendCode(Request $request)
    {


        $userN=User::where('email',$request->email)->first();
        $code=random_int(1000, 9999);
        $msj="Seans App Lorem Lorem";
        $msj.="<br><br>";
        $msj.="Doğrulama Kodu: ".$code;
        $userN->remember_token=$code;
        $userN->save();
        $userX = [
            'subject' => 'Şifre Sıfırlama Maili',
            'greeting' => 'Sayın, '.$request->email,
            'body' => $msj
        ];

        Notification::send($userN,new CustomerRegisterNotifications($userX));
        return $this->sendResponse("", 'Şifre Sıfırlama Kodu Mail Olarak Gönderilmiştir...');

    }

    public function updatePassword(Request $request)
    {
        $upt=User::where('email', $request->email)->where('remember_token',$request->code)
            ->update([
                'password' => bcrypt($request->password)
            ]);
       if ($upt>0)
           return $this->sendResponse("Başarılı", 'Şifreniz Başarıyla Güncellenmiştir....');
       else
           return $this->sendError("Hatalı", 'Doğrulama Kodu Yanluş Girilmiştir.....');




    }
}
