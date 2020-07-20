<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function verifyToken($token){
        return User::where(['token' => $token]);
    }

    public function returnUser($token){
        $user =  User::where(['token' => $token]);
        return $user->count() > 0 ? $user->first() : false;
    }

    public static function getMembers(){
        return DB::table('users')->where('users.role','=','0')
        ->leftjoin('appointments',['appointments.user_id' => 'users.id'])
        ->select('users.id','name','users.profile_image',DB::raw("COUNT(appointments.id) as totalapp"))
        ->orderBy('users.id','DESC')
        ->groupBy('users.id')
        ->groupBy('name')
        ->groupBy('users.profile_image');
    }

    public static function getProfile($token){
        return User::where(['token' => $token,'role' => 1]);
    }

    public static function getUserByToken($token){
        $user =  User::where(['token' => $token, 'role' => 0]);
        return $user->count() > 0 ? $user->first() : false;
    }

    public static function getBarberByToken($token){
        $user =  User::where(['token' => $token, 'role' => 1,'is_barber' => 1]);
        return $user->count() > 0 ? $user->first() : false;
    }
}
