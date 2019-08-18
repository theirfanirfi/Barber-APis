<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
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
        $res = DB::select(" SELECT *, CONCAT(appointments.`year`, '-0', appointments.`month`,'-',appointments.`day`) AS dday FROM `appointments` WHERE year = '$year' AND month = '$month'", [1]);
        return $res;
    }

    public static function getMonthAppointmentsAdmin($year,$month){
        $res = DB::select(" SELECT *, CONCAT(appointments.`year`, '/0', appointments.`month`,'/',appointments.`day`) AS dday FROM `appointments` WHERE year = '$year' AND month = '$month'", [1]);
        return $res;
    }

    public static function getDayAppointmentsAdmin($year,$month,$day){
        $res = DB::select(" SELECT *, CONCAT(appointments.`year`, '/0', appointments.`month`,'/',appointments.`day`) AS dday FROM `appointments` LEFT JOIN users on users.id = appointments.user_id
        LEFT JOIN timings on timings.id = appointments.timing_id
         WHERE appointments.year = '$year' AND appointments.month = '$month' AND appointments.day = '$day'", [1]);
        return $res;
    }


}
