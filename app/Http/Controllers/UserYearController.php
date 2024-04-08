<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\UserYear;

class UserYearController extends Controller
{
    //

    public function store(Request $request)
    {
        try {

            // print_r($request);
            //Validated
            $validate = Validator::make($request->all(), 
            [
                'user_id' => 'required',
                'year_id' => 'required'
                
            ]);

            if($validate->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 401);
            }

            $userYear = UserYear::create([
                'user_id' => $request->user_id,
                'year_id' => $request->year_id
                
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Created Successfully',
                
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

            $joints = UserYear::all();

            return response()->json([
                'status' => true,
                'years' => $joints
            ]);

        }catch(\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}
