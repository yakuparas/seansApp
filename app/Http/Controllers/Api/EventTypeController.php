<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EventType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventTypeController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            //$result=EventType::where('store_id',Auth::user()->store_id)->get();
            $result=EventType::all();


            return $this->sendResponse($result, 'Event Type Listesi');


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

            $eventype=new EventType();
           // $eventype->store_id=Auth::user()->store_id;
            $eventype->name=$request->name;
            $eventype->save();


        }catch (\Throwable $e)
        {
            DB::rollBack();
            return $this->sendError('Hata',['error'=>$e->getMessage()]);
        }
        DB::commit();
        return $this->sendResponse("", 'Event Tipi Başarıyla Eklendi...');
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

           // $result=EventType::all();
            $result=EventType::query()->where('id',$id)->get();
            return $this->sendResponse($result, 'Event Type Detayı');


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
            $eventype = EventType::query()->find($id);
            $eventype->name=$request->name;
            $eventype->save();

        } catch (\Throwable $e)
        {
            DB::rollBack();
            return $this->sendError('Hata',['error'=>$e->getMessage()]);
        }
        DB::commit();
        return $this->sendResponse("", 'Event Type Başarıyla Güncellendi');
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
            $del=EventType::find($id)->delete();
            return $this->sendResponse($del, 'Event Type Silindi');


        }catch (\Throwable $e)
        {
            return $this->sendError('Hata',['error'=>$e->getMessage()]);

        }
    }
}
