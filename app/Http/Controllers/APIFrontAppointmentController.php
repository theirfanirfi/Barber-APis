<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment as Apt;
use App\User;
use App\Models\Appointment;
use App\Models\ApointmentTimingModel as ATM;
use \Carbon\Carbon;
class APIFrontAppointmentController extends Controller
{
    //

    public function bookappointment(Request $req){



        $token = $req->input('token');
      //  $date = $req->input('date');
        $time = $req->input('time');
        $day = $req->input('day');
        $dday = $day;
        $month = $req->input('month');
        $year = $req->input('year');

        $service_id = $req->input('service_id');

        if(empty($token) || empty($time)
        || empty($day) || empty($month) || empty($year) || empty($service_id) || !is_numeric($service_id)){
            return response()->json([
                'isAuthenticated' => true,
                'isError' => true,
                'message' => "Arguments must be provided."
            ]);
        }else {
            $user = User::getUserByToken($token);
            if($user){
                date_default_timezone_set("Asia/Karachi");
                $checkTimeIfExists = Apt::checkTimeBookingStatus($day,$month,$year,$time);
                if($checkTimeIfExists->count() > 0 ){
                    return response()->json([
                        'isAuthenticated' => true,
                        'isError' => false,
                        'isAlreadyTaken' => true,
                        'message' => "The time is already booked. Please select any other time."
                    ]);
                }else {
                    $apt = new Apt();
                    $apt->user_id = $user->id;
                    $apt->service_id = $service_id;
                    $apt->timing_id = $time;
                    $apt->day = $day;
                    $apt->month = $month;
                    $apt->year = $year;
                    //$apt->formated_time = $year;
                    if($apt->save()){
                        return response()->json([
                            'isAuthenticated' => true,
                            'isError' => false,
                            'isAlreadyTaken' => false,
                            'isBooked' => true,
                            'message' => "Time is booked. You will be notified, when the barber confirm your booking."
                        ]);
                    }else {
                        return response()->json([
                            'isAuthenticated' => true,
                            'isError' => true,
                            'message' => "Error occurred, please try again."
                        ]);
                    }
                }
            }

        else {
            return response()->json([
                'isAuthenticated' => false,
                'isError' => true,
                'message' => "Invalid token."
            ]);
        }

    }

    }

    public function bookAppointmentInsertion($dayy,$monthh,$yearr,$bookingTimee,$check40minutesAhead,$formatedTime,$modulation,$user){

        $apt = new Apt();
        $apt->day = $dayy;
        $apt->month = $monthh;
        $apt->year = $yearr;
        $apt->time_in_milli = $bookingTimee;
        $apt->user_id = $user->id;
        $apt->time_till = $check40minutesAhead;
        $apt->time_modulation = $modulation;
        $apt->formated_time = $formatedTime;

        if($apt->save()){
            return response()->json([
                'isAuthenticated' => true,
                'isError' => false,
                'isBooked' => true,
                'message' => "Time booked."
            ]);
        }else {
            return response()->json([
                'isAuthenticated' => true,
                'isError' => true,
                'isBooked' => false,
                'message' => "Error occurred in saving your booking. Please try again."
            ]);
        }
}

public function getappointmentsfortheday(Request $req){
    $day = $req->input('d');
    $month = $req->input('m');
    $year = $req->input('y');

    $atm = ATM::getAppointmentsForToday($day,$month,$year);

    return response()->json([
        'isFound' => true,
        'isError' => false,
        'timings' => $atm,
    ]);
}

public function getcurrentmonthappointments(Request $req){
    $month = $req->input('m');
    $year = $req->input('y');
    $apt = Apt::getMonthAppointments($year,$month);
    //where(['month' => $month,'year' => $year]);
    if(sizeof($apt) >0 ){
       // $apt = $apt->select('year','month','day')->get();
        return response()->json([
            'isFound' => true,
            'isError' => false,
            'bookings' => $apt,
        ]);
    }else {
        return response()->json([
            'isFound' => false,
            'isError' => true,
            'message' => 'No appointments',
        ]);
    }
}

public function getMyAppointments(Request $req){
    $token = $req->input('token');
    if($token == null || empty($token)){
        return response()->json([
            'isFound' => false,
            'isError' => true,
            'message' => 'User must be provided.',
        ]);
    }else {
        $user = User::where(['token' => $token]);
        if($user->count() > 0){
            $user = $user->first();
            $apts = Apt::getUserAppointmentsFront($user->id);
            if($apts->count() > 0){
                return response()->json([
                    'isFound' => true,
                    'isError' => false,
                    'apts' => $apts->get(),
                    'message' => 'loading...',
                ]);
            }else {
                return response()->json([
                    'isFound' => false,
                    'isError' => false,
                    'message' => 'You have not made any booking yet. ',
                ]);
            }
        }else {
            return response()->json([
                'isError' => true,
                'message' => 'User must be provided.',
            ]);
        }
    }
}


public function deleteMyAppointment(Request $req){
    $token = $req->input('token');
    $id = $req->input('id');
    if($token == null || empty($token) || $id == null || empty($id)){
        return response()->json([
            'isFound' => false,
            'isError' => true,
            'message' => 'User must be provided.',
        ]);
    }else {
        $user = User::where(['token' => $token]);
        if($user->count() > 0){
            $user = $user->first();
            $apts = Apt::where(['id' => $id, 'user_id' => $user->id]);
            if($apts->count() > 0){
                $apt = $apts->first();
                if($apt->delete()){
                    $apts = Apt::getUserAppointmentsFront($user->id);
                    return response()->json([
                        'isDeleted' => true,
                        'isError' => false,
                        'apts' => $apts->get(),
                        'message' => 'Booking deleted.',
                    ]);
                }else {
                    return response()->json([
                        'isDeleted' => false,
                        'isError' => true,
                        'message' => 'Error occurred. Try again.',
                    ]);
                }

            }else {
                return response()->json([
                    'isFound' => false,
                    'isError' => true,
                    'message' => 'No such booking found to delete.',
                ]);
            }
        }else {
            return response()->json([
                'isError' => true,
                'message' => 'User must be provided.',
            ]);
        }
    }
}

}
