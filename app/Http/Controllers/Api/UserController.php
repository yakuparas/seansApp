<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\Promise\all;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        try {

            $result = User::where('store_id',Auth::user()->store_id)->where('type',UserType::Store)->with('roles')->get();
            return $this->sendResponse($result, 'Kullanıcı Listesi');


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

        DB::beginTransaction();

        try {

            $user=new User();
            $user->name=$request->name;
            $user->title=$request->title;
            $user->type=UserType::Store;
            $user->email=$request->email;
            $user->password=bcrypt($request->password);
            $user->phone=$request->phone;
            $user->company_id=Auth::user()->company_id;
            $user->store_id=Auth::user()->store_id;
            $user->country=$request->country;
            $user->city=$request->city;
            $user->district=$request->district;
            $user->adress=$request->adress;
            $user->zipcode=$request->zipcode;
            if (  $user->save())
            {
                $user->assignRole($request->role);
            }


        }catch (\Throwable $e)
        {
            DB::rollBack();
            return $this->sendError('Hata',['error'=>$e->getMessage()]);
        }
        DB::commit();
        return $this->sendResponse("", 'Kullanıcı Başarıyla Eklendi...');
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

            $result=User::where('id',$id)->with('roles')->get();
            return $this->sendResponse($result, 'Kullanıcı Detayı');


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
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = User::find($id);
            $user->name=$request->name;
            $user->title=$request->title;
            $user->type=UserType::Store;
            $user->email=$request->email;
            $user->password=bcrypt($request->password);
            $user->phone=$request->phone;
            $user->country=$request->country;
            $user->city=$request->city;
            $user->district=$request->district;
            $user->adress=$request->adress;
            $user->zipcode=$request->zipcode;
            if (  $user->save())
            {
                $user->syncRoles([]);
                $user->assignRole($request->role);
            }

        } catch (\Throwable $e)
        {
            DB::rollBack();
            return $this->sendError('Hata',['error'=>$e->getMessage()]);
        }
        DB::commit();
        return $this->sendResponse("", 'Kullanıcı Güncellendi');
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
            return $this->sendResponse($del, 'Kullanıcı Silindi');


        }catch (\Throwable $e)
        {
            return $this->sendError('Hata',['error'=>$e->getMessage()]);

        }
    }
}
