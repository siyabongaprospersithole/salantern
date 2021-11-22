<?php

namespace App\Http\Controllers;
use Mail;
use DB;
use App\Mail\Noreply;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;

class SignupController extends Controller
{

      //we trying to bring the  UserController controller to this controller so we can use it's methods
      protected $ChilduserController;

      public function __construct(UserController $ChilduserController)
  
      {
          $this->ChilduserController = $ChilduserController;
      }
  


    public function store(Request $request){

        //Validate the request data
        $this->validate($request,[
            'fullname' => 'required|min:3|max:60',
            'email_address' => 'required|email|unique:users|max:50',
            'date_of_birth' => 'required|date|max:13',
            'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:6'
        ]);
        
        //attempt to create the user
        //we call the create function on the UserController  
       if($this->ChilduserController->create(new \Illuminate\Http\Request($request->except('password_confirmation')))){
        
        //send a no reply email to the user, to welcome them
        Mail::to($request->email_address)->send(new Noreply($request->fullname));

        //Log the user in
        auth()->login(User::where('email_address', $request->email_address)->firstOrFail());

        return response()->json(['status' => 'success']);
       }else{
        return response()->json(['status' => 'failed']);
       }

      
    }
}
