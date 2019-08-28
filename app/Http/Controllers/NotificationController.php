<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment as apt;
class NotificationController extends Controller
{
    //
    public function getBookingNotification(){
        $apts = apt::getNotConfirmedBookingCountAndBookingsForNotifications();
        if(sizeof($apts) > 0){
            return response()->json([
                'isError' => false,
                'isAuthenticated' => true,
                'isFound' => true,
                'count' => sizeof($apts),
                'notifications' => $apts,
                'message' => 'loading'
            ]);
        }else {
            return response()->json([
                'isError' => false,
                'isAuthenticated' => true,
                'isFound' => false,
                'message' => 'No new notification.'
            ]);
        }
    }

    public function confirmAppointment(Request $req){
        $appointment_id = $req->input('id');
        if($appointment_id == null || empty($appointment_id)){
            return response()->json([
                'isError' => true,
                'isAuthenticated' => true,
                'isConfirmed' => false,
                'message' => 'Appointment must be provided.'
            ]);
        }else {
            $ap = apt::where(['id' => $appointment_id]);
            if($ap->count() > 0){
                $ap = $ap->first();
                $ap->is_confirmed = 1;
                if($ap->save()){
                    return response()->json([
                        'isError' => false,
                        'isAuthenticated' => true,
                        'isConfirmed' => true,
                        'message' => 'Appointment Confirmed and the user is notified through SMS.'
                    ]);
                }else {
                    return response()->json([
                        'isError' => true,
                        'isAuthenticated' => true,
                        'isConfirmed' => false,
                        'message' => 'Error, please try again.'
                    ]);
                }
            }else {
                return response()->json([
                    'isError' => true,
                    'isAuthenticated' => true,
                    'isConfirmed' => false,
                    'message' => 'No such appointment exists to confirm it.'
                ]);
            }
        }
    }

    public function declineAppointment(Request $req){
        $appointment_id = $req->input('id');
        if($appointment_id == null || empty($appointment_id)){
            return response()->json([
                'isError' => true,
                'isAuthenticated' => true,
                'isConfirmed' => false,
                'message' => 'Appointment must be provided.'
            ]);
        }else {
            $ap = apt::where(['id' => $appointment_id]);
            if($ap->count() > 0){
                $ap = $ap->first();
                if($ap->delete()){
                    return response()->json([
                        'isError' => false,
                        'isAuthenticated' => true,
                        'isDeclined' => true,
                        'message' => 'Appointment declined, and the user is notified through SMS.'
                    ]);
                }else {
                    return response()->json([
                        'isError' => true,
                        'isAuthenticated' => true,
                        'isDeclined' => false,
                        'message' => 'Error, please try again.'
                    ]);
                }
            }else {
                return response()->json([
                    'isError' => true,
                    'isAuthenticated' => true,
                    'isDeclined' => false,
                    'message' => 'No such appointment exists to decline.'
                ]);
            }
        }
    }
}
