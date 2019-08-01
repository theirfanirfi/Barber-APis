<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Appointment as apt;
use DB;
class ApointmentTimingModel extends Model
{
    //
    protected $table = "timings";

    public static function getAppointmentsForToday($day,$month,$year){
        return DB::select("SELECT * FROM appointments RIGHT JOIN
        timings ON appointments.timing_id = timings.id
        AND day = '$day' AND appointments.month = '$month' AND appointments.year = '$year'", [1]);
    }
}
