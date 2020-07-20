<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\ApointmentTimingModel as ATM;
class Appointment extends Model
{
    //
    protected $table = "appointments";

    public static function checkTimeAhead($time,$btime){
        return Appointment::where(['time_in_milli' => $time])->orWhere(['time_in_milli' => $btime])->count() > 0 ? 9: 0;
    }

    public static function getTheLastBookedTime(){
        $apt =  Appointment::orderBy('id','DESC')->select('time_till','time_in_milli');
        return $apt->count() > 0 ? $apt->first() : false;
    }

    public static function getTheLastAMBookedTime($year,$month,$day){
        $apt =  Appointment::where(['time_modulation' => "am",'year' => $year,'month' => $month, 'day' => $day])->orderBy('id','DESC')->select('time_till','time_in_milli','time_modulation');
        return $apt->count() > 0 ? $apt->first() : false;
    }

    public static function getTheLastPMBookedTime($year,$month,$day){
        $apt =  Appointment::where(['time_modulation' => "pm",'year' => $year,'month' => $month, 'day' => $day])->orderBy('id','DESC')->select('time_till','time_in_milli','time_modulation');
        return $apt->count() > 0 ? $apt->first() : false;
    }

    public static function getTheLastPMBookedTimeLessThan($hour,$year,$month,$day){
        $apt =  Appointment::where(['time_modulation' => "pm",'year' => $year,'month' => $month, 'day' => $day])->orderBy('id','DESC')->select('time_till','time_in_milli','time_modulation');
        return $apt->count() > 0 ? $apt->first() : false;
    }

    public static function getMonthAppointments($year,$month){
        // $res = DB::select(" SELECT *, CONCAT(appointments.`year`, '-0', appointments.`month`,'-',
        // appointments.`day`)
        // AS dday FROM `appointments` WHERE year = '$year' AND month = '$month'", [1]);
        // return $res;
        $res = DB::select("SELECT *, IF(appointments.day < 10, CONCAT(appointments.`year`, '-',
        IF(appointments.month < 10, CONCAT('0',appointments.month), appointments.month)
        ,
        '-0',appointments.`day`), CONCAT(appointments.`year`, '-',
        IF(appointments.month < 10, CONCAT('0',appointments.month), appointments.month)


        ,'-',appointments.`day`))
                AS dday FROM `appointments` WHERE year = '$year' AND month = '$month'", [1]);
        return $res;
    }

    public static function getMonthAppointmentsAdmin($year,$month){
        $res = DB::select(" SELECT *, CONCAT(appointments.`year`, '/0', appointments.`month`,'/',appointments.`day`) AS dday FROM `appointments` WHERE year = '$year' AND month = '$month'", [1]);
        return $res;
    }

    public static function getDayAppointmentsAdmin($year,$month,$day){
        $res = DB::select(" SELECT *,appointments.id as app_id, CONCAT(appointments.`year`, '/0', appointments.`month`,'/',appointments.`day`) AS dday FROM `appointments` LEFT JOIN users on users.id = appointments.user_id
        LEFT JOIN timings on timings.id = appointments.timing_id
         WHERE appointments.year = '$year' AND appointments.month = '$month' AND appointments.day = '$day'", [1]);
        return $res;
    }

    public static function getUserAppointments($user_id){
        $res = DB::select(" SELECT *, CONCAT(appointments.`year`, '/0', appointments.`month`,'/',appointments.`day`) AS dday FROM `appointments` LEFT JOIN users on users.id = appointments.user_id
        LEFT JOIN timings on timings.id = appointments.timing_id
         WHERE appointments.user_id = '$user_id'", [1]);
        return $res;
    }

    public static function getNotConfirmedBookingCountAndBookingsForNotifications(){
        // return  Appointment::where(['is_confirmed' => 0])
        // ->leftjoin('users',['users.id' => 'appointments.user_id'])
        // ->orderBy('appointments.id','DESC')
        // ->select('appointments.*','users.name','users.profile_image');

        $res = DB::select(" SELECT *,appointments.id as app_id, CONCAT(appointments.`year`, '/0', appointments.`month`,'/',appointments.`day`) AS dday FROM `appointments` LEFT JOIN users on users.id = appointments.user_id
        LEFT JOIN timings on timings.id = appointments.timing_id
         WHERE appointments.is_confirmed = 0 ORDER BY appointments.id DESC", [1]);
        return $res;
    }

    public static function checkTimeBookingStatus($day,$month,$year,$time_id){
        return Appointment::where(['day' => $day,'month' => $month,'year' => $year,'timing_id' => $time_id]);
    }

    public static function getUserAppointmentsFront($user_id){
        return Appointment::where(['user_id' => $user_id, 'is_confirmed' => 1])
        ->leftjoin('timings',['timings.id' => 'appointments.timing_id'])
        ->leftjoin('services',['services.id' => 'appointments.service_id'])
        ->select('timings.time_range','appointments.day','appointments.month','appointments.year',
        'appointments.user_id','appointments.id','services.service_name','services.service_cost');
    }

}
