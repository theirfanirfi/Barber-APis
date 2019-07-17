<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category as Cat;
use App\User;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\WhishList as WL;
class AuthController extends Controller
{
    //

    public function login(){
        $cats = Cat::all();
        $rv = "";

        return view('Frontend.login',['cats' => $cats,'rvs' => $rv]);
    }

    public function loginPost(Request $req){
        $email = $req->input('email');
        $password = $req->input('password');
        if(empty($password) || empty($email)){
            return redirect()->back()->with('error','None of the field can be empty.');
        }else {
        if(Auth::attempt(['email' => $email, 'password' => $password])){
            $user = Auth::user();
            $wls = WL::where(['user_id' => $user->id])->count();
            Session()->put('wl',$wls);

            if(Session()->has('redirect_url')){
                return redirect(Session()->get('redirect_url'))->with('success','You are logged in now.');
            }else {
            return redirect('/')->with('success','You are logged in now.');
            }

        }else {
            return redirect()->back()->with('error','Invalid credentials');
        }
    }
    }

    public function register(Request $req){
        $name = $req->input('name');
        $email = $req->input('email');
        $password = $req->input('password');
        if(empty($email) || empty($password) || empty($name)){
            return redirect()->back()->with('error','None of the field can be empty.');
        } else if(strlen($password) < 6){
            return redirect()->back()->with('error','Password length must be atleast 6 characters long');
        }else {
            $checkEmailExistence = User::where(['email' => $email])->count();
            if($checkEmailExistence > 0){
            return redirect()->back()->with('error','Email is already taken, please use another one.');
            }else {
                $user = new User();
                $user->name = $name;
                $user->role = 0;
                $user->token = Hash::make($name.time().$password);
                $user->role_name = "common";
                $user->email = $email;
                $user->password = Hash::make($password);
                if($user->save()){
                    return redirect('/login')->with('success','Registeration successful. Please login with your newly created account.');
                }else {
            return redirect()->back()->with('error','Registeration failed. Please try again.');
                }
            }
        }
    }

    public function logout(){
        Auth::logout();
        Session()->flush();
        Session()->forget(['cart','total_cart_cost','redirect_url']);
        return redirect('/')->with('info','You have logged out.');
    }

}
