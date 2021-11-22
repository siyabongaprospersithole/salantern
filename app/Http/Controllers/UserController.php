<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DB;

class UserController extends Controller
{
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        //We can the beginTransaction() method so that we can rollback if we encounter any errors
        DB::beginTransaction();
        try
        {    
            
            //try creating the user
            $create_User = User::Create([
                'fullname' => $request->fullname,
                'email_address' => $request->email_address,
                'date_of_birth' => $request->date_of_birth,
                'password' => bcrypt($request->password),
            ]);



           //commit the query to the database
            DB::commit();

            return true;
        }catch (Throwable $e) {

        //If we encounter any errors we rollback
         DB::rollBack();

         return false;
      }
    }

   
    public function update(Request $request)
    {

        //This function is called whenever the user:
        //    1. Updates image
        //    2. Updates User Details
        //    3. Updates Password
      
        //If the request has a file then we only update profile image
        if($request->file()){
            $image_name = $request->file;
            $image_name=  auth()->user()->id."-".$request->date_of_birth . '.' .$request->file->getClientOriginalExtension();
            $path = $request->file('file')
            ->storeAs('/', $image_name, ['disk'=>'avatar_uploads']);

            $user = User::find(auth()->user()->id);
            $user->avatar = $image_name;
            $user->save();

        }else if($request->has('password')){ //If the request has password then we only update the password
           
            //validate password
            $this->validate($request,[
                'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
                'password_confirmation' => 'min:6'
            ]);
            $user = User::find(auth()->user()->id);
            $user->password = bcrypt($request->password);
            $user->save();

        }else{ //If the request has password then we only update the user's details
            //validate details
            $this->validate($request,[
                'fullname' => 'required|min:3',
                'email_address' => 'required|email|unique:users,email_address,'.auth()->user()->id,
                'date_of_birth' => 'required|date'
            ]);
    
            $user = User::find(auth()->user()->id);
            $user->fullname = $request->fullname;
            $user->email_address = $request->email_address;
            $user->date_of_birth = $request->date_of_birth;
            $user->save();
        }

      

        return response()->json(['status' => 'success']);
    }


}
