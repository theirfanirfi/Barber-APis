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
        $openingHour = "08:00:00 am";
        $time = new \DateTime($openingHour);
        $timee= $time->format('H:i:s a');

        $mill = strtotime($timee);
        for($i = 1;$i<=12;$i++){
            // $timee = $timee + (40*60);
              $mill = $mill + (40*60);
        echo $formated = date("H:i:s a",$mill);

        }





exit();



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
                $time1 = new \DateTime($year.'-'.$month.'-'.$day.' '.$time);
                //exit();
                $timee= $time1->format('Y-m-d H:i:s');
                $bookingTime =  strtotime($timee); //1563633780
                $hour = date("H",$bookingTime);
                $modulation = date("a",$bookingTime);
                // return response()->json([
                //     'time' => date("a",$bookingTime),
                // ]);
                $check40minutesAhead = $bookingTime + (40*60); //1563636180
                $formatedTime = date('H:i:s a',$bookingTime);
                $check40minutesAhead = $bookingTime + (40*60);
                $checkApt = Apt::checkTimeAhead($check40minutesAhead,$bookingTime);
                if($checkApt){
                    return response()->json([
                        'isAuthenticated' => true,
                        'isError' => false,
                        'isBooked' => false,
                        'isAlreadBooked' => true,
                        'message' => "Current time is already booked. Please select 40 minutes ahead time. Thankyou",
                    ]);
                }else {

                        if($modulation == "pm"){


                            $checkTheDifference = Appointment::getTheLastPMBookedTime($year,$month,$day);
                            if($checkTheDifference){

                                $timeTill =  $checkTheDifference->time_till;
                                $lastBookedHour = date('H',$timeTill);

                                if($hour < $lastBookedHour){
                                   // echo "less than";
                                $diff = ($timeTill - $check40minutesAhead)/60;
                                if($diff >= 40){
                                    return $this->bookAppointmentInsertion($day,$month,$year,$bookingTime,$check40minutesAhead,$formatedTime,"pm",$user);
                                } else {
                                    $timebooked = date("H:i:sA",$timeTill);
                                    return response()->json([
                                        'isAuthenticated' => true,
                                        'isError' => false,
                                        'isBooked' => false,
                                        'isAlreadBooked' => true,
                                        'message' => "Sorry the time till ".$timebooked. " is already booked. Please select any time ahead of it. Thankyou",
                                    ]);
                                }
                                }else {
                                $diff = ($check40minutesAhead - $timeTill)/60;
                                if($diff >= 40){
                                    return $this->bookAppointmentInsertion($day,$month,$year,$bookingTime,$check40minutesAhead,$formatedTime,"pm",$user);
                                } else {
                                    $timebooked = date("H:i:sA",$timeTill);
                                    return response()->json([
                                        'isAuthenticated' => true,
                                        'isError' => false,
                                        'isBooked' => false,
                                        'isAlreadBooked' => true,
                                        'message' => "Sorry the time till ".$timebooked. " is already booked. Please select any time ahead of it. Thankyou",
                                    ]);
                                }
                                }
                                // $time_till_modulation = date('a',$timeTill);
                                // $booking_time_modulation = date('a',$bookingTime);
                                $diff = ($check40minutesAhead - $timeTill)/60;

                                if($diff >= 40){
                                    return $this->bookAppointmentInsertion($day,$month,$year,$bookingTime,$check40minutesAhead,$formatedTime,"pm",$user);
                                } else {
                                    $timebooked = date("H:i:sA",$timeTill);
                                    return response()->json([
                                        'isAuthenticated' => true,
                                        'isError' => false,
                                        'isBooked' => false,
                                        'isAlreadBooked' => true,
                                        'message' => "Sorry the time till ".$timebooked. " is already booked. Please select any time ahead of it. Thankyou",
                                    ]);
                                }
                            }else {
                                return $this->bookAppointmentInsertion($day,$month,$year,$bookingTime,$check40minutesAhead,$formatedTime,"pm",$user);
                            //    return response()->json([
                            //     //'diff' => $diff,
                            //     'modulation' => 'am'
                            // ]);
                            }
                        }else {

                            $checkTheDifference = Appointment::getTheLastAMBookedTime($year,$month,$day);
                            if($checkTheDifference){

                                $timeTill =  $checkTheDifference->time_till;
                                // $time_till_modulation = date('a',$timeTill);
                                // $booking_time_modulation = date('a',$bookingTime);
                                $diff = ($check40minutesAhead - $timeTill)/60;
                            if($diff >= 40){
                                // return response()->json([
                                //     'diff' => $diff,
                                //     'modulation' => 'am'
                                // ]);
                               // echo "AM diff ".$diff;
                               // exit();
                                return $this->bookAppointmentInsertion($day,$month,$year,$bookingTime,$check40minutesAhead,$formatedTime,"am",$user);
                            } else {
                                $timebooked = date("H:i:sA",$timeTill);
                                return response()->json([
                                    'isAuthenticated' => true,
                                    'isError' => false,
                                    'isBooked' => false,
                                    'isAlreadBooked' => true,
                                    'message' => "Sorry the time till ".$timebooked. " is already booked. Please select any time ahead of it. Thankyou",
                                ]);
                            }
                            }else {
                                //do not exist insert the record
                                return $this->bookAppointmentInsertion($day,$month,$year,$bookingTime,$check40minutesAhead,$formatedTime,"am",$user);
                                // return response()->json([
                                //     //'diff' => $diff,
                                //     'modulation' => 'am'
                                // ]);
                            }
                            // echo "AM ".$diff;
                            // exit();


                        }



        }
        }else {
            return response()->json([
                'isAuthenticated' => true,
                'isError' => true,
                'message' => "Invalid token."
            ]);
        }
    }

        // $time1 = new \DateTime('09:00:00');
        // $time2 = new \DateTime('10:41:00');
        // $interval = $time1->diff($time2);
        // $diffInMin =  $interval->format("%i");
        // $diffInHours =  $interval->format("%H");
        //echo "<br/>";
       // $t2 = strtotime("4:10");
       // echo $t2 = $t2 + (40*60);
        //echo ($t2 - $t1)/60;
        //echo $diffInMin;
        //echo $check40minutesAhead;

        //echo date('H:i:s',$check40minutesAhead);


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
            'isError' => false,
        ]);
    }
}

}
