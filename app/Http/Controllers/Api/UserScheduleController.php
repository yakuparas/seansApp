<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserScheduleController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        try {

            $result=UserSchedule::where('user_id',$id)->get();


            return $this->sendResponse($result, 'User Schedule Listesi');


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

            $userschedule=new UserSchedule();
            $userschedule->store_id=Auth::user()->store_id;
            $userschedule->user_id=$request->user_id;
            $userschedule->schedule_day=$request->day;
            $userschedule->schedule_start_time=$request->start_time;
            $userschedule->schedule_end_time=$request->end_time;
            $userschedule->average_consulting_time=$request->consulting_time;
            $userschedule->save();


        }catch (\Throwable $e)
        {
            DB::rollBack();
            return $this->sendError('Hata',['error'=>$e->getMessage()]);
        }
        DB::commit();
        return $this->sendResponse("", 'Başarıyla Eklendi...');
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

            $result=UserSchedule::where('id',$id)->get();


            return $this->sendResponse($result, 'User Schedule Detayı');


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
            $userschedule = UserSchedule::query()->find($id);
            $userschedule->schedule_day=$request->day;
            $userschedule->schedule_start_time=$request->start_time;
            $userschedule->schedule_end_time=$request->end_time;
            $userschedule->average_consulting_time=$request->consulting_time;
            $userschedule->save();

        } catch (\Throwable $e)
        {
            DB::rollBack();
            return $this->sendError('Hata',['error'=>$e->getMessage()]);
        }
        DB::commit();
        return $this->sendResponse("", 'User Schedule Başarıyla Güncellendi');
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
            $del=UserSchedule::find($id)->delete();
            return $this->sendResponse($del, 'User Schedule  Silindi');


        }catch (\Throwable $e)
        {
            return $this->sendError('Hata',['error'=>$e->getMessage()]);

        }
    }
}
