<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        try {

            $result = DB::table('events')
                ->join('services', 'events.service_id', '=', 'services.id')
                ->join('users', 'events.user_id', '=', 'users.id')
                ->select('events.*','users.name as user_name','services.name as servis_name')
                ->where('customer_id',Auth::user()->id)
                ->get();
            return $this->sendResponse($result, 'Randevularım');


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
            $event=new Event();
            $event->store_id=$request->store_id;
            $event->service_id=$request->service_id;
            $event->event_type_id=$request->event_type_id;
            $event->user_id=$request->user_id;

            if (isset($request->customer_id))
            {
                $event->customer_id=$request->customer_id;
            }
            else
            {
                $event->customer_id=Auth::user()->id;
            }

            $event->payment_id=$request->payment_id;
            $event->event_name=$request->event_name;
            $event->event_start=$request->event_start;
            $event->event_end=$request->event_end;
            $event->save();
        }catch (\Throwable $e)
        {
            DB::rollBack();
            return $this->sendError('Hata',['error'=>$e->getMessage()]);
        }
        DB::commit();
        return $this->sendResponse("", 'Randevu Başarıyla Oluşturuldu...');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        try {

            $result = DB::table('events')
                ->join('services', 'events.service_id', '=', 'services.id')
                ->join('users', 'events.user_id', '=', 'users.id')
                ->select('events.*','users.name as user_name','services.name as servis_name')
                ->where('events.id',Auth::user()->id)
                ->get();
            return $this->sendResponse($result, 'Randevu Düzenle');


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
        /*DB::beginTransaction();
        try {
            $event = Event::query()->find($id);
            $event->store_id=$request->store_id;
            $event->service_id=$request->service_id;
            $event->event_type_id=$request->event_type_id;
            $event->user_id=$request->user_id;
            $event->customer_id=Auth::user()->id;
            $event->payment_id=$request->payment_id;
            $event->event_name=$request->event_name;
            $event->event_start=$request->event_start;
            $event->event_end=$request->event_end;
            $event->save();

        } catch (\Throwable $e)
        {
            DB::rollBack();
            return $this->sendError('Hata',['error'=>$e->getMessage()]);
        }
        DB::commit();
        return $this->sendResponse("", 'Event Type Başarıyla Güncellendi');*/
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        try {
            $del=Event::find(Auth::user()->id)->delete();
            return $this->sendResponse($del, 'Randevu Silindi');


        }catch (\Throwable $e)
        {
            return $this->sendError('Hata',['error'=>$e->getMessage()]);

        }
    }
}
