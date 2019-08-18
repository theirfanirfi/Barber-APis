<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
class ServiceController extends Controller
{
    //
    public function getServices(){
        $services = Service::all();
        if($services->count() > 0 ){
            return response()->json([
                'isFound' => true,
                'services' => $services,
            ]);
        }else {
            return response()->json([
                'isFound' => false,
                'message' => 'No service found.'
            ]);
        }
    }

    public function addService(Request $req){
        $name = $req->input('service_name');
        $cost = $req->input('service_cost');

        if($name == null || empty($name) || $cost == null || empty($cost)){
            return response()->json([
                'isError' => true,
                'message' => 'None of the fields can be empty.',
                'isAuthenticated' => true,
            ]);
        }else {
            $service = new Service();
            $service->service_name = $name;
            $service->service_cost = $cost;

            if($service->save()){
                return response()->json([
                    'isError' => false,
                    'isSaved' => true,
                    'message' => 'Service Added.',
                    'isAuthenticated' => true,
                ]);
            }else {
                return response()->json([
                    'isError' => true,
                    'message' => 'Error occurred in Adding the service. Please try again.',
                    'isAuthenticated' => true,
                ]);
            }
        }
    }

    public function updateservice(Request $req){
        $name = $req->input('service_name');
        $cost = $req->input('service_cost');
        $id = $req->input('service_id');

        if($name == null || empty($name) || $cost == null || empty($cost) || empty($id) || $id == null){
            return response()->json([
                'isError' => true,
                'message' => 'None of the fields can be empty.',
                'isAuthenticated' => true,
            ]);
        }else {
            $service = Service::where(['id' => $id]);
            if($service->count() > 0){
$service = $service->first();

                $service->service_name = $name;
                $service->service_cost = $cost;

                if($service->save()){
                    return response()->json([
                        'isError' => false,
                        'isSaved' => true,
                        'message' => 'Service Updated.',
                        'isAuthenticated' => true,
                    ]);
                }else {
                    return response()->json([
                        'isError' => true,
                        'message' => 'Error occurred in Updating the service. Please try again.',
                        'isAuthenticated' => true,
                    ]);
                }
            }else {
                return response()->json([
                    'isError' => true,
                    'message' => 'No such service found to update.',
                    'isAuthenticated' => true,
                ]);
            }

        }
    }


    public function deleteservice(Request $req){
        $id = $req->input('service_id');

        if(empty($id) || $id == null){
            return response()->json([
                'isError' => true,
                'message' => 'Service must be provided.',
                'isAuthenticated' => true,
            ]);
        }else {
            $service = Service::where(['id' => $id]);
            if($service->count() > 0){
$service = $service->first();
                if($service->delete()){
                    return response()->json([
                        'isError' => false,
                        'isSaved' => true,
                        'message' => 'Service deleted.',
                        'isAuthenticated' => true,
                    ]);
                }else {
                    return response()->json([
                        'isError' => true,
                        'message' => 'Error occurred in deleting the service. Please try again.',
                        'isAuthenticated' => true,
                    ]);
                }
            }else {
                return response()->json([
                    'isError' => true,
                    'message' => 'No such service found to delete.',
                    'isAuthenticated' => true,
                ]);
            }

        }
    }
}
