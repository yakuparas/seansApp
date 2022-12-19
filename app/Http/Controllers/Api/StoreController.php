<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Store;
use App\Models\User;
use App\Notifications\CompanyRegisterNotifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;

class StoreController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        try {
            $result=Store::where('company_id',session('company_id'))->get();
            return $this->sendResponse($result, 'Firma Listesi');


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

            $store=new Store();
            $store->company_id=Auth::user()->company_id;
            $store->store_manager=$request->store_manager;
            $store->store_name=$request->store_name;
            $store->email=$request->email;
            $store->phone=$request->phone;
            $store->country=$request->country;
            $store->city=$request->city;
            $store->district=$request->district;
            $store->adress=$request->adress;
            $store->zipcode=$request->zipcode;
            if ($store->save())
            {
                $user=new User();
                $user->name=$request->store_manager;
                $user->type=UserType::Store;
                $user->email=$request->email;
                $user->password=bcrypt("12345");
                $user->company_id=Auth::user()->company_id;
                $user->store_id=$store->id;
                $user->save();
                $role=Role::create(['name' => 'admin','store_id'=>$store->id]);
                $user->assignRole($role);
                $CompanyData=[
                    'name'=>$request->store_manager,
                    'body'=>$request->store_name." ".$request->store_manager." 12345",
                    'btntext'=>'Teşekkürler',
                    'url'=>'/',
                    'thanks'=>'fffff',
                ];

            }

        }catch (\Throwable $e)
        {
            DB::rollBack();
            return $this->sendError('Hata',['error'=>$e->getMessage()]);
        }
        DB::commit();
        Notification::send($user,new CompanyRegisterNotifications($CompanyData));
        return $this->sendResponse("", 'Şube Başarıyla Eklendi...');


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

            $result=Store::query()->where('id',$id)->get();
            return $this->sendResponse($result, 'Şube Detayı');


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
            $store = Store::query()->find($id);
            $store->store_name=$request->store_name;
            $store->store_manager=$request->store_manager;
            $store->email=$request->email;
            $store->phone=$request->phone;
            $store->country=$request->country;
            $store->city=$request->city;
            $store->district=$request->district;
            $store->adress=$request->adress;
            $store->zipcode=$request->zipcode;
            $store->save();

        } catch (\Throwable $e)
        {
            DB::rollBack();
            return $this->sendError('Hata',['error'=>$e->getMessage()]);
        }
        DB::commit();
        return $this->sendResponse("", 'Firma Başarıyla Güncellendi');
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
            $del=Store::find($id)->delete();
            return $this->sendResponse($del, 'Şube ve Şubeye Tüm Bilgiler Silindi');


        }catch (\Throwable $e)
        {
            return $this->sendError('Hata',['error'=>$e->getMessage()]);

        }
    }
}
