<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServiceController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            $result=Service::where('company_id',Auth::user()->company_id)->where('store_id',Auth::user()->store_id)->get();


            return $this->sendResponse($result, 'Hizmet Listesi');


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

          $services=new Service();
          $services->store_id=Auth::user()->store_id;
          $services->company_id=Auth::user()->company_id;
          $services->name=$request->name;
          $services->description=$request->description;
          $services->price=$request->price;
          $services->duration_time=$request->duration_time;
          $services->break_time=$request->break_time;
          $services->save();


        }catch (\Throwable $e)
        {
            DB::rollBack();
            return $this->sendError('Hata',['error'=>$e->getMessage()]);
        }
        DB::commit();
        return $this->sendResponse("", 'Hizmet Başarıyla Eklendi...');
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

            $result=Service::query()->where('id',$id)->get();
            return $this->sendResponse($result, 'Hizmet Detayı');


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
            $services = Service::query()->find($id);
            $services->name=$request->name;
            $services->description=$request->description;
            $services->price=$request->price;
            $services->duration_time=$request->duration_time;
            $services->break_time=$request->break_time;
            $services->save();

        } catch (\Throwable $e)
        {
            DB::rollBack();
            return $this->sendError('Hata',['error'=>$e->getMessage()]);
        }
        DB::commit();
        return $this->sendResponse("", 'Hizmet Başarıyla Güncellendi');
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
            $del=Service::find($id)->delete();
            return $this->sendResponse($del, 'Hizmet ve Hizmete Ait Bilgiler Silindi');


        }catch (\Throwable $e)
        {
            return $this->sendError('Hata',['error'=>$e->getMessage()]);

        }
    }
}
