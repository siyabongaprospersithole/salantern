<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller
{

    //Login Post Route
    public function login(Request $request){
        $this->validate($request,[
            'email_address' => 'required',
            'password' => 'required|min:6',
        ]);

        if( ! auth()->attempt($request->only('email_address','password')))
        {  
              return response()->json(['status' => 'invalid']);
        }else{
            return response()->json(['status' => 'success']);
        }
    }

    public function logout(){
        if(auth()->check())
         auth()->logout();

         return redirect('/');
    }
}
