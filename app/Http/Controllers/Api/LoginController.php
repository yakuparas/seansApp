<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends BaseController
{
    public function login(Request $request)
    {

        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];
        if (auth()->attempt($data)) {

                $user = Auth::user();
                $success['id']=$user->id;
                $success['name']=$user->name;
                $success['email']=$user->email;
                $success['userType']=UserType::getKey((int)$user->type);

            if ($user->type==UserType::Store)
            {
                    $success['userRole']=$user->getRoleNames();
                    $success['permission']=$user->getAllPermissions();
                    $success['token'] = auth()->user()->createToken('Store')->accessToken;
                    session(['company_id' => $user->company_id]);
                    session(['store_id' => $user->store_id]);
                    return $this->sendResponse($success, 'Store login successfully.');
            }
            else if ($user->type==UserType::Customer)
            {
                if($user->status==0)
                    return $this->sendError('Unauthorised.', ['error'=>'Üyeliğiniz Onaylanmamış.Aktivasyon Kodunu Göndermek İçin Tekrar']);


                $success['token'] = auth()->user()->createToken('Customer')->accessToken;
                return $this->sendResponse($success, 'Customer login successfully.');
            }
            else if ($user->type==UserType::Administrator)
            {
                $success['token'] = auth()->user()->createToken('Administrator')->accessToken;
                return $this->sendResponse($success, 'Administrator login successfully.');
            }
            else
            {
                return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);

            }



        } else {
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }
    }


    public function logout(Request $request)
    {
        if (Auth::check())
        {
            $token = $request->user()->token();
            $token->revoke();
            Session::flush();
            return $this->sendResponse("Başarılı", 'Çıkış Yapıldı');
        }

    }
}
