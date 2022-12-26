<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserType;
use App\Models\Company;
use App\Models\Store;
use App\Models\User;
use App\Notifications\CompanyRegisterNotifications;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;


class CompanyController extends BaseController
{
   /* function __construct()
    {
        $this->middleware('permission:company.list|company.store|company.show|company.destroy', ['only' => ['index','show']]);
        $this->middleware('permission:company.store', ['only' => ['create','store']]);
        $this->middleware('permission:company.show', ['only' => ['edit','update']]);
        $this->middleware('permission:company.destroy', ['only' => ['destroy']]);
    }*/

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $result=Company::select()->paginate(10);

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

            $company=new Company();
            $company->company_name=$request->company_name;
            $company->company_manager=$request->company_manager;
            $company->email=$request->email;
            $company->phone=$request->phone;
            $company->country=$request->country;
            $company->city=$request->city;
            $company->district=$request->district;
            $company->adress=$request->adress;
            $company->zipcode=$request->zipcode;
            if ($company->save())
            {
                $store=new Store();
                $store->company_id=$company->id;
                $store->store_manager=$request->company_manager;
                $store->store_name="Merkez Şube";
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
                    $user->name=$request->company_manager;
                    $user->type=UserType::Store;
                    $user->email=$request->email;
                    $user->password=bcrypt("12345");
                    $user->phone=$request->phone;
                    $user->company_id=$company->id;
                    $user->store_id=$store->id;
                    $user->country=$request->country;
                    $user->city=$request->city;
                    $user->district=$request->district;
                    $user->adress=$request->adress;
                    $user->zipcode=$request->zipcode;
                    $user->save();
                    $role=Role::create(['name' => 'admin','store_id'=>$store->id]);
                    $user->assignRole($role);
                    $CompanyData=[
                    'name'=>$request->company_manager,
                    'body'=>$request->company_name." ".$request->company_manager." 12345",
                    'btntext'=>'Teşekkürler',
                    'url'=>'/',
                    'thanks'=>'fffff',
                    ];
                }

            }


        } catch (\Throwable $e)
        {

            DB::rollBack();
            return $this->sendError('Hata',['error'=>$e->getMessage()]);

        }
        DB::commit();
        Notification::send($user,new CompanyRegisterNotifications($CompanyData));
        return $this->sendResponse("", 'Firma Kayıtı Başarıyla Oluşturuldu');
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

                $result=Company::query()->where('id',$id)->first();
                return $this->sendResponse($result, 'Firma Detayı');


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


            $company = Company::query()->find($id);
            $company->company_name=$request->company_name;
            $company->company_manager=$request->company_manager;
            $company->email=$request->email;
            $company->phone=$request->phone;
            $company->country=$request->country;
            $company->city=$request->city;
            $company->district=$request->district;
            $company->adress=$request->adress;
            $company->zipcode=$request->zipcode;
            $company->save();

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
            $del=Company::find($id)->delete();
            return $this->sendResponse($del, 'Firma ve Firmaya Tüm Bİlgiler Silindi');


        }catch (\Throwable $e)
        {
            return $this->sendError('Hata',['error'=>$e->getMessage()]);

        }
    }
}
