<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    //
    public function store(Request $request)
    {
        try {

            // print_r($request);
            //Validated
            $validateUser = Validator::make($request->all(), 
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'contact' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'contact' => $request->contact,
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    public function index(){
        try{

            $users = User::all();

            return response()->json([

                'status' => true,
                'users' => $users
            ]);

        }catch(\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    public function update(Request $request, $id){
        try{

            $validateUser = Validator::make($request->all(), 
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'contact' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if (User::where('id', $id)->exists()){

                $user = User::find($id);


                $employee->sur_name =  $request->sur_name;
                $employee->first_name = $request->first_name;
                $employee->other_names = $request->other_names;
                $employee->dob = $request->dob;
                $employee->gender = $request->gender;
                $employee->Married = $request->Married;
                $employee->email = $request->email;
                $employee->nationality = $request->nationality ;
                $employee->nin_number = $request->nin_number;  
                $employee->updated_at = now(); 

                $employee->save();
                
                return response()->json([
                    'status' => true,
                    'message' => 'Update was successful',

                ], 200);
                
            }else{
                return response()->json([
                    'status' => false,
                    'message' => "User doesnot exist",

                ], 402);
            }

        }catch(\Throwable $th){
            return response()->json([
                'status'=> false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), 
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function getContributions($id){
        try{
            
            $user = User::findOrFail($id);



            $contributions = $user->contributions;

            $result = [];

            foreach($contributions as $contribution){

                $cont['id'] = $contribution->id;
                
                $cont['year'] = $contribution->userYear->year->year;

                $cont['amount'] = $contribution->amount;
                $cont['month'] = $contribution->month;
                $cont['telephone'] = $contribution->telephone;
                $cont['network'] = $contribution->network;
                $cont['paid_at'] = $contribution->created_at;
                
                


                $result[] = $cont;
            }

            return response()->json([
                'status'=> true,
                'contributions' => $result
            ]);

        }catch(\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}
