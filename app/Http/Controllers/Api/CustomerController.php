<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class CustomerController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            $result=User::where('company_id',Auth::user()->company_id)->where('type',UserType::Customer)->get();


            return $this->sendResponse($result, 'Müşteri Listesi');


        } catch (\Throwable $e)
        {
            return $this->sendError('Hata',['error'=>$e->getMessage()]);

        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            $result=User::where('id',$id)->get();
            return $this->sendResponse($result, 'Müşteri Detayı');


        } catch (\Throwable $e)
        {
            return $this->sendError('Hata',['error'=>$e->getMessage()]);

        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {



        try {
            $validator = Validator::make($request->all(), [
                'logo' => 'required|image',
            ]);

            if ($validator->fails())
            {
                return $this->sendError($validator->errors()->all(),'Hata...');

            }

            $user = User::query()->find(Auth::user()->id);
            $user->name=$request->name;
            $user->phone=$request->phone;
            $user->country=$request->country;
            $user->city=$request->city;
            $user->district=$request->district;
            $user->adress=$request->adress;
            $user->zipcode=$request->zipcode;

            if($request->hasFile('logo')) {
                $logo = time().'.'.$request->logo->getClientOriginalExtension();
                $request->logo->move(public_path('image'), $logo);
            }
            $user->image='image/'.$logo;
            $user->save();

            return $this->sendResponse($user, 'Profil Detayı');



        }catch (\Throwable $e)
        {
            return $this->sendError('Hata',['error'=>$e->getMessage()]);

        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $del=User::find($id)->delete();
            return $this->sendResponse($del, 'Müşteri Silindi');


        }catch (\Throwable $e)
        {
            return $this->sendError('Hata',['error'=>$e->getMessage()]);

        }
    }

    public function status(Request $request,$id)
    {
        try {
            $user=User::find($id);
            $user->status=$request->status;
            $user->save();
            return $this->sendResponse($user, 'Müşteri Durumu Değiştirildi ');


        }catch (\Throwable $e)
        {
            return $this->sendError('Hata',['error'=>$e->getMessage()]);

        }
    }

    public function getProfile()
    {
        try {

            $result=User::where('id',Auth::user()->id)->first();
            return $this->sendResponse($result, 'Profil Bilgileri');


        } catch (\Throwable $e)
        {
            return $this->sendError('Hata',['error'=>$e->getMessage()]);

        }
    }
}
